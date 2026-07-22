<?php
require_once 'libs/db_libpostgres.php';
/**
 * Class DBQueryLog
 */
class DBQueryLog {

  private $inicio = false;

  private $sLogFile = null;

  /**
   * Instancia unica da classe
   * @var DBQueryLog
   */
  private static $sInstance = null;

  /**
   * Metodo que define a criação da instancia da classe
   * @return DBQueryLog
   */
  private static function getInstance() {

    if (self::$sInstance == null) {
      self::$sInstance = new DBQueryLog();
    }
    return self::$sInstance;
  }

  /**
   * Inicia a criacao do Log
   * @param $sNameLog
   */
  public static function init($sNameLog) {

    self::getInstance()->sLogFile = 'tmp/AnaliseQueryLog'.$sNameLog.'.sql';
    if (file_exists(self::getInstance()->sLogFile)) {
      unlink(self::getInstance()->sLogFile);
    }
    self::getInstance()->inicio   = true;
    self::getInstance()->createTable();
    $_SESSION["register_query_analise"] = true;
  }

  private function createTable() {

    $oPostgresUitls = new PostgreSQLUtils();
    if (!$oPostgresUitls->isTableExists('analise_tempo')) {
      db_query('create table analise_tempo (duracao numeric, sql text, linha integer, arquivo text)');
    }
    pg_query('truncate table analise_tempo');
  }

  /**
   * Escreve a linha do log
   * @param $query
   * @param $time
   */
  public static function writeLine($query, $time) {

    if (empty($_SESSION["register_query_analise"])) {
      return ;
    }
    $aRastros   = debug_backtrace();
    $iLinha     = $aRastros[2]['line'];
    $sArquivo   = explode("/",$aRastros[2]['file']);
    $sArquivo   = $sArquivo[count($sArquivo) - 1];
    $aFunction  = $aRastros[3];
    $sArquivo .=" ({$aFunction['function']})";

    $tempoDecorrido = $time;
    $query          = str_replace(array("\n", "\r", "\r\n"), " ", $query);
    pg_query("insert into analise_tempo (duracao, sql, linha, arquivo) values ($tempoDecorrido, '".pg_escape_string($query)."', $iLinha, '$sArquivo')");
  }

  /**
   * Termina a escuta do log, e executa os inserts na base de dados
   * @param bool $runInsert apos a escuta, grava o arquivo de log no banco de dados
   */
  public static function end($runInsert = false) {

    if ($runInsert) {

      $aConteudo = file(self::getInstance()->sLogFile);
      foreach ($aConteudo as $sConteudo) {
        //pg_query($sConteudo);
      }
    }
    self::getInstance()->inicio = false;
    unset($_SESSION["register_query_analise"]);
  }

  public static function isStarted() {
    return self::getInstance()->inicio;
  }
}
