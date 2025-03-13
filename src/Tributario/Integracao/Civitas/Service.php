<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Integracao\Civitas;

use \cl_db_sysregrasacessoip as RegrasAcessoIp;
use ECidade\Tributario\Integracao\Civitas\Repository\Importador as ImportadorRepository;
use ECidade\Tributario\Integracao\Civitas\Repository\Exportacao as ExportacaoRepository;
use ECidade\Tributario\Integracao\Civitas\Arquivo\Exportacao as ArquivoExportacao;
use ECidade\Tributario\Integracao\Civitas\Validador;
use ECidade\Tributario\Integracao\Civitas\Logger\RequestLogger;


/**
 * Class Service
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package ECidade\Tributario\Integracao\Civitas
 */
class Service
{
    const FILE_PATH = "tmp/";

    /**
     * Função que realiza a importacao de dados do Webservice, para o recadastramento de imóveis.
     * @param string $arquivoXml
     * @return string
     */
    public function cargaEcidade($arquivoXml)
    {

        $sequencialRequisicao =  null;

        try {

            $oXml = simplexml_load_string($arquivoXml);
            $this->autenticarAcesso($oXml);

            $edificacoes          = $oXml->parametros->edificacoes;
            $lotes                = $oXml->parametros->lotes;
            $testadas             = $oXml->parametros->testadas;
            $data                 = $oXml->parametros->data;

            RequestLogger::log(
                RequestLogger::INFO,
                'civitas_carga', $data[0] , '' ,  array(
                'xml' =>  $arquivoXml
            ));


            if (!empty($edificacoes)) {
                $nomeArquivoEdificacoes = "edificacoes" . time() . ".csv";
                file_put_contents(self::FILE_PATH . $nomeArquivoEdificacoes, base64_decode($edificacoes));
            }

            if (!empty($lotes)) {
                $nomeArquivoLotes       = "lotes" . time() . ".csv";
                file_put_contents(self::FILE_PATH . $nomeArquivoLotes, base64_decode($lotes));
            }


            $this->validarDadosCargaEcidade($edificacoes, $lotes, $testadas, $data);

            $sequencialRequisicao = ImportadorRepository::atualizarSituacao(ImportadorRepository::CODIGO_PENDENTE, null ,  $data[0]);

            $nomeArquivoTestadas    = "testadas" . time() . ".csv";
            file_put_contents(self::FILE_PATH . $nomeArquivoTestadas, base64_decode($testadas));

            $arquivos = array();

            if (!empty($nomeArquivoLotes) ) {
                $arquivos[] = array(
                    "Nome" => $nomeArquivoLotes,
                    "TipoArquivo" => 1,
                    "Data" => $data,
                    "Caminho" => self::FILE_PATH
                );
            }

            if (!empty($nomeArquivoEdificacoes)) {
                $arquivos[] = array(
                    "Nome" => $nomeArquivoEdificacoes,
                    "TipoArquivo" => 2,
                    "Data" => $data,
                    "Caminho" => self::FILE_PATH
                );
            }

            if (!empty($nomeArquivoTestadas)) {
                $arquivos[] = array(
                    "Nome" => $nomeArquivoTestadas,
                    "TipoArquivo" => 3,
                    "Data" => $data,
                    "Caminho" => self::FILE_PATH
                );
            }

            db_inicio_transacao();

            $oValidador = new Validador($arquivos);

            if ($oValidador->validarArquivosImportacao()) {
                $job = new \Job();
                $job->setNome('Civitas');
                $job->setCodigoUsuario(1);
                $time = new \DateTime();
                $job->setMomentoCricao($time->modify('+ 1 minute')->getTimestamp());
                $job->setDescricao('Civitas');
                $job->setNomeClasse('CivitasTask');
                $job->setTipoPeriodicidade(\Agenda::PERIODICIDADE_UNICA);

                if (!empty($nomeArquivoEdificacoes)) {
                    $job->adicionarParametro("arquivoEdificacoes", $nomeArquivoEdificacoes);
                }

                if ($nomeArquivoLotes) {
                    $job->adicionarParametro("arquivoLotes", $nomeArquivoLotes);
                }

                $job->adicionarParametro("arquivoTestadas", $nomeArquivoTestadas);
                $job->adicionarParametro("data", $data);
                $job->adicionarParametro("sequencialRequisicao", $sequencialRequisicao);
                $job->setCaminhoPrograma('model/cadastro/CivitasTask.model.php');
                $job->salvar();

                $retorno = new \stdClass();
                $retorno->situacao = ImportadorRepository::CODIGO_SUCESSO;
            } else {
                $retorno = $oValidador->getSituacao();
            }

            $retornoMontado = $this->montarRetorno($retorno);
            db_fim_transacao();
        } catch (\Exception $exception) {

            if (\db_utils::inTransaction()) {
                db_fim_transacao(true);
            }

            $retorno = new \stdClass();
            $retorno->situacao = ImportadorRepository::CODIGO_ERRO;
            $retorno->erros = array($exception->getMessage());
            $retornoMontado = $this->montarRetorno($retorno);

            ImportadorRepository::atualizarSituacao(ImportadorRepository::CODIGO_ERRO,$sequencialRequisicao, $data[0]);
        }

        return $retornoMontado;
    }

