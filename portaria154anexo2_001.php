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
require_once(modification("dbforms/db_classesgenericas.php"));

$classenta = new cl_assenta;
$rotulocampo = new rotulocampo;
$rotulocampo->label("rh01_regist");
?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">
        <?php
        db_app::load("scripts.js, strings.js, datagrid.widget.js, windowAux.widget.js,dbautocomplete.widget.js");
        db_app::load("dbmessageBoard.widget.js, prototype.js, dbtextField.widget.js, dbcomboBox.widget.js, DBAbas.widget.js");
        db_app::load("estilos.css, grid.style.css, AjaxRequest.js");
        ?>
    </head>
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" class="body-default abas">
        <div id="abas"></div>
        <div id="aba_imprimir">
            <form action="" method="post" name="form1">  
                <table align="center">
                    <tr>
                        <td>
                            <fieldset>
                                <legend><b>&nbsp;Portaria 154 - Anexo X &nbsp;</b></legend>
                                <table width="100%">
                                    <tr>
                                        <td><strong>UNIDADE GESTORA:</strong></td>
                                        <td>
                                            <?php
                                            $aUnidadeGestora = array (
                                                '1' => 'RPPS', 
                                                '2' => 'RGPS'
                                            );
                                            db_select('iUnidadeGestora', $aUnidadeGestora, true, 1,"style='width:125px'");
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php db_ancora(@$Lrh01_regist, "js_pesquisarh01_regist(true);", 1);?>
                                        </td>
                                        <td>
                                            <?php
                                            db_input('rh01_regist', 8, $Irh01_regist, true, 'text', 1, " onchange='js_pesquisarh01_regist(false);'");
                                            db_input('z01_nome', 30, "Nome", true, 'text', 3, '');
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>        
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            <input type="button" name='btnRelatorio' value='Relatório' onclick="js_imprimir()" />
                        </td>
                    </tr>
                </table>    
            </form>
        </div>
        <div id="aba_anosanterior" class="container">
            <fieldset>
                <legend>Atualização de informações de anos anteriores</legend>
                <form action="" method="post" name="form2">  
                    <table width="100%">
                        <tr>
                            <td>
                                <strong>Exercicio:</strong>
                            </td>
                            <td>
                                <select id="exercicioopcoes" onchange="exibeTabelaExercicio()">
                                    
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td id="exercicios" colspan="2">
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">
                                <input type="button" name='btnSalvar' value='Salvar' onclick="salvarExercicios()"/>
                            </td>
                        </tr>
                    </table>
                </form>   
            </fieldset>
        </div>
        <?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
    </body>
