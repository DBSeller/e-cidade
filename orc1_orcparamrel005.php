<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_orcparamrel_classe.php"));
require_once(modification("classes/db_orcparamrelperiodos_classe.php"));
$clorcparamrel = new cl_orcparamrel;
/*
$clorcparamrelperiodos = new cl_orcparamrelperiodos;
*/
db_postmemory($_POST);
$db_opcao = 22;
$db_botao = false;
if (isset($alterar)) {
    $sqlerro = false;
    db_inicio_transacao();
    $clorcparamrel->alterar($o42_codparrel);
    if ($clorcparamrel->erro_status == 0) {
        $sqlerro = true;
    }
    $erro_msg = $clorcparamrel->erro_msg;

    if ( !empty($templatePath) ) {

        $oDaoOrcparamreltemplate = new cl_orcparamreltemplate();
        $oDaoOrcparamreltemplate->excluir(null, "o163_orcparamrel = {$o42_codparrel}");

        if ($oDaoOrcparamreltemplate->erro_status == "0") {
            $sMsg  = "Não foi possível salvar template do relatório. Tente novamente mais tarde, ";
            $sMsg .= "se o problema persisrir, contate o suporte.";
            throw new Exception($sMsg);
        }

        /**
         * Geramos um Blob vazio e gravamos o arquivo no banco
         */
        $iOid          = DBLargeObject::criaOID( true );
        $lSalvaArquivo = DBLargeObject::escrita( $templatePath, $iOid );

        $oDaoOrcparamreltemplate->o163_template = $iOid;
        $oDaoOrcparamreltemplate->o163_orcparamrel = $clorcparamrel->o42_codparrel;
        $oDaoOrcparamreltemplate->incluir();

        if ($oDaoOrcparamreltemplate->erro_status == "0") {
            $sMsg  = "Não foi possível salvar template do relatório. Tente novamente mais tarde, ";
            $sMsg .= "se o problema persisrir, contate o suporte.";
            throw new Exception($sMsg);
        }
    }

    db_fim_transacao($sqlerro);
    $db_opcao = 2;
    $db_botao = true;
} elseif (isset($chavepesquisa) && $chavepesquisa !== 'undefined') {
    $db_opcao = 2;
    $db_botao = true;
    $result = $clorcparamrel->sql_record($clorcparamrel->sql_query($chavepesquisa));
    db_fieldsmemory($result, 0);
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
<body >
    <?php
    include(modification("forms/db_frmorcparamrel.php"));
    ?>
    </body>
    </html>
<?php
if (isset($alterar)) {
    if ($sqlerro == true) {
        db_msgbox($erro_msg);
        if ($clorcparamrel->erro_campo != "") {
            echo "<script> document.form1." . $clorcparamrel->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
            echo "<script> document.form1." . $clorcparamrel->erro_campo . ".focus();</script>";
        };
    } else {
        db_msgbox($erro_msg);
    }
}
if (isset($chavepesquisa) && $chavepesquisa !== 'undefined') {
    echo "
  <script>
      function js_db_libera(){
         parent.document.formaba.orcparamrelperiodos.disabled=false;
         (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_orcparamrelperiodos.location.href='orc1_orcparamrelperiodos001.php?o113_orcparamrel=" . @$o42_codparrel . "';
     ";
    if (isset($liberaaba)) {
        echo "  parent.mo_camada('orcparamrelperiodos');";
    }
    echo "}\n
    js_db_libera();
  </script>\n
 ";
}
if ($db_opcao == 22 || $db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
}