    /**
     * Função do webservice para consulta de imóveis atualizados.
     * @param string $arquivoXml
     * @return string
     */
    public function exportacaoEcidade($arquivoXml)
    {


        try {
            $oXml = simplexml_load_string($arquivoXml);
            $this->autenticarAcesso($oXml);

            RequestLogger::log(
                RequestLogger::INFO,
                'civitas_exportacaoEcidade', $oXml->parametros->data , '' ,  array(
                'xml' =>  $arquivoXml
            ));


            $oData = new \DBDate($oXml->parametros->data);

            db_inicio_transacao();

            $aDados = ExportacaoRepository::getDados($oData);
            $dadosMatriculasRejeitadas = ExportacaoRepository::getMatriculasNovasRejeitadas($oData);

            $aDados = array_merge($aDados, $dadosMatriculasRejeitadas);

            if (empty($aDados)) {
                throw new \Exception("Não foram encontradas atualizações na data informada.");
            }

            $oArquivoExportacao = new ArquivoExportacao($aDados);

            $sArquivoExportacao = $oArquivoExportacao->getArquivoBase64();

            $retorno = new \stdClass();
            $retorno->situacao = ImportadorRepository::CODIGO_SUCESSO;
            $retorno->arquivo  = $sArquivoExportacao;

            $xmlRetorno = $this->montarRetorno($retorno);

            db_fim_transacao();
        } catch (\Exception $exception) {
            if (\db_utils::inTransaction()) {
                db_fim_transacao(true);
            }

            $retorno = new \stdClass();
            $retorno->situacao = ImportadorRepository::CODIGO_ERRO;
            $retorno->erros    = array($exception->getMessage());

            $xmlRetorno = $this->montarRetorno($retorno);
        }

        return $xmlRetorno;
    }

    /**
     * Retorna o token privado para o cliente acessar as rotinas do webservice.
     * @param string $arquivoXml
     * @return string
     */
    public function getToken($arquivoXml)
    {

        RequestLogger::log(
            RequestLogger::INFO,
            'civitas_getToken', '' , '' ,  array(
            'xml' =>  $arquivoXml
        ));


        try {
            $oXml         = simplexml_load_string($arquivoXml);
            $idAcesso     = $oXml->certificado->codigo;
            $tokenUsuario = $oXml->certificado->token;

            $tokenPublico    = $this->getTokenPublicoByCodigoAcesso($idAcesso);
            $hashAutenticado = $this->getHashSha256($tokenPublico . date('Ymd'));

            if (!empty($tokenPublico) && $tokenUsuario == $hashAutenticado) {
                $pid          = uniqid();
                $tokenPrivado = $this->getHashSha256($tokenPublico . $pid);
                $this->setTokenPrivadoByCodigoAcesso($idAcesso, $tokenPrivado);

                $retorno = new \stdClass;
                $retorno->situacao    = 1;
                $retorno->token = $tokenPrivado;

                $xmlRetorno = $this->montarRetorno($retorno);
            } else {
                throw new \Exception("Autenticação não autorizada.");
            }
        } catch (\Exception $exception) {
            $this->limparTokenPrivadoByCodigoAcesso($idAcesso);

            if (\db_utils::inTransaction()) {
                db_fim_transacao(true);
            }

            $retorno = new \stdClass();
            $retorno->situacao = 2;
            $retorno->erros = array($exception->getMessage());
            $xmlRetorno = $this->montarRetorno($retorno);
        }


        return $xmlRetorno;
    }

    /**
     * Realiza a autenticação para acessar as rotinas do webservice do civitas.
     * @param \SimpleXMLElement $oXml
     * @return bool
     * @throws \Exception
     */
    private function autenticarAcesso($oXml)
    {
        $idAcesso        = $oXml->certificado->codigo;
        $token           = $oXml->certificado->token;
        $corpoRequisicao = $oXml->parametros->asXML();

        if (!$this->validarTokenPrivado($idAcesso, $token, $corpoRequisicao)
            || empty($token)
            || empty($corpoRequisicao)
            || empty($idAcesso)) {
            $this->limparTokenPrivadoByCodigoAcesso($idAcesso);
            throw new \Exception("Requisição não autorizada.");
        }

        return true;
    }

