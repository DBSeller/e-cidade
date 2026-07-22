<?php
/*
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_pcfornemov_classe.php"));
require_once(modification("classes/db_pcforne_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
$clpcfornemov = new cl_pcfornemov;
$clpcforne = new cl_pcforne;
$db_opcao = 22;
$db_botao = false;

$oGet = db_utils::postMemory($_GET);
if (isset($alterar) || isset($excluir) || isset($incluir)) {
  $sqlerro = false;
}
if (isset($incluir)) {
  if ($sqlerro == false) {
    db_inicio_transacao();
    $clpcfornemov->incluir($pc62_codmov);
    $erro_msg = $clpcfornemov->erro_msg;
    if ($clpcfornemov->erro_status == 0) {
      $sqlerro = true;
    }
    db_fim_transacao($sqlerro);
  }
} else if (isset($alterar)) {
  if ($sqlerro == false) {
    db_inicio_transacao();
    $clpcfornemov->alterar($pc62_codmov);
    $erro_msg = $clpcfornemov->erro_msg;
    if ($clpcfornemov->erro_status == 0) {
      $sqlerro = true;
    }
    $pc62_codmov = "";
    db_fim_transacao($sqlerro);
  }
} else if (isset($excluir)) {
  if ($sqlerro == false) {
    db_inicio_transacao();
    $clpcfornemov->excluir($pc62_codmov);
    $erro_msg = $clpcfornemov->erro_msg;
    if ($clpcfornemov->erro_status == 0) {
      $sqlerro = true;
    }
    db_fim_transacao($sqlerro);
  }
} else if (isset($opcao)) {
  $result = $clpcfornemov->sql_record($clpcfornemov->sql_query($pc62_codmov));

  if ($result != false && $clpcfornemov->numrows > 0) {
    db_fieldsmemory($result, 0);
  }
}

if (!isset($z01_nome)) {
  $z01_nome = pg_result(db_query("select z01_nome from cgm where z01_numcgm = {$pc62_numcgm}"), 0, 0);
}

?>

<?php
require_once Modification::getFile("forms/db_frmpcfornemov.php");
?>


<?php
if (isset($alterar) || isset($excluir) || isset($incluir)) {
  db_msgbox($erro_msg);
  if ($clpcfornemov->erro_campo != "") {
    echo "<script> document.form1." . $clpcfornemov->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1." . $clpcfornemov->erro_campo . ".focus();</script>";
  }
}
?>