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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('classes/db_rhpessoal_classe.php');
require_once modification('classes/db_rhcedencia_classe.php');
require_once modification('dbforms/db_funcoes.php');


db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clrhcedencia = new cl_rhcedencia;
$clrhpessoal = new cl_rhpessoal;
$clrhcadregime = new cl_rhcadregime;

$db_opcao = 1;
$db_botao = true;

if (!isset($rh261_dtorigemadmissao)) {
    $rh261_dtorigemadmissao = "";
}
if (!isset($rh261_datamovimentacao)) {
    $rh261_datamovimentacao = "";
}
if (!isset($rh261_devolucao)) {
    $rh261_devolucao = "";
}
if (!isset($rh261_indicadoconselho)) {
    $rh261_indicadoconselho = false;
}

if (isset($novo) && $novo == 'true') {
    $rh261_credencial = "";
    $rh261_onus = "";
    $rh261_ressarcimento = "";
    $rh261_datamovimentacao = "";
    $rh261_devolucao = "";
    $rh261_numcgm = "";
    $rh261_matorigemcedente = "";
    $rh261_servidorcedido = "";
    $rh261_indicadoconselho = "";
    $rh261_codcategoriaorigem = "";
    $rh261_dtorigemadmissao = "";
    $rh261_tiporegimeorigem = "";
    $rh261_tiporegimeprev = "";
}

$rh261_devolucao_dia = isset($rh261_devolucao_dia) ? $rh261_devolucao_dia : "";
$rh261_devolucao_mes = isset($rh261_devolucao_mes) ? $rh261_devolucao_mes : "";
$rh261_devolucao_ano = isset($rh261_devolucao_ano) ? $rh261_devolucao_ano : "";
$rh261_datamovimentacao_dia = isset($rh261_datamovimentacao_dia) ? $rh261_datamovimentacao_dia : "";
$rh261_datamovimentacao_mes = isset($rh261_datamovimentacao_mes) ? $rh261_datamovimentacao_mes : "";
$rh261_datamovimentacao_ano = isset($rh261_datamovimentacao_ano) ? $rh261_datamovimentacao_ano : "";
$rh261_dtorigemadmissao_dia = isset($rh261_dtorigemadmissao_dia) ? $rh261_dtorigemadmissao_dia : "";
$rh261_dtorigemadmissao_mes = isset($rh261_dtorigemadmissao_mes) ? $rh261_dtorigemadmissao_mes : "";
$rh261_dtorigemadmissao_ano = isset($rh261_dtorigemadmissao_ano) ? $rh261_dtorigemadmissao_ano : "";
$rh261_numcgm = isset($rh261_numcgm) ? $rh261_numcgm : null;

if (!isset($rh261_sequencial)) {
    $rh261_sequencial = 0;
}
$camposRhcdencia ='rh261_sequencial,rh261_credencial,rh261_onus,rh261_ressarcimento,rh261_datamovimentacao,rh261_devolucao,rh261_numcgm,rh261_matorigemcedente,rh261_servidorcedido,rh261_indicadoconselho,rh261_codcategoriaorigem,rh261_dtorigemadmissao,rh261_tiporegimeorigem,rh261_tiporegimeprev';

