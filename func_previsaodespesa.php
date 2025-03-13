<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_previsaodespesa_classe.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$dao = new cl_previsaodespesa;
$campos = "
c333_sequencial                    
,c333_ano                           
,c333_esferaorcamentaria            
,c333_orcorgao as db_orcorgao
,o40_descr as dl_Órgão               
,c333_orcunidade as db_orcunidade 
,o41_descr as dl_Unidade                 
,c333_orcfuncao as db_orcfuncao
,o52_descr as dl_Função                        
,c333_orcsubfuncao as db_orcsubfuncao
,o53_descr as dl_Subfunção              
,c333_orcprograma as db_orcprograma
,o54_descr as dl_Programa                   
,c333_orcprojativ as db_orcprojativ
,o55_descr as dl_Ação                   
,c333_ppasubtitulolocalizadorgasto as db_ppasubtitulolocalizadorgasto
,o11_descricao as dl_Subtítulo  
,c333_conplanoorcamento as db_conplanoorcamento
,c60_descr as dl_Natureza_da_Despesa                                  
,c333_previsao                      
";
?>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
    <link href='estilos.css' rel='stylesheet' type='text/css'>
    <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>

<?php
if (!isset($pesquisa_chave)) {
    $sql = $dao->sql_previsao_despesa("", $campos, "c333_sequencial", "");
    $repassa = array();
    if (isset($chave_c333_sequencial)) {
        $repassa = array(
            "chave_c333_sequencial" => $chave_c333_sequencial,
            "chave_c333_sequencial" => $chave_c333_sequencial
        );
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
    db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
    echo '  </fieldset>';
    echo '</div>';
}
?>

<form name="form2" method="post" action="" class="container">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_previsaodespesa.hide();">
</form>
</body>
</html>
