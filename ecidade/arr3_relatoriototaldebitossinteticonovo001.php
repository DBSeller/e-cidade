<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2022  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_sql.php"));
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_libtributario.php");

$oGet = db_utils::postMemory($_GET);
$clrotulo = new rotulocampo;
$clrotulo->label('j40_refant');
$clrotulo = new rotulocampo;
$clrotulo->label('q02_inscmu');
try {

  $oRelatorio = new RelatorioSinteticoArrecadar();
    
  if (!empty(str_replace("--", "", $oGet->dtini))) {
    $oRelatorio->setDataInicial(new DBDate($oGet->dtini));  
  }  

  if (!empty(str_replace("--", "",$oGet->dtfim))) {
    $oRelatorio->setDataFinal(new DBDate($oGet->dtfim));
  }

  if (!empty($oGet->exercini)) {
    $oRelatorio->setExercicioInicial($oGet->exercini);  
  }  

  if (!empty($oGet->exercfim)) {
    $oRelatorio->setExercicioFinal($oGet->exercfim);
  }
  
  if (!empty($oGet->tipos)) {
    $oRelatorio->setTiposDebito($oGet->tipos);
  }
  
  if (!empty($oGet->numcgm)) {
    $oRelatorio->setCgm($oGet->numcgm);
  }
  
  if (!empty($oGet->matric)) {
    $oRelatorio->setMatricula($oGet->matric);
  }

  if (!empty($oGet->inscr)) {
    $oRelatorio->setInscricao($oGet->inscr);
  }
  
  if (!empty($oGet->numpre)) {
    $oRelatorio->setNumpre($oGet->numpre);
  }
  $oRelatorio->emitir();

} catch (Exception $oException) {
  db_redireciona("db_erros.php?db_erro=" . urlencode($oException->getMessage()));
}