<?php
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));

use ECidade\Financeiro\Contabilidade\Importacao\Siconfi\TipoRecursos as Importacao;
use ECidade\Financeiro\Contabilidade\Exportacao\Siconfi\TipoRecursos as Exportacao;

$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->message = 1;

try {
    db_inicio_transacao();
    switch ($oParam->exec) {
        case 'importarArquivo':
            $oFiles = db_utils::postMemory($_FILES);

            if (strtolower(substr($oFiles->arquivo['name'], -4)) != '.csv') {
                throw new BusinessException("Arquivo importado com formato inválido! Arquivo deve ser do formato CSV.");
            }

            if (trim(file_get_contents($oFiles->arquivo['tmp_name'])) == "") {
                throw new BusinessException("Não é possível importar arquivo vazio.");
            }

            $tipoRecurso = new Importacao();
            $tipoRecurso->setAnoImportacao($oParam->anoSelecionado);
            $tipoRecurso->setAnoServidor(date("Y"));
            $tipoRecurso->import($oFiles->arquivo['tmp_name']);

            $erros = $tipoRecurso->getErros();
            if (count($erros) > 0) {
                throw new Exception("Erro ao importar os codigos (" . implode(",", $erros) . ")");
            }

            db_fim_transacao();
            $oRetorno->sMessage = utf8_encode("Importação efetuada com sucesso!");
            break;
        case "downloadArquivo":
            $oExportacao = new Exportacao();
            $oRetorno->sNomeArquivo = urlencode($oExportacao->export($oParam->anoSelecionado));
            $oRetorno->sNome = urlencode("Recursos");
            break;
    }
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = utf8_encode($eErro->getMessage());
}

$oRetorno->erro = $oRetorno->status;
echo $oJson->encode($oRetorno);
