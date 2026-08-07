<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

use ECidade\Financeiro\Orcamento\Service\AcompanhamentoDesembolsoDespesaService;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
$clorcdotacao = new cl_orcdotacao;
$clorcdotacaocontr = new cl_orcdotacaocontr;
$clorcelemento = new cl_orcelemento;
$clorcorgao = new cl_orcorgao;
$clorcunidade = new cl_orcunidade;
$clorcfuncao = new cl_orcfuncao;
$clorcsubfuncao = new cl_orcsubfuncao;
$clorcprograma = new cl_orcprograma;
$clorcprojativ = new cl_orcprojativ;
$clorcparametro = new cl_orcparametro;
$clorctiporec = new cl_orctiporec;
$db_opcao = 22;
$db_botao = false;
if ((isset($_POST["db_opcao"]) && $_POST["db_opcao"]) == "Alterar") {
    try {
        db_inicio_transacao();
        $erro_trans = false;
        $result = $clorcparametro->sql_record($clorcparametro->sql_query_file(db_getsession('DB_anousu'), 'o50_subelem'));
        db_fieldsmemory($result, 0);
        if ($o50_subelem == 'f') {
            $o56_elemento = substr($o56_elemento, 0, 7) . "000000";
            $result = $clorcelemento->sql_record($clorcelemento->sql_query_file(null, null, 'o56_codele', 'o56_elemento', " o56_anousu = " . db_getsession("DB_anousu") . " and o56_elemento = '$o56_elemento' "));
        } else {
            $result = $clorcelemento->sql_record($clorcelemento->sql_query_file(null, null, 'o56_codele', '', " o56_anousu = " . db_getsession("DB_anousu") . " and o56_elemento = '$o56_elemento' "));
        }

        if ($result == false || $clorcelemento->numrows == 0) {
            throw new Exception("Elemento não Cadastrado.");
        }

        db_fieldsmemory($result, 0);
        $clorcdotacao->o58_codele = $o56_codele;
        $clorcdotacao->o58_esferaorcamentaria = $esferaOrcamentaria;
        $clorcdotacao->o58_localizadorgastos = $o58_localizadorgastos;
        $clorcdotacao->o58_programa = $o58_programa;

        $clorcdotacao->alterar($o58_anousu, $o58_coddot);
        if ($clorcdotacao->erro_status == 0) {
            throw new Exception($clorcdotacao->erro_msg);
        }
        $o61_codigo = '';
        $rsContraPartida = $clorcdotacaocontr->sql_record($clorcdotacaocontr->sql_query_file($o58_anousu, $o58_coddot));

        if ($clorcdotacaocontr->numrows > 0) {
            db_fieldsmemory($rsContraPartida, 0);
            $clorcdotacaocontr->o61_anousu = $o58_anousu;
            $clorcdotacaocontr->o61_coddot = $o58_coddot;
            $clorcdotacaocontr->o61_codigo = $o61_codigo;
            $clorcdotacaocontr->excluir(null, "o61_coddot={$o61_coddot} and o61_anousu={$o61_anousu}");

            if ($clorcdotacaocontr->erro_status == 0) {
                throw new Exception($clorcdotacaocontr->erro_msg);
            }
        }

        if ($o61_codigo !== '') {
            $clorcdotacaocontr->o61_anousu = $o58_anousu;
            $clorcdotacaocontr->o61_coddot = $o58_coddot;
            $clorcdotacaocontr->o61_codigo = $o61_codigo;
            $clorcdotacaocontr->incluir(null);
            if ($clorcdotacaocontr->erro_status == 0) {
                $erro_trans = true;
                $clorcdotacao->erro_msg = $clorcdotacaocontr->erro_msg;
            }
        }
        $dotacao = new \ECidade\Financeiro\Orcamento\Model\Dotacao();
        $dotacao->setCodigoDotacao($clorcdotacao->o58_coddot)
            ->setAno($clorcdotacao->o58_anousu)
            ->setValor($clorcdotacao->o58_valor);

        $acompanhamento = new AcompanhamentoDesembolsoDespesaService();
        $acompanhamento->criar($dotacao);

        db_fim_transacao();
    } catch (Exception $exception) {
        $erro_trans = true;
        $clorcdotacao->erro_msg = $exception->getMessage();
        $clorcdotacao->erro_status = 0;
        db_fim_transacao(true);
    }

    db_fim_transacao($erro_trans);
    $db_opcao = 2;
} else if (isset($chavepesquisa)) {
    $db_opcao = 2;
    if (!isset($o58_coddot)) {
        $result = $clorcdotacaocontr->sql_record($clorcdotacaocontr->sql_query_file($chavepesquisa, $chavepesquisa1, 'o61_codigo'));
        $result = $clorcdotacao->sql_record($clorcdotacao->sql_query($chavepesquisa, $chavepesquisa1));
        db_fieldsmemory($result, 0);
    }
    $db_botao = true;
}

?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <?php
    include(modification("forms/db_frmorcdotacao001.php"));
    ?>
</div>
</html>
<?php
if ((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Incluir") {
    if ($clorcdotacao->erro_status == "0") {
        $clorcdotacao->erro(true, false);
        $db_botao = true;
        echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
        if ($clorcdotacao->erro_campo != "") {
            echo "<script> document.form1." . $clorcdotacao->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clorcdotacao->erro_campo . ".focus();</script>";
        };
    } else {
        $clorcdotacao->erro(true, true);
    };
}
if (isset($chavepesquisa) && @$o15_tipo == 1) {
    echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.orcdotacaocontr.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_orcdotacaocontr.location.href='orc1_orcdotacaocontr001.php?o61_anousu=$o58_anousu&o61_coddot=$o58_coddot';
     ";
    if (isset($liberaaba)) {
        echo "  parent.mo_camada('orcdotacaocontr');";
    }
    echo "}\n
    js_db_libera();
  </script>\n
 ";
}
if ($db_opcao == 22) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
?>
