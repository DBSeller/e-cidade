<?php

class DBReleaseNote {

  const DIRETORIO_RELEASE_NOTE = 'release_notes';

  private $sVersao = '';

  private $iUsuario;

  private $sNomeArquivo;

  private $iCodVersao;

  private $iCodRelease;

  public function __construct($idUsuario, $sNomeArquivo = '', $sVersao = null) {

    $this->iUsuario  = $idUsuario;
    $this->sNomeArquivo = $sNomeArquivo;

    if ( empty($sVersao) ) {

      $sVersao = $this->getPrimeiraVersaoNaoLida();
    }

    if ( !empty($sVersao) ) {

      $aVersao = explode(".", $sVersao);
      $this->iCodVersao = $aVersao[1];
      $this->iCodRelease = $aVersao[2];
    }

  }

  /**
   * Verifica se o changelog informado já foi lido pelo usuário.
   * @return boolean True se não foi lido, False se foi lido ou não existe.
   */
  public function check() {

    if ( empty($this->iCodVersao) ||  empty($this->iCodRelease) ) {
      return false;
    }

    $rsMudancaLidas = $this->getMudancasLidas();

    $iTotalLidos = pg_num_rows($rsMudancaLidas);

    $aReleaseNoteNomeArquivo = $this->getVersoesPorNomeArquivo();
    return $iTotalLidos != count($aReleaseNoteNomeArquivo);
  }

  private function getVersoesPorNomeArquivo($iSorting = 0) {

    $aVersoes = scandir(self::DIRETORIO_RELEASE_NOTE, $iSorting);

    $aRetornoReleaseNotes = array();

    foreach ($aVersoes as $sVersao) {

      if ($sVersao == "." || $sVersao == "..") {
        continue;
      }

      $sNomeArquivo = self::DIRETORIO_RELEASE_NOTE . "/" . $sVersao . "/" . $this->sNomeArquivo . ".md";

      if (file_exists($sNomeArquivo)) {
        $aRetornoReleaseNotes[] = $sVersao;
      }

    }

    return $aRetornoReleaseNotes;
  }

  public function getVersaoFormatada() {
    return 'v2.' . $this->iCodVersao . '.' . $this->iCodRelease;
  }

  public function getContent() {

    $sCaminhoArquivo = self::DIRETORIO_RELEASE_NOTE . "/{$this->getVersaoFormatada()}/$this->sNomeArquivo.md";

    if (!file_exists($sCaminhoArquivo)) {
      return "";
    }

    $sConteudoArquivoMD = file_get_contents($sCaminhoArquivo);

    require_once("ext/php/Michelf/MarkdownExtra.inc.php");

    $sContent = \Michelf\MarkdownExtra::defaultTransform($sConteudoArquivoMD);

    return $sContent;
  }

  public function getProximaVersao($lSomenteNaoLidos = false) {
    return $this->getVersaoOrdenada(0, $lSomenteNaoLidos);
  }

  public function getVersaoAnterior($lSomenteNaoLidos = false) {
    return $this->getVersaoOrdenada(1, $lSomenteNaoLidos);
  }

  public function getVersaoOrdenada($iSorting, $lSomenteNaoLido = true) {

    $aVersoes = $this->getVersoesPorNomeArquivo($iSorting);

    $rsMudancaLidas = $this->getMudancasLidas($iSorting);

    $iInicio = 0;

    foreach ($aVersoes as $iIndexVersoes => $sVersao) {

      if ( $sVersao == $this->getVersaoFormatada() ) {
        $iInicio = $iIndexVersoes;
        break;
      }
    }

    if (!$lSomenteNaoLido) {
      return isset($aVersoes[$iInicio+1]) ? $aVersoes[$iInicio+1] : "";
    }

    $sVersao = "";
    $aVersoesLidas = array();

    if (pg_num_rows($rsMudancaLidas) > 0) {
      $aVersoesLidas = db_utils::getCollectionByRecord($rsMudancaLidas);
    }

    for ($iIndexVersoes = ($iInicio+1); $iIndexVersoes < count($aVersoes); $iIndexVersoes++) {

      $sVersao = $aVersoes[$iIndexVersoes];

      foreach ($aVersoesLidas as $oDadoVersaoLida) {

        $sVersaoLida = "v2.{$oDadoVersaoLida->db30_codversao}.{$oDadoVersaoLida->db30_codrelease}";

        if ( $sVersao == $sVersaoLida ) {
          $sVersao = "";
          continue 2;
        }
      }

      break;

    }

    return $sVersao;
  }

  public function getVersao() {
    return "2.{$this->iCodVersao}.{$this->iCodRelease}";
  }

  public function getMudancasLidas($iSorting = 0) {

    $sSql  = "select *                                                         ";
    $sSql .= "  from db_releasenotes                                           ";
    $sSql .= " inner join db_versao on db30_codver = db147_db_versao           ";
    $sSql .= " where db147_nomearquivo   = '{$this->sNomeArquivo}'                ";
    $sSql .= "  and db147_id_usuario = {$this->iUsuario}                       ";
    $sSql .= " order by db147_db_versao " . ($iSorting == 1 ? "desc" : "asc");

    $rsCheckReleaseNote = db_query($sSql);

    if  (!$rsCheckReleaseNote) {
      throw new DBException("Erro ao buscar as mudanças lidas do usuário.");
    }

    return $rsCheckReleaseNote;
  }

