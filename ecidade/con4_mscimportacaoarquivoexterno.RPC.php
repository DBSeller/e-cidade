<?php

use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\ArquivoExterno\Importacao;

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = 1;
$oRetorno->erro = false;

try {

    db_inicio_transacao();
    switch($oParam->exec) {

        case 'importarArquivo' :

            $oFiles = db_utils::postMemory($_FILES);
            if (strtolower(substr($oFiles->arquivo['name'], -4)) != '.csv') {
                throw new BusinessException("Arquivo importado com formato inválido! Arquivo deve ser do formato CSV.");
            }

            if (trim(file_get_contents($oFiles->arquivo['tmp_name'])) == "") {
                throw new BusinessException("Não é possível importar arquivo vazio.");
            }

            $competencia = new \ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Competencia($oParam->ano, $oParam->mes);
            $importacaoArquivoExterno = new Importacao($competencia);
            $importacaoArquivoExterno->setCodigoTribunal($oParam->codigo_tribunal);
            $importacaoArquivoExterno->importar($oFiles->arquivo['tmp_name']);
            $oRetorno->message = "Arquivo {$oFiles->arquivo['name']} importado com sucesso.";
            db_fim_transacao(false);
            break;

        case "getArquivos":

            $importacaoArquivoExterno = new Importacao();
            $oRetorno->arquivos = $importacaoArquivoExterno->getArquivos();
            break;

        case "remover":

            $importacaoArquivoExterno = new Importacao();
            $importacaoArquivoExterno->remover($oParam->codigo);
            $oRetorno->message = "Arquivo removido sucesso.";
            $oRetorno->arquivos = $importacaoArquivoExterno->getArquivos();
            db_fim_transacao(false);
            break;
    }


} catch (Exception $eErro){
    db_fim_transacao(true);
    $oRetorno->iStatus  = 2;
    $oRetorno->erro = true;
    $oRetorno->message = utf8_encode($eErro->getMessage());
}

$oRetorno->erro = $oRetorno->status;
echo $oJson->encode($oRetorno);
