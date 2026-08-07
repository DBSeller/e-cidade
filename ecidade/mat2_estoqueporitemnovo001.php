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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

db_postmemory($HTTP_POST_VARS);

$aux = new cl_arquivo_auxiliar;
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load('scripts.js, prototype.js, strings.js');
    db_app::load('estilos.css');
    ?>
    <script type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
</head>
<body>
<div class="container">
    <form name="form1" method="post" action="">
        <fieldset>
            <legend><b>Selecionar Depósitos</b></legend>
            <table class="form-container">
                <tr>
                    <td nowrap="nowrap">
                        Opções:
                    </td>
                    <td>
                        <select name="ver">
                            <option name="condicao1" value="true">Com os depósitos selecionados</option>
                            <option name="condicao1" value="false">Sem os depósitos selecionados</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap" colspan=2>
                        <div id="ctnDeposito"></div>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap">
                        Posição até:
                    </td>
                    <td nowrap="nowrap">
                        <?php
                        db_inputdata('data1', '', '', '', true, 'text', 1, "");
                        echo "<b> á</b> ";
                        db_inputdata('data2', '', '', '', true, 'text', 1, "");
                        ?>&nbsp;
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap" title="Quebra por depósito">
                        Quebra por depósito:
                    </td>
                    <td nowrap="nowrap">
                        <?php
                        $tipo_que = array("N" => "Não", "S" => "Sim");
                        db_select("quebra", $tipo_que, true, 2, "onchange='js_testord(this.value);'");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap" title="Ordem por  Codigo/Departamento/Material">
                        Ordem:
                    </td>
                    <td nowrap="nowrap">
                        <?php
                        $tipo_ordem = array("a" => "Alfabética", "c" => "Codigo", "d" => "Departamento");
                        db_select("ordem", $tipo_ordem, true, 2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap" title="Estoque Zerado">
                        Listar estoque zerado:
                    </td>
                    <td nowrap="nowrap">
                        <?php
                        $tipo_est = array("N" => "Não", "S" => "Sim");
                        db_select("list_zera", $tipo_est, true, 2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap" title="Tipo">
                        Tipo:
                    </td>
                    <td nowrap="nowrap">
                        <?php
                        $tipo_rel = array("S" => "Sintético","A"=>"Analítico", "C" => "Conferência");
                        db_select("tipo", $tipo_rel, true, 2);
                        ?>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();">
    </form>
</div>

</body>
</html>
<script>
    var oLancadorDeposito = new DBLancador('LancadorDeposito');
    oLancadorDeposito.setLabelAncora("Depósito");
    oLancadorDeposito.setTextoFieldset("Depósito");
    oLancadorDeposito.setTituloJanela("Pesquisar Depósito");
    oLancadorDeposito.setNomeInstancia("oLancadorDeposito");
    oLancadorDeposito.setParametrosPesquisa("func_db_almox.php", ["m91_codigo", "descrdepto"], "sDescricaoDepartamento=false");
    oLancadorDeposito.setGridHeight(150);
    oLancadorDeposito.show($("ctnDeposito"));

    function js_testord(valor) {
        if (valor == 'S') {
            document.form1.ordem.value = 'b';
            document.form1.ordem.disabled = true;
        } else {
            document.form1.ordem.value = 'a';
            document.form1.ordem.disabled = false;
        }
    }

    function js_mandadados() {
        query = "";
        vir = "";

        //pega o valor das instituições selecionadas no campo db_selinstit do 'g3'
        var selInstit = (parent.iframe_g3.document.form1.db_selinstit.value);

        //valida se alguma instituição foi informada
        if (selInstit != 0) {
            //roda a variavel e faz um replace de '-' por ','
            for (x = 0; x < selInstit.length; x++) {
                selInstit = (selInstit.replace('-', ','));

            }
        } else {
            //se não foi selecionada nehuma isntituição retorna para seleção
            alert('Você não escolheu nenhuma instituição. Verifique!');
            return false;

        }

        var sDepositos = '';
        oLancadorDeposito.getRegistros().each(function(oDadosAlmoxarifado, iIndice) {

            if (iIndice > 0) {
                sDepositos += ',';
            }
            sDepositos += oDadosAlmoxarifado.sCodigo;
        });

        vir = "";
        listamat = "";

        for (x = 0; x < parent.iframe_g2.document.form1.material.length; x++) {
            listamat += vir + parent.iframe_g2.document.form1.material.options[x].value;
            vir = ",";
        }

        query += '&depositos=' + sDepositos;
        query += '&veralmoxarifados=' + document.form1.ver.value;
        query += '&listamaterial=' + listamat;
        query += '&vermaterial=' + parent.iframe_g2.document.form1.ver.value;
        query += '&data_inicial=' + document.form1.data1.value;
        query += '&data_final=' + document.form1.data2.value;
        query += '&ordem=' + document.form1.ordem.value;
        query += '&tipoimpressao=' + document.form1.tipo.value;
        query += '&verestoquezerado=' + document.form1.list_zera.value;
        //query += '&listarservico='+document.form1.listar_serv.value;
        query += '&quebrapordepartamento=' + document.form1.quebra.value;
        query += '&opcao_material=' + parent.iframe_g2.document.form1.opcao_material.value;
        query += '&instituicoes=' + selInstit;
        console.log(query);
        //jan = window.open('mat2_relestoque002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan = window.open('mat2_estoqueporitemnovo002.php?' + query, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
    }

    $('quebra').style.width = '123px';
    $('list_zera').style.width = '123px';
    // $('listar_serv').style.width = '123px';
    $('tipo').style.width = '123px';
    $('ordem').style.width = '123px';
</script>
