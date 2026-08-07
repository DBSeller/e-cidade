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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rhpessoal_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$daoAvaliacaoGrupoRespostaTSVE = new cl_avaliacaogruporespostatertrabasemvinc;
$rotulo = new rotulocampo();
$rotulo->label('rh01_regist');
$rotulo->label('rh01_numcgm');
$rotulo->label('z01_nome');
?>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
    <link href='estilos.css' rel='stylesheet' type='text/css'>
    <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
    <script src="scripts/prototype.js" type="text/javascript"></script>
</head>
<body>
<form name="form2" method="post" action="" class="container">
    <fieldset>
        <legend>Pesquisa de Servidores Sem Vínculo Desligados</legend>

        <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
            <tr>
                <td>
                    <label for="chave_rh01_regist"><?=$Lrh01_regist?></label>
                </td>
                <td>
                    <?php db_input("rh01_regist", 10, $Irh01_regist, true, "text", 4, "", "chave_rh01_regist"); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="chave_rh01_numcgm"><?=$Lrh01_numcgm?></label>
                </td>
                <td>
                    <?php db_input("rh01_numcgm", 10, 1, true, "text", 4, "", "chave_rh01_numcgm"); ?>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="chave_z01_nome"><?=$Lz01_nome?></label>
                </td>
                <td>
                    <?php db_input("z01_nome", 60, $Iz01_nome, true, "text", 1, "", "chave_z01_nome"); ?>
                </td>
            </tr>
        </table>
    </fieldset>

    <input type="submit" name="pesquisar" id="pesquisar" value="Pesquisar">
    <input type="button" name="limpar" id="limpar" value="Limpar" onClick="limpaCampos()">
    <input type="button" name="Fechar" id="fechar" value="Fechar">
</form>
</body>
</html>
<?php
    
    $codigoInsituicao = InstituicaoRepository::getInstituicaoSessao()->getCodigo();
    $whereFiltrosPesquisa = array();
    $repassa = array();

    $whereFiltrosPesquisa[] = "rh01_instit = {$codigoInsituicao}";

    if(isset($chave_rh01_regist) && !empty($chave_rh01_regist)) {
        $whereFiltrosPesquisa[] = "rh01_regist = $chave_rh01_regist";
    }

    if(isset($chave_rh01_numcgm) && !empty($chave_rh01_numcgm)) {
        $whereFiltrosPesquisa[] = "rh01_numcgm = $chave_rh01_numcgm";
    }

    if(isset($chave_z01_nome) && !empty($chave_z01_nome)) {
        $whereFiltrosPesquisa[] = "z01_nome ilike '$chave_z01_nome%'";
    }

    if(isset($campos) == false) {
        $campos = array("rhpessoal.rh01_regist, cgm.z01_numcgm, cgm.z01_nome");
    }

    $ordem = array("cgm.z01_nome");
    $sql = $daoAvaliacaoGrupoRespostaTSVE->buscaServidorCargaDesligamento($campos, $ordem, $whereFiltrosPesquisa);
    
    if(isset($chave_rh01_regist)) {
        $repassa = array("chave_rh01_regist"=>$chave_rh01_regist,"chave_z01_nome=>$chave_z01_nome");
    }

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    echo '  </fieldset>';
    echo '</div>';
?>
<script type="text/javascript">

    (function() {
        var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
        input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
    })();

    function limpaCampos() {
        $('chave_rh01_regist').value = '';
        $('chave_rh01_numcgm').value = '';
        $('chave_z01_nome').value = '';
    }
</script>
