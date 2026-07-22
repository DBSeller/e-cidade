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
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_db_almox_classe.php"));
include(modification("classes/db_db_almoxdepto_classe.php"));
include(modification("classes/db_db_depart_classe.php"));
include(modification("classes/db_matparam_classe.php"));
include(modification("dbforms/db_funcoes.php"));
$cldb_almox = new cl_db_almox;
$cldb_almoxdepto = new cl_db_almoxdepto;

$clmatparam = new cl_matparam;
$clrotulo = new rotulocampo;
$clrotulo->label('coddepto');
$clrotulo->label('descrdepto');
$clrotulo->label('m92_codalmox');
db_postmemory($HTTP_POST_VARS);

?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <?php
        db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js");
        db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js");
        db_app::load("estilos.css, grid.style.css");
        ?>
        <script>
            var codalmox = <?php echo isset($codalmox)? $codalmox : 0;?>;
            var sUrlRpc = "mat1_matdb_almoxdepto.RPC.php";
            var dtAtual = '<?php echo date("Y-m-d", db_getsession('DB_datausu'));?>';
            var instituicao = <?php echo db_getsession("DB_instit");?>;
            var dbopcao = <?php echo isset($db_opcao)? $db_opcao : 0;?>;
            
            function js_marca(obj){ 
                var OBJ = document.form1;
                for (i = 0; i < OBJ.length; i++) {
                    if (OBJ.elements[i].type == 'checkbox') {
                        OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
                    }
                }
                return false;
            }

            function alterarDepartamentos() {
                var oParam = new Object();
                oParam.exec = 'atualizar';
                oParam.codalmox = codalmox;
                oParam.departamentos = new Array();
                var elementos = document.querySelectorAll("[id]");
                for (var i = 0, len = elementos.length; i < len; i++) {
                    var check = elementos[i];
                    if (elementos[i].id.indexOf("CHECK_") > -1) {
                        if (elementos[i].checked == true) {
                            oParam.departamentos.push(elementos[i].getAttribute('data-check'));
                        }
                    }
                }
                js_divCarregando('Aguarde, salvando departamentos...', 'msgbox');
                var oAjax = new Ajax.Request(sUrlRpc,
                {
                    method:'post',
                    parameters:'json='+Object.toJSON(oParam),
                    onComplete: retornoAlteracao
                }); 
            }

            function buscaDepartamentos() {
                var oParam = new Object();
                if (codalmox == 0) {
                    return false;
                }
                oParam.exec = 'buscaDepartamentos';
                oParam.codalmox = codalmox;
                oParam.instituicao = instituicao;
                oParam.dtAtual = dtAtual;
                js_divCarregando('Aguarde, buscando departamentos...', 'msgbox');
                var oAjax = new Ajax.Request(sUrlRpc,
                {
                    method:'post',
                    parameters:'json='+Object.toJSON(oParam),
                    onComplete: carregaOpcoes
                });                 
            }
            
            function carregaOpcoes(oResponse) {
                js_removeObj('msgbox');
                var oRetorno = JSON.parse(oResponse.responseText);

                if (oRetorno.status == 0) {
                    alert(oRetorno.message);
                    return false;
                }
                // var quadroDepartamentos = document.getElementById('almoxdeptos');
                var quadroDepartamentos = document.getElementById('almoxdeptos');
                // let row = quadroDepartamentos.insertRow(index)
                oRetorno.departamentos.each(function(departamento){
                    var tr = document.createElement('tr');
                    var td = document.createElement('td');
                    var checkbox = document.createElement('input');

                    td.className = 'corpo';
                    td.title = 'Inverte a marcação';
                    td.align = 'center';
                    checkbox.type = 'checkbox';
                    checkbox.name = 'CHECK_' + departamento.coddepto;
                    checkbox.id = 'CHECK_' + departamento.coddepto;
                    checkbox.setAttribute('data-check', departamento.coddepto);

                    if (dbopcao == 33) {
                        checkbox.setAttribute('read', 'disabled');
                    }
                    if (departamento.selecionado == 1) {
                        checkbox.checked = true;
                    }
                    td.append(checkbox);
                    tr.append(td);
                    
                    var td2 = document.createElement('td');
                    var label = document.createElement('label');
                    var codigo = document.createElement('small');
                    td2.className = 'corpo';
                    td2.title = 'código do departamento';
                    td2.align = 'center';
                    label.style.cursor = 'hand';
                    label.setAttribute('for', 'CHECK_"' + departamento.coddepto);
                    codigo.textContent = departamento.coddepto;

                    label.append(codigo);
                    td2.append(label);
                    tr.append(td2);

                    var td3 = document.createElement('td');
                    var label2 = document.createElement('label');
                    var descricao = document.createElement('small');
                    td3.className = 'corpo';
                    td3.title = 'código do departamento';
                    td3.align = 'center';
                    label2.style.cursor = 'hand';
                    label2.setAttribute('for', 'CHECK_"' + departamento.coddepto);
                    descricao.textContent = departamento.descrdepto;

                    label2.append(descricao);
                    td3.append(label2);
                    tr.append(td3);

                    quadroDepartamentos.append(tr);

                });
            }

            function retornoAlteracao(oResponse) {
                js_removeObj('msgbox');
                var oRetorno = JSON.parse(oResponse.responseText);
                if (oRetorno.status == 1) {
                    alert(oRetorno.message);
                } else {
                    alert(oRetorno.message);
                }
            }
        </script>  
        <style>
            .cabec {
                text-align: center;
                color: darkblue;
                background-color:#aacccc;       
                border-color: darkblue;
            }
            .corpo {
                text-align: center;
                color: black;
                background-color:#ccddcc;       
            }
        </style>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="buscaDepartamentos()" bgcolor="#cccccc">
        <center>
            <form name="form1" method='post'>
                <table border='0'>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr> 
                        <td align="left" nowrap title="<?=$Tm92_codalmox?>"><?php db_ancora(@$Lm92_codalmox,"js_pesquisam92_codalmox(true);",3);?></td>
                        <td align="left" nowrap>
                            <?php 
                            $result_descr=$cldb_almox->sql_record($cldb_almox->sql_query($codalmox,"m91_depto as depto_almox,descrdepto"));
                            if ($cldb_almox->numrows > 0) {
                                db_fieldsmemory($result_descr, 0);
                            }
                            db_input("codalmox", 6, $Im92_codalmox, true, "hidden", 3, "");
                            db_input("depto_almox", 6, "", true, "hidden", 3, "");
                            $m92_codalmox =@ $codalmox;
                            db_input("m92_codalmox", 6, $Im92_codalmox, true, "text", 3, "");
                            db_input("descrdepto", 40, $Idescrdepto, true, "text", 3);  
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <br>
                            <?php
                            //desativa botao atualizar caso seja exclusão
                            if (isset($db_opcao) == 33) {
                                $read = "disabled";
                            } else {
                                $read = null;
                            }
                            // echo "<input $read name=\"atualizar\" type=\"submit\" value=\"Atualizar\">";
                            ?>
                            <input onclick="alterarDepartamentos()" name="atualizar" type="button" value="Atualizar">
                            <br>
                        </td>
                    </tr>
                    <tr>                    
                        <table id="almoxdeptos">
                            <tr>
                                <td class='cabec' title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
                                <td class='cabec' align='center'  title='coddepto'><?php echo str_replace(":", "", $Lcoddepto);?></td>
                                <td class='cabec' align='center'  title='descrdepto'><?php echo str_replace(":", "", $Ldescrdepto);?></td>
                            </tr>
                        </table>        
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </form>
        </center>
    </body>
</html>