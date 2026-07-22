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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory($_POST);
$daoContribuicao = new cl_contribuicaosindicalperiodo;

$instituicao = db_getsession('DB_instit');
$where = array(
    "r70_ativo IS TRUE",
    "r70_instit = {$instituicao}"
);

$campos = array(
    "z01_numcgm AS cgm",
    "z01_nome AS nome",
);

$dao = new cl_rhlota();
$sql = $dao->sql_query_lota_cgm(
    null,
    'DISTINCT ' . implode(', ', $campos),
    'z01_numcgm',
    implode(' AND ', $where)
);

$rsEmpregador = db_query($sql);
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<form name="form1" method="post" class="container">
    <fieldset>
        <legend>Dados para Pesquisa</legend>
        <table class="form-container">
            <tr id="tr_empregador">
                <td>
                    <label for="empregador">Empregador:</label>
                </td>
                <td>
                    <?php db_selectrecord('z01_numcgm', $rsEmpregador, false, 1, '', 'empregador', '', '', '', 1); ?>
                </td>
            </tr>
            <tr>
                <td><label>Indicativo de Período:</label></td>
                <td>
                    <select name="indicativoPeriodo" id="indicativoPeriodo">
                        <option value="1">Mensal</option>
                        <option value="2">Anual</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Período:</label></td>
                <td>
                    <input type="text" name="periodo" id="periodo" maxlength="7" class="field-size2">
                </td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar">
    <input name="Fechar" type="button" id="fechar" value="Fechar"
           onclick="parent.db_iframe_contribuicaosindicalperiodo.hide();">
    <div style=" margin-top: 4px;
            background-color: #FFF;
            border-radius: 4px;
            font-weight: bold;
            padding: 1px 10px;
            text-align: center;">
        <p>Clique em pesquisar para buscar os dados conforme os filtros.</p>
    </div>
</form>
<?php
$campos = "
    eso30_sequencial as db_id,
    z01_nome as dl_empregador,        
    case 
        when eso30_indicativo_periodo = 1 
        then 'Mensal'
        else 'Anual'
    end::varchar as dl_indicativo, 
    eso30_periodo
";

if (!empty($empregador)) {
    $where = array(
        "eso30_empregador = {$empregador}",
        "eso30_indicativo_periodo = {$indicativoPeriodo}",
    );

    if (!empty($periodo)) {
        $where[] = "eso30_periodo = '{$periodo}'";
    }

    $sql = $daoContribuicao->sql_query(
        "",
        $campos,
        "eso30_indicativo_periodo, eso30_periodo",
        implode(' and ', $where)
    );

    $repassa = array(
        "empregador" => $empregador,
        "indicativoPeriodo" => $indicativoPeriodo,
    );

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
}

?>
</body>
</html>
<script type="text/javascript">
    (function () {
        var query = frameElement.getAttribute('name').replace('IF', ''),
            input = document.querySelector('input[value="Fechar"]');
        input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
    })();
</script>
