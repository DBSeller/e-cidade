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

use ECidade\Tributario\Juridico\ProcessoEletronico\Processamento;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\Configuracao;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\Documento;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\ProcessoEletronico;
use ECidade\Tributario\Juridico\Repository\Parametro;

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_libdocumento.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));

/**
 * @todo remover essa coisa....
 */
ini_set("display_errors", "Off");
$post = db_utils::postMemory($_POST);
$json = JSON::create();
$parametros = $json->parse(db_stdClass::db_stripTagsJson(str_replace("\\", "", $post->json)));

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';
$dataSistema = new \DateTime(date("Y-m-d", db_getsession("DB_datausu")));
$usuario = UsuarioSistemaRepository::getPorCodigo(db_getsession('DB_id_usuario'));
$instituicao = InstituicaoRepository::getInstituicaoSessao();

try {

    $configuracaoTJ = Configuracao::getPorInstituicao($instituicao->getCodigo());
    if (empty($configuracaoTJ)) {

        $mensagem = "Antes de realizar o envio dos processos para o TJ, é necessário a configuracao dos ";
        $mensagem .= "dados de integração em 'DB:TRIBUTÁRIO > Jurídico > Procedimentos > Processo Eletrônico > Configurações.'";
        throw new \BusinessException($mensagem);
    }

    db_inicio_transacao();
    switch ($parametros->exec) {

        case 'processarLista' :

            require_once("phar://" . __DIR__ . '/pades.phar');

            $processamentoIniciais = new Processamento($parametros->lista, $dataSistema, $usuario, $instituicao);
            $parametrosJuridico = Parametro::getPorInstituicaoEAno($instituicao, db_getsession("DB_anousu"));

            $processamentoIniciais->agruparPor($parametros->agrupar);
            $arquivo = $processamentoIniciais->processarIniciais();
            $retorno->arquivo = $arquivo;
            break;

        case 'pesquisarIniciais':


            $integracao = new \ECidade\Tributario\Juridico\ProcessoEletronico\Integracao($parametros->lista,
                $configuracaoTJ);
            $situacao = array($parametros->situacao);
            if ($parametros->situacao == 0) {
                $situacao = null;
            }
            $iniciais  = $integracao->getIniciaisParaEnvio($situacao);
            foreach($iniciais as $inicial) {

               $inicial->documentos = array();
               $documentos = Documento::getPorProcessoEletronico($inicial->codigo_processo_eletronico);
               foreach ($documentos as $documentoProcesso) {

                   $documento = new \stdClass();
                   $documento->documento = $documentoProcesso->getNome();
                   $documento->arquivo   = $documentoProcesso->getConteudo();
                   $documento->tipo      = $documentoProcesso->getTipo();

                   $inicial->documentos[] = $documento;
               }

            }
            $retorno->iniciais = $iniciais;

            break;

        case 'verificarProcessamento':

            $integracao = new \ECidade\Tributario\Juridico\ProcessoEletronico\Integracao($parametros->lista,
                $configuracaoTJ);
            $retorno->deveProcessar = $integracao->temIniciaisDaListaSemProcessoEletronico();
            break;

        case 'gravarDocumentoAssinado':

            require_once("phar://" . __DIR__ . '/pades.phar');
            $retorno->files = array();

            $post = db_utils::postMemory($_POST);

            $parametros = json_decode(str_replace("\\", "", $post->json));
            $daoIntegracaoProcessoArquivo = new cl_integracaoprocessoeletronicoarquivo();

            foreach ($parametros->files as $file) {

                $pades = new Pades();
                $pades->buildFromMimeEnvelopment(base64_decode($file->content));
                $data = $pades->render();

                $sqlDadosArquivo = $daoIntegracaoProcessoArquivo->sql_query_file(null, "*", null,
                    "v40_integracaoprocessoeletronico = {$parametros->id} and v40_tipo = 1");
                $rsArquivo = db_query($sqlDadosArquivo);
                $arquivo = db_utils::fieldsMemory($rsArquivo, 0);

                $daoIntegracaoProcessoArquivo->v40_nome = $arquivo->v40_nome;
                $daoIntegracaoProcessoArquivo->v40_sequencial = $arquivo->v40_sequencial;
                $daoIntegracaoProcessoArquivo->v40_integracaoprocessoeletronico = $parametros->id;
                $daoIntegracaoProcessoArquivo->v40_arquivo = base64_encode($data);
                $daoIntegracaoProcessoArquivo->v40_data = $arquivo->v40_data;
                $daoIntegracaoProcessoArquivo->v40_tipo = $arquivo->v40_tipo;
                $daoIntegracaoProcessoArquivo->alterar(null, "v40_integracaoprocessoeletronico = {$parametros->id}");
                if ($daoIntegracaoProcessoArquivo->erro_status == 0) {
                    throw new \BusinessException("Erro ao processar assinatura eletrônica.\n{$daoIntegracaoProcessoArquivo->erro_msg}");
                }

                $processoEletronico = ProcessoEletronico::getByCodigo($parametros->processo_eletronico);
                $processoEletronico->setDataCalculo(new \DateTime());
                $processoEletronico->setSituacao(\ECidade\Tributario\Juridico\ProcessoEletronico\Integracao::SITUACAO_ASSINADO);

                file_put_contents("tmp/assinado." . $file->name, $data);
            }
            break;


        case 'atualizarDocumento' :

            require_once("phar://" . __DIR__ . '/pades.phar');

            $processamentoIniciais = new Processamento($parametros->lista, $dataSistema, $usuario, $instituicao);
            $parametrosJuridico = Parametro::getPorInstituicaoEAno($instituicao, db_getsession("DB_anousu"));

            $processoEletronico = ProcessoEletronico::getByCodigo($parametros->processo_eletronico);
            ProcessoEletronico::removerDocumentos($processoEletronico);
            $processoEletronico->setSituacao(\ECidade\Tributario\Juridico\ProcessoEletronico\Integracao::SITUACAO_PROCESSADO);
            $documentos = $processamentoIniciais->gerarDocumentoDaInicial($processoEletronico->getInicial());

            ProcessoEletronico::persistirDocumentos($processoEletronico, $documentos);
            ProcessoEletronico::persist($processoEletronico);

            $retorno->arquivo = $arquivo;
            break;

    }
    db_fim_transacao();
} catch (Exception $e) {

    $retorno->erro = true;
    $retorno->mensagem = $e->getMessage();

    db_fim_transacao(true);
}
echo $json->stringify($retorno);