try {   
    if (isset($incluir)) {
        db_inicio_transacao();

        $sqlerro = false;

        $clrhcedencia->rh261_credencial = $rh261_credencial;
        $clrhcedencia->rh261_onus = $rh261_onus;
        $clrhcedencia->rh261_ressarcimento = $rh261_ressarcimento;
        $clrhcedencia->rh261_datamovimentacao = $rh261_datamovimentacao;
        $clrhcedencia->rh261_devolucao = $rh261_devolucao;
        $clrhcedencia->rh261_numcgm = $rh261_numcgm;
        $clrhcedencia->rh261_matorigemcedente = $rh261_matorigemcedente;
        $clrhcedencia->rh261_servidorcedido = $rh261_servidorcedido;
        $clrhcedencia->rh261_regist = $rh261_regist;
        $clrhcedencia->rh261_indicadoconselho = $rh261_indicadoconselho == 'true' ||  $rh261_indicadoconselho == 't' ? 't' : 'f';
        $clrhcedencia->rh261_codcategoriaorigem = (int) $rh261_codcategoriaorigem;
        $clrhcedencia->rh261_dtorigemadmissao = $rh261_dtorigemadmissao;
        $clrhcedencia->rh261_tiporegimeorigem = (int) $rh261_tiporegimeorigem;
        $clrhcedencia->rh261_tiporegimeprev = (int )$rh261_tiporegimeprev;

        $clrhcedencia->incluir(null);
        if ($clrhcedencia->erro_status == "0") {
            $erro_msg = $clrhcedencia->erro_msg;
            $sqlerro = true;
        }
        db_fim_transacao($sqlerro);
    } else {
        if (isset($alterar)) {
            db_inicio_transacao();
            $sqlerro = false;
            
            $varTemp = 'false';
            if ($rh261_indicadoconselho) {
                $varTemp = 'true';
            }

            $clrhcedencia->rh261_credencial = $rh261_credencial;
            $clrhcedencia->rh261_onus = $rh261_onus;
            $clrhcedencia->rh261_ressarcimento = $rh261_ressarcimento;
            $clrhcedencia->rh261_datamovimentacao = $rh261_datamovimentacao;
            $clrhcedencia->rh261_devolucao = $rh261_devolucao;
            $clrhcedencia->rh261_numcgm = $rh261_numcgm;
            $clrhcedencia->rh261_matorigemcedente = $rh261_matorigemcedente;
            $clrhcedencia->rh261_servidorcedido = $rh261_servidorcedido;
            $clrhcedencia->rh261_regist = $rh261_regist;
            $clrhcedencia->rh261_indicadoconselho = $varTemp;
            $clrhcedencia->rh261_codcategoriaorigem = (int) $rh261_codcategoriaorigem;
            $clrhcedencia->rh261_dtorigemadmissao = $rh261_dtorigemadmissao;
            $clrhcedencia->rh261_tiporegimeorigem = (int)$rh261_tiporegimeorigem;
            $clrhcedencia->rh261_tiporegimeprev = (int)$rh261_tiporegimeprev;
            $clrhcedencia->alterar($rh261_sequencial);
            if ($clrhcedencia->erro_status == "0") {
                $erro_msg = $clrhcedencia->erro_msg;
                $sqlerro = true;
                $opcao = "alterar";
            }
            db_fim_transacao($sqlerro);
        } else {
            if (isset($excluir)) {
                db_inicio_transacao();
                $sqlerro = false;

                $clrhcedencia->excluir($rh261_sequencial);
                if ($clrhcedencia->erro_status == "0") {
                    $erro_msg = "Erro ao excluir as informações do dependente.";
                    $sqlerro = true;
                }
                db_fim_transacao($sqlerro);
            }
        }
    }
} catch (Exception $exception) {
    $erro_msg = $exception->getMessage();
    $sqlerro = true;
    db_fim_transacao($sqlerro);
}

if ((isset($alterar) || isset($excluir) || isset($incluir)) && $sqlerro == false) {
    unset($opcao);
}

if (isset($opcao)) {
    if ($opcao == "alterar") {
        $db_opcao = 2;
    } else {
        $db_opcao = 3;
    }
    $rh261_credencial = '';
    $rh261_onus = 'X';
    $rh261_ressarcimento = 'X';
    $rh261_datamovimentacao = '';
    $rh261_devolucao = '';
    $rh261_numcgm = 0;
    $rh261_matorigemcedente = 0;
    $rh261_servidorcedido = 'N';
    $rh261_indicadoconselho = 'f';

    $sqlrhcedencia= $clrhcedencia->sql_query_file(null,$camposRhcdencia,null,'rh261_sequencial='.$rh261_sequencial);
    $registros = $clrhcedencia->sql_record($sqlrhcedencia);
    if ($clrhcedencia->numrows > 0) {
        db_fieldsmemory($registros, 0);
    }
    

} else {
    $result_nome = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($rh261_regist, "z01_nome"));
    if ($clrhpessoal->numrows > 0) {
        db_fieldsmemory($result_nome, 0);
    }
    if (isset($rh261_regist) && trim($rh261_regist) != "" && !(isset($novo) && $novo == 'true')) {
        $sqlrhcedencia= $clrhcedencia->sql_query_file(null,$camposRhcdencia,'rh261_datamovimentacao desc, rh261_devolucao desc','rh261_regist='.$rh261_regist);
        $registros = $clrhcedencia->sql_record($sqlrhcedencia);
        if ($clrhcedencia->numrows > 0) {
            db_fieldsmemory($registros, 0);
        }
    }
}
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
    <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
            <center>
                <?php
                require_once modification('forms/db_frmrhcedencia.php');
                ?>
            </center>
        </td>
    </tr>
</table>
<?php
?>
</body>
</html>
<?php
if (isset($incluir) || isset($alterar) || isset($excluir)) {
    if ($sqlerro == true) {
        db_msgbox($erro_msg);
        echo "<script> document.form1." . $clrhdepend->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1." . $clrhdepend->erro_campo . ".focus();</script>";
    }
}
