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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);

$cliptubase = new cl_iptubase;
$clobraslote = new cl_obraslote;
$clparprojetos = new cl_parprojetos;
$clobraspropri = new cl_obraspropri;
$clobrashabite = new cl_obrashabite;
$clobrasalvara = new cl_obrasalvara;
$clobrasconstr = new cl_obrasconstr;
$clobrashabiteprot = new cl_obrashabiteprot;
$clobrashabiteprotoff = new cl_obrashabiteprotoff;

$db_opcao = 1;
$db_botao = true;
$lLimpa = false;
$codigo = null;

if (isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] == "Incluir") {
    $rsParProjetos = $clparprojetos->sql_record($clparprojetos->sql_query_file(db_getsession('DB_anousu')));

    if ($clparprojetos->numrows > 0) {
        $sqlerro = false;

        $result_obrasconstr = $clobrasconstr->sql_record($clobrasconstr->sql_query($ob09_codconstr, 'obrasconstr.ob08_codobra', null));
        db_fieldsmemory($result_obrasconstr,0);
        
        if(is_null($ob08_codobra)){
            $sMsg = _M('tributario.projetos.db_frmobrashabite.obra_nao_encontrada');
            db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");
        }

        $result_obrasalvara = $clobrasalvara->sql_record($clobrasalvara->sql_query($ob08_codobra, "obrasalvara.ob04_ativo"));
        db_fieldsmemory($result_obrasalvara,0);

        if($ob04_ativo == 'f'){
            $sMsg = _M('tributario.projetos.db_frmobrashabite.alvara_cancelado');
            db_redireciona("db_erros.php?fechar=true&db_erro={$sMsg}");     
        }

        db_inicio_transacao();

        if (isset($_POST["ob09_area"]) || $_POST["ob09_area"] == "") {
            $rsObra = db_query($clobrasconstr->sql_query_file($ob09_codconstr, 'obrasconstr.ob08_area'));
            $areaObra = db_utils::fieldsMemory($rsObra, 0);
            if ((float)$areaObra->ob08_area > (float)($_POST["ob09_area"])) {
                $clobrashabite->ob09_parcial = true;
            } else {
                $clobrashabite->ob09_parcial = false;
            }
        }

        $clobrashabite->incluir($ob09_codhab);
        $codigo = $clobrashabite->ob09_codhab;

        if ($clobrashabite->erro_status == 0) {
            $erro = $clobrashabite->erro_msg;
            db_msgbox($erro);
            $sqlerro = true;
        }

        $oParProjetos = db_utils::fieldsMemory($rsParProjetos, 0);

        if ($oParProjetos->ob21_numeracaohabite == 2) {
            $clparprojetos->ob21_ultnumerohabite = $oParProjetos->ob21_ultnumerohabite + 1;
            $clparprojetos->ob21_anousu = db_getsession('DB_anousu');
            $clparprojetos->alterar(db_getsession('DB_anousu'));
            if ($clparprojetos->erro_status == 0) {
                $erro = $clparprojetos->erro_msg;
                db_msgbox($erro);
                $sqlerro = true;
            }
        }

        if ($iValSis == 1) {
            if (isset($ob19_codproc) && $ob19_codproc != "") {
                $clobrashabiteprot->ob19_codproc = $ob19_codproc;
                $clobrashabiteprot->ob19_codhab = $clobrashabite->ob09_codhab;
                $clobrashabiteprot->incluir();

                if ($clobrashabiteprot->erro_status == 0) {
                    $erro = $clobrashabiteprot->erro_msg;
                    db_msgbox($erro);
                    $sqlerro = true;
                }
            }
        } else {
            $clobrashabiteprotoff->ob22_codhab = $clobrashabite->ob09_codhab;
            $clobrashabiteprotoff->ob22_codproc = $ob22_codproc;
            $clobrashabiteprotoff->ob22_titular = $ob22_titular;
            $clobrashabiteprotoff->ob22_data = $ob22_data_ano . "-" . $ob22_data_mes . "-" . $ob22_data_dia;
            $clobrashabiteprotoff->incluir(null);

            if ($clobrashabiteprotoff->erro_status == 0) {
                $erro = $clobrashabiteprotoff->erro_msg;
                db_msgbox($erro);
                $sqlerro = true;
            }
        }

        db_fim_transacao($sqlerro);
    } else {
        $oParms = new stdClass();
        $oParms->iAnoUsu = db_getsession('DB_anousu');
        db_msgbox(_M('tributario.projetos.db_frmobrashabite.paramentro_nao_configurado', $oParms));
        $sqlerro = true;
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
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <?php
  include(modification("forms/db_frmobrashabite.php"));
  db_menu();
  ?>
  </body>
  </html>
<?php
if (isset($_POST["db_opcao"]) && $_POST["db_opcao"] == "Incluir") {
    if ($clobrashabite->erro_status == "0") {
        $clobrashabite->erro(true, false);
        $db_botao = true;

        echo " <script>";
        echo "  document.form1.db_opcao.disabled=false;";

        if ($iValSis == 2) {
            echo "  document.getElementById('procManual').style.display  = '';		  ";
            echo "  document.getElementById('procSistema').style.display = 'none'; ";
        } else {
            echo "  document.getElementById('procManual').style.display  = 'none'; ";
            echo "  document.getElementById('procSistema').style.display = '';		  ";
        }

        echo " </script>";

        if ($clobrashabite->erro_campo != "") {
            echo "<script> document.form1." . $clobrashabite->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clobrashabite->erro_campo . ".focus();</script>";
        };
    } else {
        $daoParProjetos = new cl_parprojetos();
        $sqlParametros = $daoParProjetos->sql_query_pesquisaParametros(db_getsession('DB_anousu'));
        $rsParametros = $daoParProjetos->sql_record($sqlParametros);
        $tipoRelatorio = 0;

        if ($daoParProjetos->erro_status != "0") {
            $tipoRelatorio = db_utils::fieldsMemory($rsParametros, 0)->ob21_tipocartahabite;
        }

        echo " <script>";

        echo " if (confirm('Inclusão efetuada com sucesso. Deseja imprimir a Carta de Habite-se?')) { ";
        echo "   let tipoRelatorio = " . $tipoRelatorio . ";";
        echo "   let url = tipoRelatorio === 0 ? 'pro2_cartahabite002.php' : 'pro2_cartahabite003.php'; ";
        echo "   let emissaoRelatorio = new EmissaoRelatorio(url, {'codigo': '" . $codigo . "'}); ";
        echo "   emissaoRelatorio.open(); ";
        echo " } ";
        echo " location.href = 'pro1_obrashabite001.php'; ";

        echo " </script>";
    }
}