    /**
     * Valida se o token privado passado pelo usuário é válido.
     * @param int $idAcesso
     * @param string $token
     * @param string $corpoRequisicao
     * @return bool
     */
    private function validarTokenPrivado($idAcesso, $token, $corpoRequisicao)
    {
        $tokenPrivado    = $this->getTokenPrivadoByCodigoAcesso($idAcesso);
        $hashAutenticado = $this->getHashSha256($tokenPrivado . $corpoRequisicao);

        $this->limparTokenPrivadoByCodigoAcesso($idAcesso);

        if (!empty($tokenPrivado) && $hashAutenticado == $token) {
            return true;
        }

        return false;
    }

    /**
     * Retorna token publico dado um id de acesso.
     * @param int $idAcesso
     * @return string
     */
    private function getTokenPublicoByCodigoAcesso($idAcesso)
    {
        $oDaoAcesso = new RegrasAcessoIp;
        $sqlAcesso  = $oDaoAcesso->sql_query_file($idAcesso, 'db48_tokenpublico');
        $rs         = \db_query($sqlAcesso);

        return \db_utils::fieldsMemory($rs, 0)->db48_tokenpublico;
    }

    /**
     * Retorna token privado dado um id de acesso.
     * @param int $idAcesso
     * @return string
     */
    private function getTokenPrivadoByCodigoAcesso($idAcesso)
    {
        $oDaoAcesso = new RegrasAcessoIp;
        $sqlAcesso  = $oDaoAcesso->sql_query_file($idAcesso, 'db48_tokenprivado');
        $rs         = \db_query($sqlAcesso);

        return \db_utils::fieldsMemory($rs, 0)->db48_tokenprivado;
    }

    /**
     * Salva token privado gerado pela autenticação.
     * @param int $idAcesso
     * @param string $tokenPrivado
     * @return bool
     */
    private function setTokenPrivadoByCodigoAcesso($idAcesso, $tokenPrivado)
    {
        $sql = "update configuracoes.db_sysregrasacessoip set db48_tokenprivado = '{$tokenPrivado}' where db48_idacesso = {$idAcesso}";
        $rs = \db_query($sql);

        return $rs;
    }

    /**
     * Limpa o token privado gerado pela requisição.
     * @param int $idAcesso
     * @return bool
     */
    private function limparTokenPrivadoByCodigoAcesso($idAcesso)
    {
        return $this->setTokenPrivadoByCodigoAcesso($idAcesso, '');
    }

    /**
     * Retorna encriptação sha256 de uma chave.
     * @return string
     */
    private function getHashSha256($chave)
    {
        return hash('sha256', $chave);
    }

    /**
     * Método que monta o xml de retorno
     * @param \stdClass $retorno
     * @return string
     */
    private function montarRetorno($retorno)
    {
        $documento = new \DOMDocument('1.0', 'ISO-8859-1');

        $tagRoot = $documento->createElement("retorno");

        $situacao = $documento->createElement("situacao", $retorno->situacao);
        $tagRoot->appendChild($situacao);


        if (isset($retorno->erros)) {
            $erros = $documento->createElement("erros");

            foreach ($retorno->erros as $erroRetorno) {
                $erro  = $documento->createElement("erro", urlencode($erroRetorno));
                $erros->appendChild($erro);
            }

            $tagRoot->appendChild($erros);
        } else if (isset($retorno->arquivo)) {
            $arquivo = $documento->createElement("arquivo", $retorno->arquivo);

            $tagRoot->appendChild($arquivo);
        } else if (isset($retorno->token)) {
            $token = $documento->createElement("token", $retorno->token);

            $tagRoot->appendChild($token);
        }

        $documento->appendChild($tagRoot);

        return $documento->saveXML();
    }

    /**
     * Valida se já foi feita uma atualização de IPTU com a data informada.
     * @param string
     * @return bool
     */
    private function validarDataAtualizacao($data)
    {
        $sql     = "select j142_dataarquivo from atualizacaoiptuschema where j142_dataarquivo = '{$data}';";
        $rs      = db_query($sql);
        $numRows = pg_num_rows($rs);

        if ($numRows < 1) {
            return true;
        }

        return false;
    }

    /**
     * Valida se uma data esta no formato YYYY-MM-DD
     * @param string $data
     * @return bool
     */
    private function validarFormatoData($data)
    {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Valida se os dados para do serviço carga_ecidade são válidos.
     * @param string $edificacoes
     * @param string $lotes
     * @param string $testadas
     * @param string $data
     * @throws \ParameterException
     */
    private function validarDadosCargaEcidade($edificacoes, $lotes, $testadas, $data)
    {
        if (empty($data)) {
            throw new \ParameterException("A data dos arquivos não foi informada.");
        }

        if (!$this->validarDataAtualizacao($data)) {
            throw new \ParameterException("Já existe uma importação com a data {$data} informada.");
        }

        if (!$this->validarFormatoData($data)) {
            throw new \ParameterException("A data {$data} informada deve estar no formato AAAA-MM-DD.");
        }
    }
}