  public function getPrimeiraVersaoNaoLida() {

    $aVersoes = $this->getVersoesPorNomeArquivo();

    if (empty($aVersoes)) {
      return false;
    }

    $rsMudancaLidas = $this->getMudancasLidas();

    if (pg_num_rows($rsMudancaLidas) == 0 ) {
      return $aVersoes[0];
    }

    $aMudancas = db_utils::getCollectionByRecord($rsMudancaLidas);

    foreach ($aVersoes as $iIndex => $sVersao) {

      if (isset ($aMudancas[$iIndex]) ) {

        $sVersaoMudanca = "v2.{$aMudancas[$iIndex]->db30_codversao}.{$aMudancas[$iIndex]->db30_codrelease}";
        if ( $sVersao != $sVersaoMudanca ) {
          return $sVersao;
        }

      } else {
        return $sVersao;
      }

    }

    require('libs/db_acessa.php');
    return "v2.{$db_fonte_codversao}.{$db_fonte_codrelease}";
  }

  public function marcarComoLido($aArquivosLidos) {

    if (empty($aArquivosLidos)) {
      return;
    }

    $sSqlDelete = "delete from db_releasenotes where db147_id_usuario = {$this->iUsuario} ";

    $sSqlInsert = "insert into db_releasenotes (db147_sequencial, db147_nomearquivo, db147_db_versao, db147_id_usuario) values ";

    $aArquivosInserir = array();
    $aVersoesDelete = array();
    $aSqlInsert = array();
    foreach ($aArquivosLidos as $oArquivoLido) {

      $aVersao = explode(".", $oArquivoLido->sVersao);
      $iCodVersao = $aVersao[1];
      $iCodRelease = $aVersao[2];

      $sSqlBuscaVersoes  = "select db30_codver from db_versao                                   \n";
      $sSqlBuscaVersoes .= " where db30_codversao  = " . $iCodVersao  . "\n";
      $sSqlBuscaVersoes .= "   and db30_codrelease = " . $iCodRelease . "\n";

      $rsBuscaVersoes = db_query($sSqlBuscaVersoes);

      if (!$rsBuscaVersoes || pg_num_rows($rsBuscaVersoes) == 0) {
        throw new DBException("Erro ao buscar as versões do sistema.");
      }

      $oVersao = db_utils::fieldsMemory($rsBuscaVersoes, 0);

      $aSqlInsert[] =  "(nextval('db_releasenotes_db147_sequencial_seq'), '{$oArquivoLido->sNomeArquivo}', {$oVersao->db30_codver}, {$this->iUsuario})";

      $aArquivosInserir[] = "'$oArquivoLido->sNomeArquivo'";
      $aVersoesDelete[]   = $oVersao->db30_codver;
    }

    if (empty($aSqlInsert)) {
      throw new DBException("Não foi possível marcar as mudanças como lido.");
    }

    $aVersoesDelete = array_unique($aVersoesDelete);

    if ( !empty($aArquivosInserir) && !empty($aVersoesDelete) ) {
      $sSqlDelete .= " and db147_nomearquivo in (" . implode(",", $aArquivosInserir) . ") \n";
      $sSqlDelete .= " and db147_db_versao   in (" . implode(",", $aVersoesDelete)   . ");\n";
    } else {
      $sSqlDelete = "";
    }


    $sSqlInsert .= implode(",", $aSqlInsert);

    $rsInsert = db_query($sSqlDelete . $sSqlInsert);

    if (!$rsInsert) {
      throw new DBException("Não foi possível marcar as mudanças como lido.");
    }

  }

  public function getArquivoAnterior() {

    $aNomeArquivo = explode("_", $this->sNomeArquivo);

    $sNomeArquivo = current($aNomeArquivo);

    $iIndexArquivo = 1;

    if ( count($aNomeArquivo) > 1 ) {
      $iIndexArquivo = intval( end($aNomeArquivo) );
      array_pop($aNomeArquivo);
      $sNomeArquivo = implode("_", $aNomeArquivo);
    }

    if ($iIndexArquivo == 1) {
      return "";
    }

    $iIndexArquivo--;

    return $sNomeArquivo . "_" . str_pad($iIndexArquivo, 2, STR_PAD_LEFT, "0");
  }

  public function getProximoArquivo() {

    $aNomeArquivo = explode("_", $this->sNomeArquivo);

    $sNomeArquivo = current($aNomeArquivo);

    $iIndexArquivo = 1;

    if ( count($aNomeArquivo) > 1 ) {

      $iIndexArquivo = intval( end($aNomeArquivo) );
      array_pop($aNomeArquivo);
      $sNomeArquivo = implode("_", $aNomeArquivo);
    }

    $iIndexArquivo++;

    $sNotaGeral = $sNomeArquivo . "_" . str_pad($iIndexArquivo, 2, STR_PAD_LEFT, "0");

    if ( file_exists(DBReleaseNote::DIRETORIO_RELEASE_NOTE . "/" . $this->getVersaoFormatada() . "/" . $sNotaGeral . ".md") ) {
      return $sNotaGeral;
    }

    return "";
  }
}