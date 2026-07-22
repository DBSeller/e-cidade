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
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_classesgenericas.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_orctiporec_classe.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k02_codigo');
$clrotulo->label('k02_drecei');
$clrotulo->label('o08_reduz');

$clorctiporec = new cl_orctiporec;
$clorctiporec->rotulo->label();

db_postmemory($_POST);
?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript"  type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script>
        function js_verifica() {
            var anoi = new Number(document.form1.datai_ano.value);
            var anof = new Number(document.form1.dataf_ano.value);
            if (anoi.valueOf() > anof.valueOf()) {
                alert('Intervalo de data invalido. Velirique !.');
                return false;
            }
            return true;
        }


        function js_emite() {
            vir = "";
            cods = "";
            var_obj = document.getElementById('receita').length;
            for (y = 0; y < var_obj; y++) {
                var_if = document.getElementById('receita').options[y].value;
                cods += vir + var_if;
                vir = ",";
            }

            recurso = "";
            if (document.form1.o15_recurso.value != '') {
                recurso = document.form1.o15_recurso.value;
            }

            qry = "estrut=" + document.form1.estrut.value;
            qry += "&sinana=" + document.form1.sinana.value;
            qry += "&ordem=" + document.form1.ordem.value;
            qry += "&desdobrar=" + document.form1.desdobrar.value;
            qry += "&codrec=" + cods;
            qry += "&datai=" + document.form1.datai_ano.value + '-' + document.form1.datai_mes.value + '-' + document.form1.datai_dia.value;
            qry += "&dataf=" + document.form1.dataf_ano.value + '-' + document.form1.dataf_mes.value + '-' + document.form1.dataf_dia.value;
            qry += "&tipo=" + document.form1.tipo.value;
            qry += "&recurso=" + recurso;

            jan = window.open('cai2_correceitas002.php?' + qry, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
            jan.moveTo(0, 0);
        }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>

<form class="container" name="form1" method="post" action="" onsubmit="return js_verifica();">
    <fieldset>
        <legend>Receitas Por Período - Tesouraria</legend>
        <table>
            <tr>
                <td align="rigth">
                    <strong>Data Inicial :</strong>
                </td>
                <td align="left">
                    <?php
                    db_inputdata('datai', '01', '01', db_getsession("DB_anousu"), true, 'text', 4);

                    echo "<strong>Data Final :</strong>";
                    $datausu = date("Y/m/d", db_getsession("DB_datausu"));
                    $dataf_ano = substr($datausu, 0, 4);
                    $dataf_mes = substr($datausu, 5, 2);
                    $dataf_dia = substr($datausu, 8, 2);

                    db_inputdata('dataf', $dataf_dia, $dataf_mes, $dataf_ano, true, 'text', 4);
                    ?>
                </td>
            </tr>
            <tr>
                <td ><strong>Estrutural da Receita:</strong>
                </td>
                <td>
                    <?php
                    db_input('estrut', 15, 0, true, 'text', 2, "");
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Tipo de Receita:</strong>
                </td>
                <td>
                    <select name="tipo" onchange="js_valor();">
                        <option value='T'>Todas</option>
                        <option value='O'>Orçamentarias</option>
                        <option value='E'>Extra-Orçamentarias</option>
                </td>
            </tr>
            <tr>
                <td ><strong>Desdobrar Receita:</strong>
                </td>
                <td>
                    <select name="desdobrar" onchange="js_valor();">
                        <option value='N'>Não</option>
                        <option value='S'>Sim</option>
                </td>
            </tr>
            <tr>
                <td ><strong>Ordem:</strong>
                </td>
                <td>
                    <select name="ordem">
                        <option value='r'>Código Receita</option>
                        <option value='e'>Estrutural</option>
                        <option value='a'>Alfabética Descrição Receita</option>
                        <option value='d'>Reduzido Orçamento</option>
                        <option value='c'>Reduzido Conta</option>
                </td>
            </tr>
            <tr>
                <td ><strong>Tipo:</strong>
                </td>
                <td>
                    <select name="sinana">
                        <option value='S1'>Sintético/Receita</option>
                        <option value='S2'>Sintético/Estrutural</option>
                        <option value='A'>Analítico</option>
                        <option value='S3'>Sintético/Conta</option>
                </td>
            </tr>
            <tr>
                <td><a id='ancoraRecurso' href="#">Recurso:</a></td>
                <td>
                    <input type="text" name="recurso" id="o15_recurso" class="field-size2">
                    <input type="text" id="o15_descr" class="field-size8 readonly" readonly>
                </td>
            </tr>

            <tr>
                <td colspan="2" align="center">
                    <?php
                    $aux = new cl_arquivo_auxiliar;
                    $aux->cabecalho = "<strong>RECEITAS</strong>";
                    $aux->codigo = "k02_codigo";
                    $aux->descr = "k02_drecei";
                    $aux->nomeobjeto = 'receita';
                    $aux->funcao_js = 'js_mostra';
                    $aux->funcao_js_hide = 'js_mostra1';
                    $aux->sql_exec = "";
                    $aux->func_arquivo = "func_tabrec_todas.php";
                    $aux->nomeiframe = "db_iframe";
                    $aux->localjan = "";
                    $aux->db_opcao = 2;
                    $aux->tipo = 2;
                    $aux->top = 0;
                    $aux->linhas = 6;
                    $aux->vwhidth = 400;
                    $aux->funcao_gera_formulario();
                    ?>
                </td>
            </tr>
        </table>
    </fieldset>
    <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();">
</form>

<?php
db_menu();
?>
</body>
</html>
<script>

    const lookupRecurso = new DBLookUp($('ancoraRecurso'), $('o15_recurso'), $('o15_descr'), {
        "sArquivo": "func_fonterecurso.php",
        "sObjetoLookUp": "db_iframe_orctiporec",
        "sLabel": "Pesquisar Recurso"
    });

    function js_pesquisatabrec(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta', 'db_iframe_conclass', 'func_tabrec_todas.php?funcao_js=parent.js_mostratabrec1|0|3', 'Pesquisa', true, '0');
        } else {
            if (document.form1.c60_codcla.value != '') {
                js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta', 'db_iframe_conclass', 'func_tabrec_todas.php?pesquisa_chave=' + document.form1.k02_codigo.value + '&funcao_js=parent.js_mostratabrec', 'Pesquisa', false);
            } else {
                document.form1.k02_drecei.value = '';
            }
        }
    }

    function js_mostratabrec(chave, erro) {
        document.form1.k02_drecei.value = chave;
        if (erro == true) {
            document.form1.k02_codigo.focus();
            document.form1.k02_codigo.value = '';
        }
    }

    function js_mostratabrec1(chave1, chave2) {
        document.form1.k02_codigo.value = chave1;
        document.form1.k02_drecei.value = chave2;
        db_iframe.hide();
    }
</script>

<?php
if (isset($ordem)) {
    echo "<script>
                   js_emite();
       </script>";
}
?>
