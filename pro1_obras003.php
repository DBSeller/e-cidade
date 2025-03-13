<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

if (!isset($abas)) {
    echo "<script>location.href='pro1_obras005.php?db_opcao=3'</script>";
    exit;
}

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);

$clobras = new cl_obras;
$clobrasresp = new cl_obrasresp;
$clobrastec = new cl_obrastec;
$clobrastecnicos = new cl_obrastecnicos;
$clobraspropri = new cl_obraspropri;
$clobrastiporesp = new cl_obrastiporesp;
$clobraslote = new cl_obraslote;
$clobraslotei = new cl_obraslotei;
$clobrasender = new cl_obrasender;
$clobrasalvara = new cl_obrasalvara;
$clobrashabite = new cl_obrashabite;
$clobrasconstr = new cl_obrasconstr;
$clobrasprotprocesso = new cl_obrasprotprocesso;
$clobrasiptubase = new cl_obrasiptubase;

$db_botao = false;
$db_opcao = 33;

if (isset($chavepesquisa)) {
    $db_opcao = 3;
    $result = $clobras->sql_record($clobras->sql_query($chavepesquisa));

    db_fieldsmemory($result, 0);

    $result = $clobraspropri->sql_record($clobraspropri->sql_query($chavepesquisa));
    if ($clobraspropri->numrows > 0) {
        db_fieldsmemory($result, 0);
    }

    $result = $clobraslote->sql_record($clobraslote->sql_query($chavepesquisa));
    if ($clobraslote->numrows > 0) {
        db_fieldsmemory($result, 0);
    }

    $result = $clobraslotei->sql_record($clobraslotei->sql_query($chavepesquisa));
    if ($clobraslotei->numrows > 0) {
        db_fieldsmemory($result, 0);
    }

    $result = $clobrasresp->sql_record(
      $clobrasresp->sql_query($chavepesquisa, "ob10_numcgm, z01_nome as z01_nomeresp")
    );

    if ($clobrasresp->numrows > 0) {
        db_fieldsmemory($result, 0);
    }

    $db_botao = true;

    $result = $clobrastecnicos->sql_record(
      $clobrastecnicos->sql_query("", "z01_nome as z01_nometec, ob15_crea", "", " ob20_codobra = $chavepesquisa ")
    );

    if ($clobrastecnicos->numrows > 0) {
        db_fieldsmemory($result, 0);
    }

    if ($ob01_regular) {
        $rsObrasiptubase = $clobrasiptubase->sql_record($clobrasiptubase->sql_query(null,
          "j01_matric, z01_nome as z01_nome_matricula",
          null,
          "ob24_obras = {$chavepesquisa}"));
        if ($clobrasiptubase->numrows > 0) {
            db_fieldsmemory($rsObrasiptubase, 0);
        }

    }

    $rsObrasProtProcesso = $clobrasprotprocesso->sql_record(
      $clobrasprotprocesso->sql_query("", "*", "", " ob25_obras = $chavepesquisa ")
    );

    $ob01_processosistema = 'N';

    if ($clobrasprotprocesso->numrows > 0) {
        $oObraProcesso = db_utils::fieldsMemory($rsObrasProtProcesso, 0);
        $ob01_processosistema = 'S';
    }

    $campos = array(
      'ob01_responsavelprojeto',
      'tecnico_responsavel.ob15_crea as crea_responsavel',
      'cgm_responsavel.z01_nome as nome_responsavel',
      'ob01_arquitetoobra',
      'tecnico_arquiteto.ob15_crea as crea_arquiteto',
      'cgm_arquiteto.z01_nome as nome_arquiteto'
    );
    $sqlResponsavelArquiteto = $clobras->sqlResponsavelArquiteto($campos, array("ob01_codobra = {$chavepesquisa}"));
    $rsResponsavelArquiteto = db_query($sqlResponsavelArquiteto);

    if ($rsResponsavelArquiteto && pg_num_rows($rsResponsavelArquiteto) > 0) {
        db_fieldsmemory($rsResponsavelArquiteto, 0);
    }
}

?>
  <html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC>
  <?php
  include(modification("forms/db_frmobras.php"));
  ?>
  </body>
  </html>
<?php
if (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] == "Excluir") {
    if ($clobras->erro_status == "0") {
        $clobras->erro(true, false);
    } else {
        $clobras->erro(true, false);

        echo "
         <script>
         function js_src(){
           parent.iframe_obras.location.href='pro1_obras003.php?abas=1';\n
         }
         js_src();
         </script>
       ";
    };
};

if ($db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
