<?php
/**
 *      E-cidade Software Publico para Gestao Municipal
 *    Copyright (C) 2009  DBSeller Servicos de Informatica
 *                  www.dbseller.com.br
 *                e-cidade@dbseller.com.br
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
/**
 *
 */
class Log {

  static private $oInstancia = null;
  static private $iIndiceLog = 0;
  static private $aLog       = array();
  private $sCaminho;
  /**
   *  Contrutor privado da Classe
   *
   *  @param string $sLocal Caminho para salvar o LOG
   */
  private function __construct($sLocal = "tmp/LogECidade") {

    $sLocal .= date("_Ymd_H");
    $sLocal .= ".log";
    $this->sCaminho = $sLocal.".txt";
    $this->oLog = new DBLog("TXT", $sLocal);
  }

  /**
   *  Chama a insancia do log
   */
  public function escreverLog($sMensagem = '', $iTipo = DBLog::LOG_INFO) {
    return $this->oLog->escreverLog($sMensagem, $iTipo);
  }

  /**
   *  Escreve o Log
   *
   *  @return void
   */
  public static function write($sMensagem = '', $iTipo = DBLog::LOG_INFO) {

    // dump(db_getsession("DB_log", false));

    if ( !db_getsession("DB_log", false) ) {
      return;
    }

    $iIndiceLog = self::$iIndiceLog++;
    $aPartes    = explode("\n", $sMensagem);
    $aRastros   = debug_backtrace();
    $iLinha     = $aRastros[0]['line'];
    $sArquivo   = explode("/",$aRastros[0]['file']);
    $sArquivo   = $sArquivo[count($sArquivo) - 1];
    $aFunction  = $aRastros[1];
    $aRastros   = $aRastros[0];
    $sInfo      = str_pad("({$sArquivo}:{$iLinha})::{$aFunction['function']}", 70," ", STR_PAD_RIGHT);
    $sCola      = "$sInfo|";
    $iEspacos   = (count(debug_backtrace()) - 2)  * 2;
    $sEspacos   = str_pad("",$iEspacos," "/**Espaçador*/, STR_PAD_BOTH);

    $sLog       = "";
    foreach($aPartes as $sParte ) {
      $sLog .= self::$aLog[$iIndiceLog][] = "{$sCola} {$sEspacos} {$sParte}";
    }

    if ( empty(self::$oInstancia) ) {
      self::$oInstancia = new Log("tmp/".$sArquivo);
    }

    self::$oInstancia->escreverLog($sLog, $iTipo);
    return;
  }

  public function __destruct() {

$sCaminho = $this->sCaminho;
echo <<<HTML
<style>
  #saida_DBLog {
    font-weight: bold;
    margin: 5px;
    border-radius: 4px;
    display: block;
    padding: 10px;
    border: 1px solid #FFDD00;
    background-color: #FFFFCC;
    position:fixed;
    top:0;
    right:0;
  }
</style>
<div id="saida_DBLog">
  <a href='{$sCaminho}' target='_blank'>Arquivo de Log Gerado</a>
</div>
HTML;
  }
}