</html>
<script>
    var oAbas = new DBAbas($('abas'));
    var oAbaImprimir = oAbas.adicionarAba('Imprimir', $('aba_imprimir'));
    var oAbaAnoAnterior = oAbas.adicionarAba('Anos Anteriores', $('aba_anosanterior'));
    var tabelaAtual = null;
    var sUrl = 'portaria154.RPC.php';
    var aMeses = new Array('JANEIRO', 'FEVEREIRO', 'MARÇO', 'ABRIL', 'MAIO', 'JUNHO', 'JULHO', 'AGOSTO', 'SETEMBRO', 'OUTUBRO', 'NOVEMBRO', 'DEZEMBRO', '13º SALARIO / GRAT. NATALINA');

    oAbaAnoAnterior.bloquear();

    function js_imprimir() {
        var matricula =  document.form1.rh01_regist.value;
        var unidadegestora = document.getElementById('iUnidadeGestora').value;
        if (matricula === "") {
            alert("Matrícula não informada!")
            return false;
        }

        var oParam = {
            matricula: matricula,
            unidadegestora: unidadegestora
        };
        window.open('portaria154anexo2_002.php?json=' + Object.toJSON(oParam));
    }
  
    function js_detectaarquivo(sArquivo){
        var sListagem = sArquivo + "#Download arquivo ";
        js_montarlista(sListagem,"form1");
    }

    function js_pesquisarh01_regist(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
        } else {
            if (document.form1.rh01_regist.value != '') { 
                js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
            }
        }
    }

    function salvarExercicios() {
        js_divCarregando('Aguarde, salvando informações.', 'msgbox');
        var dadosExercicio = document.querySelectorAll("[data-exercicio]");
        var dados = [];
        dadosExercicio.forEach(function(elemento){
            var dado = {
                exercicio:elemento.getAttribute('data-exercicio'),
                mes:elemento.getAttribute('data-mes'),
                valor:elemento.value
            }; 
            dados.push(dado);

        });
        var oParam = {
            matricula: matricula,
            exec: 'salvarExercicios',
            dados:dados
        };
        var oAjax = new Ajax.Request(
            sUrl, 
            {
                method: 'post',
                parameters: 'json='+Object.toJSON(oParam),
                onComplete: retornoExercicioSalvar
            }
        );
    }

    function js_mostrapessoal(chave, erro) {
        document.form1.z01_nome.value = chave; 
        if (erro == true) {
            document.form1.rh01_regist.focus(); 
            document.form1.rh01_regist.value = '';
            oAbaAnoAnterior.bloquear();
        } else {
            buscarExercicio();
        }
    }

    function js_mostrapessoal1(chave1, chave2) {
        document.form1.rh01_regist.value = chave1;
        document.form1.z01_nome.value = chave2;
        db_iframe_rhpessoal.hide();
        buscarExercicio();
    }

    function buscarExercicio() {
        js_divCarregando('Aguarde, pesquisando informações.', 'msgbox');
        matricula = document.form1.rh01_regist.value;
        var oParam = {
            matricula: matricula,
            exec: 'getExercicioMatriculas'
        };

        var oAjax = new Ajax.Request( 
            sUrl, 
            {
                method: 'post',
                parameters: 'json='+Object.toJSON(oParam),
                onComplete: retornoExercicio
            }
        );
    }

    function retornoExercicioSalvar(oRetorno) {
        js_removeObj('msgbox');
        var retorno = JSON.parse(oRetorno.responseText);
        alert(retorno.message);
    }

    function retornoExercicio(oRetorno) {
        js_removeObj('msgbox');
        oAbaAnoAnterior.desbloquear();
        $('exercicios').innerHTML = '';
        var retorno = JSON.parse(oRetorno.responseText);

        if (retorno.status == 2) {
            alert(retorno.erro);
            return false;
        }

        var exercicios = document.getElementById('exercicioopcoes');
        var opcao = document.createElement("option");
        exercicios.innerHTML = '';
        opcao.text = 'Selecione';
        opcao.value = '';
        exercicios.add(opcao);
        if (retorno.possuiDados === false) {
            for (var i = retorno.anoInicial; i <= retorno.anoBloqueio; i++) {
                var opcao = document.createElement("option");
                opcao.text = i;
                exercicios.add(opcao);

                var div = document.createElement('div');

                var tabela = document.createElement('table');
                tabela.id =  'exercicio_'+i;

                for (var j = 0; j < aMeses.length; j++) {
                    var tr = document.createElement('tr');
                    var td = document.createElement('td');
                    td.innerHTML = aMeses[j];
                    var td2 = document.createElement('td');
                    var input = document.createElement('input');
                    input.type = 'text';
                    input.setAttribute('onkeyup',"mascaraMoeda(this)");
                    input.setAttribute('data-exercicio',i);
                    input.setAttribute('data-mes',j+1);
                    input.value = '0,00';
                    if (i == retorno.anoInicial) {
                        if (j+1 < retorno.mesInicial) {
                            input.value = '-';
                            input.setAttribute('disabled',"disabled")
                        }
                    }

                    if (i == retorno.anoBloqueio) {
                        if (j+1 > retorno.mesBloqueio) {
                            input.setAttribute('disabled',"disabled")
                        }
                    }
                    td2.appendChild(input);

                    tr.appendChild(td);
                    tr.appendChild(td2);
                    tabela.appendChild(tr);
                    tabela.style.display = "none";
                }

                div.appendChild(tabela);
                
                $('exercicios').appendChild(div);
            }
        } else {
            retorno.mesInicial = Number(retorno.mesInicial);
            retorno.anoInicial = Number(retorno.anoInicial);
            retorno.mesBloqueio = Number(retorno.mesBloqueio);
            retorno.anoBloqueio = Number(retorno.anoBloqueio);
            for (i in retorno.dados) {
                var dado = retorno.dados[i];
                var opcao = document.createElement("option");
                var div = document.createElement('div');
                var tabela = document.createElement('table');

                opcao.text = dado.exercicio;
                exercicios.add(opcao);
                // Cast de string para numero
                i = Number(i);

                tabela.id =  'exercicio_'+dado.exercicio;

                for (j in dado.mes) {
                    var mes = dado.mes[j];
                    var tr = document.createElement('tr');
                    var td = document.createElement('td');
                    var td2 = document.createElement('td');
                    var input = document.createElement('input');
                    // Cast de string para numero
                    j = Number(j);
                    td.innerHTML = aMeses[j-1];
                    input.type = 'text';
                    input.setAttribute('onkeyup',"mascaraMoeda(this)");
                    input.setAttribute('data-exercicio',i);
                    input.setAttribute('data-mes',j);
                    input.setAttribute('value',mes);
                    if (i == retorno.anoInicial) {
                        if (j < retorno.mesInicial) {
                            input.setAttribute('disabled',"disabled")
                        }
                    }

                    td2.appendChild(input);

                    tr.appendChild(td);
                    tr.appendChild(td2);
                    tabela.appendChild(tr);
                    tabela.style.display = "none";
                }

                div.appendChild(tabela);
                
                $('exercicios').appendChild(div);
                continue;
            }
        }
    }

    function mascaraMoeda(elemento) {
        var el = elemento.value

        v = el.replace(/\D/g,"");
        v = new String(Number(v));
        var len = v.length;

        if (1== len) {
            v = v.replace(/(\d)/,"0,0$1");
        } else {
            if (2 == len) {
            v = v.replace(/(\d)/,"0,$1");
            } else {
                if (len > 2) {
                   v = v.replace(/(\d{2})$/,',$1');
                }
            }
        }
        elemento.value = v;
     }

    function exibeTabelaExercicio() {
        var x = document.getElementById('exercicioopcoes').value;
        if (x !== '') {
            if (tabelaAtual == null) {
                tabelaAtual = x;
            }
            var tabela1 = document.getElementById('exercicio_' + x);
            var tabela2 = document.getElementById('exercicio_' + tabelaAtual);
            $(tabela2).style.display = 'none';
            $(tabela1).style.display = '';
            tabelaAtual = x;
        } else {
            if (tabelaAtual != null) {
                var tabela2 = document.getElementById('exercicio_' + tabelaAtual);
                $(tabela2).style.display = 'none';
            }
        }
    }
</script>
<style type="text/css">
    #exercicios td input {
        text-align: right;
    }
</style>
