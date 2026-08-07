<?php
/**
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
require_once modification('dbforms/db_funcoes.php');

parse_str($HTTP_SERVER_VARS['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clrotulo = new rotulocampo;
$clrotulo->label('h12_codigo');
$clrotulo->label('h12_descr');
?>
<html>

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script>
    function js_pesquisa() {
        F = document.form1;
        js_OpenJanelaIframe('CurrentWindow.corpo', 'func_mostra', 'pes3_consvantagemconf002.php?codigo_assent=' + F
            .h12_codigo.value + '&descr=' + F.h12_descr.value, 'CONFIGURACAO DE VANTAGENS', true, '20');

    }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="">
    <form name="form1" method="post">
        <table align="center" border="0" cellspacing="4" cellpadding="0">
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td nowrap title="<?=@$Th12_codigo?>">
                    <?
      db_ancora(@$Lh12_codigo,"js_pesquisah12_codigo(true);",1);
      ?>
                </td>

                <td nowrap>
                    <?
      db_input('h12_codigo',6,$h12_codigo,true,'text',3,"onchange='js_pesquisah12_codigo(false);'")
      ?>
                    <?
      db_input('h12_descr',40,$Ih12_descr,true,'text',3,'')
      ?>
                </td>

            </tr>
            <tr>
                <td align="center" colspan="2">
                    <input onClick="js_pesquisa()" type="button" value="Pesquisar" name="pesquisar">
                </td>
            </tr>
        </table>
    </form>
    <? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>

</html>
<script>
function js_pesquisah12_codigo(mostra) {
    if (mostra == true) {
        js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tipoasse',
            'func_tipoasse.php?funcao_js=parent.js_mostratipoasse1|h12_codigo|h12_descr&instit=<?=(db_getsession("DB_instit"))?>',
            'Pesquisa', true);
    } else {
        if (document.form1.h12_codigo.value != '') {
            js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_tipoasse', 'func_tipoasse.php?pesquisa_chave=' +
                document.form1.h12_codigo.value +
                '&funcao_js=parent.js_mostratipoasse&instit=<?=(db_getsession("DB_instit"))?>', 'Pesquisa', false);
        } else {
            document.form1.h12_descr.value = '';
        }
    }
}

function js_mostrtipoasse(chave, erro) {
    document.form1.h12_descr.value = chave;
    if (erro == true) {
        document.form1.h12_codigo.focus();
        document.form1.h12_descr.value = '';
    }
}

function js_mostratipoasse1(chave1, chave2) {
    document.form1.h12_codigo.value = chave1;
    document.form1.h12_descr.value = chave2;
    db_iframe_tipoasse.hide();
}
</script>