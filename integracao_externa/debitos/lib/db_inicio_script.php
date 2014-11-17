<?
// Desabilita tempo máximo de execucao
set_time_limit(0);

// Arquivo de constantes de configuração
require_once("db_constants.php");


// Bibliotecas
include_once("db_libconversao.php");
include_once(PATH_ECIDADE."/libs/db_utils.php");
include_once(PATH_ECIDADE."/std/db_stdClass.php");
include_once(PATH_ECIDADE."/model/dataManager.php");

// Timestamp para data/Hora
$sTimeStampInicio = date("Ymd_His");

// Verifica se nao foi setado o nome do script
if(!isset($sNomeScript)) {
  $sNomeScript = basename(__FILE__);
} 

// Seta nome do arquivo de Log, caso já não exista
if(!defined("DB_ARQUIVO_LOG")) {
  $sArquivoLog = "log/".$sNomeScript."_".$sTimeStampInicio.".log";
  define("DB_ARQUIVO_LOG", $sArquivoLog);
}

if(!is_dir("log")) {
  mkdir("log");
}

// Logs...
db_log("", $sArquivoLog);
db_log("*** INICIO Script ".$sNomeScript." ***", $sArquivoLog);
db_log("", $sArquivoLog);

db_log("Arquivo de Log: $sArquivoLog", $sArquivoLog);
db_log("    Script PHP: ".$sNomeScript, $sArquivoLog);
db_log("", $sArquivoLog);

?>
