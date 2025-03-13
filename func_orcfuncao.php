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
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_orcfuncao_classe.php');

db_postmemory($_POST);
parse_str($_SERVER['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

$clorcfuncao = new cl_orcfuncao();
$clorcfuncao->rotulo->label('o52_funcao');
$clorcfuncao->rotulo->label('o52_descr');

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="estilos.css">
    <script src="scripts/scripts.js"></script>
</head>
<body>
<form name="form2" method="post" class="container">
    <fieldset>
        <legend>Dados para Pesquisa</legend>
        <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
            <tr>
                <td><label for="chave_o52_funcao"><?=$Lo52_funcao?></label></td>
                <td><?php db_input("o52_funcao",2, $Io52_funcao, true, "text", 4, "", "chave_o52_funcao"); ?></td>
            </tr>
            <tr>
                <td><label for="chave_o52_descr"><?=$Lo52_descr?></label></td>
                <td><?php db_input("o52_descr",40, $Io52_descr, true, "text", 4, "", "chave_o52_descr"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onclick="parent.db_iframe_orcfuncao.hide();">
</form>
<?php
if (isset($pesquisa_chave) === false) {
    if (isset($campos) === false) {
        if (file_exists("funcoes/db_func_orcfuncao.php") === true) {
            include(modification("funcoes/db_func_orcfuncao.php"));
        } else {
            $campos = "orcfuncao.*";
        }
    }
        if(isset($chave_o52_funcao) && (trim($chave_o52_funcao)!="") ){
	         $sql = $clorcfuncao->sql_query($chave_o52_funcao,$campos,"o52_funcao");
        }else if(isset($chave_o52_descr) && (trim($chave_o52_descr)!="") ){
	         $sql = $clorcfuncao->sql_query("",$campos,"o52_descr"," o52_descr like '$chave_o52_descr%' ");
        }else{
           $sql = $clorcfuncao->sql_query("",$campos,"o52_funcao","");
        }
        $repassa = array();
        if(isset($chave_o52_descr)){
          $repassa = array("chave_o52_funcao"=>$chave_o52_funcao,"chave_o52_descr"=>$chave_o52_descr);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $result = $clorcfuncao->sql_record($clorcfuncao->sql_query($pesquisa_chave));
          if($clorcfuncao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o52_descr',false);</script>";
        } else {
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
    } else {
        echo "<script>{$funcao_js}('', false);</script>";
    }
}
?>
</body>
</html>
<?php if (isset($pesquisa_chave) === false) { ?>
    <script rel="script" type="text/javascript">
    </script>
<?php } ?>
<script rel="script" type="text/javascript">
    js_tabulacaoforms("form2","chave_o52_descr",true,1,"chave_o52_descr",true);
</script>
