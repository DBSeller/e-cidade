<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSelller Servicos de Informatica
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

require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

use ECidade\RecursosHumanos\ESocial\Model\Arquivo\QualificacaoCadastral\Geracao;
use ECidade\RecursosHumanos\ESocial\Repository\ImportacaoQualificacaoCadastral;
use ECidade\RecursosHumanos\ESocial\Entity\ImportacaoQualificacaoCadastral as Entity;

$parametros = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$retorno = new stdClass();
$retorno->status = 1;
$retorno->mensagem = '';

db_inicio_transacao();
try {

    switch ($parametros->executa) {
        case 'gerarArquivo':
            if (!isset($parametros->selecao)) {
                throw new ParameterException("Seleção não informado.");
            }
            if (!isset($parametros->matriculas)) {
                throw new ParameterException("Matricula(s) não informada(s).");
            }

            $geracao = new Geracao($parametros->selecao, $parametros->matriculas);
            $retorno->arquivos = $geracao->gerarArquivo();

            if (empty($retorno->arquivos)) {
                throw new \BusinessException("Nenhum servidor válido encontrado para o filtro informado.");
            }
            break;

        /**
         * Salva a importação do arquivo de qualificação cadastral
         */
        case 'importarArquivo':
            $arquivo = $parametros->arquivo;
            $partes = explode('/', $arquivo);
            $arquivo = array_pop($partes); // remove os diretorios para ficar só com o nome do arquivo

            $partes = explode('.', $arquivo);
            $situacao = array_pop($partes); // pega a situação do nome do arquivo

            $processado = trim(mb_strtolower($situacao)) === 'processado';

            $oid = DBLargeObject::criaOID(true);
            if (!DBLargeObject::escrita($parametros->caminhoArquivo, $oid)) {
                throw new Exception("Erro ao salvar arquivo no banco de dados.");
            }

            $qualificacao = new Entity();
            $qualificacao
                ->setData(new \DateTime(date('Y-m-d H:i:s')))
                ->setInstituicao(new \Instituicao(db_getsession('DB_instit')))
                ->setNomeArquivo($arquivo)
                ->setProcessado($processado)
                ->setArquivoOid($oid);

            $repository = new ImportacaoQualificacaoCadastral();
            $repository->save($qualificacao);

            $retorno->mensagem = "Importação salva com sucesso.";

            break;

        /**
         * escreve o arquivo em disco e retorna o path
         */
        case 'buscarCaminhoArquivo':
            $repository = new ImportacaoQualificacaoCadastral();
            $qualificacao = $repository->getById($parametros->codigo);
            $retorno->caminho = $qualificacao->getPathArquivo();
            break;

        case 'buscarArquivosImportados':
            $repository = new ImportacaoQualificacaoCadastral();
            $arquivos = $repository->getByInstituicao(InstituicaoRepository::getInstituicaoSessao()->getCodigo());
            $retorno->arquivos = array();
            foreach ($arquivos as $arquivo) {

                $stdArquivo = new stdClass();
                $retorno->arquivos[] = $arquivo->getPropriedadesStdClass();
            }
            break;
        case 'excluirArquivo':
            $repository = new ImportacaoQualificacaoCadastral();
            if ($repository->deleteFromId($parametros->codigo)) {
                $retorno->mensagem = 'Arquivo excluído com sucesso.';
            } else {
                throw new \Exception('Erro ao excluir arquivo.');
            }
            break;

        }
    db_fim_transacao(false);
} catch (Exception $oErro) {

    db_fim_transacao(true);
    $retorno->status = 2;
    $retorno->mensagem = $oErro->getMessage();
}

$retorno->erro = $retorno->status == 2;
echo JSON::create()->stringify($retorno);
