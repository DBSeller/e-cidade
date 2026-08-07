<?php

use ECidade\Tributario\Juridico\ProcessoEletronico\Integracao;
use ECidade\Tributario\Juridico\ProcessoEletronico\ProcessoEletronico;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\Configuracao;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\ProcessoEletronico as ProcessoEletronicoRepository;
use ECidade\Tributario\Juridico\ProcessoEletronico\Service\EnvioRemessaService;
use ECidade\Tributario\Juridico\ProcessoEletronico\Service\MovimentacaoService;
use ECidade\Tributario\Juridico\ProcessoEletronico\Remessa;

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));


$oJson = json::create();
$oPost = db_utils::postMemory($_POST);
$oParam = $oJson->parse(db_stdClass::db_stripTagsJson(str_replace("\\", "", $oPost->json)));

$oListaCda = new cl_listacda;
$oInicialcert = new cl_inicialcert;
$oArrecad = new cl_arrecad;
$oDbConfig = new cl_db_config;
$oCertidArqremessa = new cl_certidarqremessa;
$clparjuridico = new cl_parjuridico;
$clcgm = new cl_cgm;
$oAdvog = new cl_advog;

$lErro = false;
$lInicialAtiva = false;


$sMensagem = "";

$iNextCertidarqremessa = '';
$sDataGeracao = date('Y-m-d', db_getsession('DB_datausu'));
$sDataGeracaoEnvio = date('YmdHis', db_getsession('DB_datausu'));
$iCodLayOut = 97;

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->message = 1;
$Pais = "BR";

$instituicao = InstituicaoRepository::getInstituicaoSessao();

switch ($oParam->exec) {

    case 'processar' :


        $iLista = $oParam->iLista;
        $nValorTotalInicial = 0;
        $nValorTotalCertid = 0;
        $iInicial = null;
        $iExercCDA = null;
        $iMenorInicial = 0;
        $iCodMunic = "";
        $pArquivoTxt = "";

        try {

            $configuracaoTJ = Configuracao::getPorInstituicao($instituicao->getCodigo());

            if (empty($configuracaoTJ)) {

                $mensagem = "Antes de realizar o envio dos processos para o TJ, é necessário a configuracao dos ";
                $mensagem .= "dados de integração em 'DB:TRIBUTÁRIO > Jurídico > Procedimentos > Processo Eletrônico > Configurações.'";
                throw new \BusinessException($mensagem);
            }

            $integracao = new Integracao($iLista, $configuracaoTJ);

            $aDados = $integracao->getIniciaisParaEnvio(
                array(Integracao::SITUACAO_ASSINADO,
                    Integracao::SITUACAO_RETORNO_ERRO
                ),
                $oParam->processosEletronicos);

            $listaErros = array();

            foreach ($aDados as $oDados) {

                db_inicio_transacao();

                $oProcessoEletronico = ProcessoEletronicoRepository::getByCodigo($oDados->codigo_processo_eletronico);

                $oEnvioService = new EnvioRemessaService($configuracaoTJ, $instituicao, $integracao, $oProcessoEletronico);

                $remessa = new Remessa($configuracaoTJ);

                $oRetorno = $remessa->enviar($oEnvioService->getObjectToSend($oDados));


                $oMovimentacaoService = new MovimentacaoService($instituicao, $oProcessoEletronico);

                try {

                    $oMovimentacaoService->salvaMovimentacao($oRetorno, $oDados->inicial);
                } catch (\Exception  $e) {
                    db_fim_transacao(true);
                    $listaErros[] = $e->getMessage();
                }

                db_fim_transacao(false);
            }

            $oRetorno->message = "Distribuição Eletrônica Realizada com Sucesso!";
            if (count($listaErros) > 0) {

                $oRetorno->message .= "\n Algumas iniciais não foram distribuidas, pois existem dados inconsistentes.\n";
                $oRetorno->message .= "Verique o relatório de inconsistências";
            }

        } catch (Exception $eException) {

            db_fim_transacao(true);
            $oRetorno->status = 0;
            $oRetorno->erro = true;
            $oRetorno->message = $eException->getMessage();
        }

        $oRetorno->dados = $pArquivoTxt;
        break;

}

echo $oJson->stringify($oRetorno);

?>
