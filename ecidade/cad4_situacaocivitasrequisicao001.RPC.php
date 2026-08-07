<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('libs/JSON.php');



$oJson = new services_json();
$oParam = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMessage = '';

try {

    db_inicio_transacao();

    switch ($oParam->exec) {

        case "consultaDados":

            $oFiltros = $oParam->aFiltros;

            $aWhere = array();

            if (!empty($oParam->aFiltros)) {

                if (!empty($oFiltros->dataInicio)) {

                    $dataIni =  implode('-', array_reverse(explode('/',  $oFiltros->dataInicio)));
                    $aWhere[] = "rq01_dataenvio >= '{$dataIni}'";
                }

                if (!empty($oFiltros->dataFinal)) {
                    $dataFim =  implode('-', array_reverse(explode('/',  $oFiltros->dataFinal)));
                    $aWhere[] = "rq01_dataenvio <= '{$dataFim}'";
                }
            }

            $sSql = " SELECT * FROM requisicaocivitas INNER JOIN requisicaocivitassituacao 
                      ON  requisicaocivitassituacao.rq02_sequencial = requisicaocivitas.rq01_situacao";

            if (sizeof($aWhere) > 0) {
                $sWhere = implode(" AND ", $aWhere);
                $sSql .= " WHERE {$sWhere}";
            }


            $rsSqlCivitasEnvio = pg_query($sSql);

            $aDados = db_utils::makeCollectionFromRecord($rsSqlCivitasEnvio, function($dado) use ($oFiltros) {

                $item = new stdClass();

                $oData = new DateTime($dado->rq01_dataenvio);
                $item->codigo = $dado->rq01_sequencial;
                $item->data   =  $oData->format("d/m/Y ");;
                $item->envio  =  $dado->rq01_sequencial;
                $item->status = $dado->rq02_descricao;

                return $item;

            });

            if (sizeof($aDados) <= 0) {
                throw new \Exception("Nenhum registro foi encontrado com os filtros informados.");
            }

            $oRetorno->dados = $aDados;

            break;

        case 'consultaOcorrencias':
            $oFiltros = $oParam->aFiltros;

            if (empty($oFiltros->idEnvio)) {
                throw new \Exception("Nenhum registro foi encontrado com  esse envio");
            }

            $sSql = " SELECT * FROM requisicaocivitas WHERE rq01_sequencial = ". $oFiltros->idEnvio ;
            $rsSqlCivitasEnvio = pg_query($sSql);

            $aEnvios = pg_fetch_all($rsSqlCivitasEnvio);

            $aDados = array();

            foreach ($aEnvios as  $aEnvio) {


                if (!empty($aEnvio['rq01_descricao'])) {
                    $json = json_decode($aEnvio['rq01_descricao'],true);
                    if (!empty($json) && is_array($json)) {
                        foreach ($json as $value) {
                            $item = new stdClass();
                            $item->descricao = $value;
                            $aDados[] = $item;
                        }
                    }
                }
            }

            $oRetorno->ocorrencias = $aDados;

            break;
    }

    db_fim_transacao(false);

} catch (Exception $eErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = $eErro->getMessage();
}

$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);