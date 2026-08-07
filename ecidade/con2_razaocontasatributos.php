<?PHP
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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$oDaoUnidadeGestora = new cl_unidadegestora();
$oDaoUnidadeGestora->rotulo->label();


?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta http-equiv="Expires" CONTENT="0">

        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAncora.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
        <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <style>
    .ComboRazao {
    width: 220px;
    }
    #data1, #data2 {
    width: 70px;
    }
    </style>
    <body bgcolor=#CCCCCC bgcolor="#CCCCCC" onload="buscarContaCorrente()">
        <center>
        <form name="form1" method="post" action="">
            <fieldset style="margin-top: 30px; width: 580px; text-align: left;">
                <legend>Relatório de Razão por Contas</legend>
                <table style="text-align: left" border='0'>
                    <tr>
                        <td nowrap align=left>
                            <b>Período:</b>
                        </td>
                        <td nowrap align=left>
                            <?php
                            $dia  = date("d",db_getsession("DB_datausu"));
                            $mes  = date("m",db_getsession("DB_datausu"));
                            $ano  = date("Y",db_getsession("DB_datausu"));
                            $dia2 = date("d",db_getsession("DB_datausu"));
                            $mes2 = date("m",db_getsession("DB_datausu"));
                            $ano2 = date("Y",db_getsession("DB_datausu"));
                            db_inputdata('data1',@$dia,@$mes,@$ano,true,'text',1,"");
                            echo "<strong>a</strong>";
                            db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><strong> Estrutural: </strong></td>
                        <td>
                            <input type='text' name='estrut_inicial' id='estrut_inicial' size='15' maxlength='15'  class="ComboRazao">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label id="lblUg" for="k171_sequencial">Unidade Gestora:</label>
                        </td>
                        <td colspan="3">
                            <?php
                            db_input('k171_sequencial',   10, $Ik171_sequencial, true, 'text', 1);
                            db_input('k171_nome', 40,         $Ik171_nome, true, 'text', 3);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td align = "left"><strong> Conta Corrente: </strong></td>
                        <td id="ctnContasCorrente"> </td>
                    </tr>
            </table>
        </fieldset>
    </div>
    <div id='ctnLancadorDocumentos' style="margin-top: 10px; width: 600px;"> </div>
    <div id='ctnLancadorContas' style="margin-top: 10px; width: 600px;"> </div>
    <div style="margin-top: 10px;">
        <input type="button" id="emite" value="Emitir" onClick="js_imprimir()">
    </div>
</form>
</center>
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>

    const CAMINHO_MENSAGEM_TELA = "financeiro.contabilidade.con2_razaocontas001.";
    
    var rpc        = "con2_razaocontasatributos.RPC.php";
    var alturaGrid = "75px";
    var aOptions   = new Array();

    var oUnidadeGestora = new DBLookUp( $('lblUg'),
                                        $('k171_sequencial'),
                                        $('k171_nome'),
                                        {
                                            sArquivo: 'func_unidadegestora.php'
                                        });

    var cboContasCorrennte = new DBComboBox('contaCorrente', 'contaCorrente', aOptions, '180');
    cboContasCorrennte.show($('ctnContasCorrente'));

    function filtroAtributo()
    {
        var retorno = [];
        var linhas = document.querySelectorAll("tr[identificador]");
        if (linhas.length > 0) {
            for (var elemento of linhas) {
                var sigla = elemento.querySelector("td[sigla]").getAttribute("sigla");
                var valor = elemento.querySelector("td[valor]").getAttribute("valor");
                retorno.push({sigla: sigla, valor: valor});
            }
        }
        return retorno;
    }

    /**
    * Cria o lançador para os Documentos
    */
    function js_criarLancadorDocumentos()
    {
        oLancadorDocumentos = new DBLancador("oLancadorDocumentos");
        oLancadorDocumentos.setNomeInstancia("oLancadorDocumentos");
        oLancadorDocumentos.setLabelAncora("Documentos: ");
        oLancadorDocumentos.setTextoFieldset("Documentos Selecionados");
        oLancadorDocumentos.setParametrosPesquisa("func_conhistdoc.php", ['c53_coddoc', 'c53_descr']);
        oLancadorDocumentos.setGridHeight(alturaGrid);
        oLancadorDocumentos.setTituloJanela("Pesquisar Documentos");
        oLancadorDocumentos.show($("ctnLancadorDocumentos"));
    }
    
    js_criarLancadorDocumentos();
    /**
    * Cria o lançador para as contas
    */
    function js_criarLancadorContas()
    {
        oLancadorContas = new DBLancador("oLancadorContas");
        oLancadorContas.setNomeInstancia("oLancadorContas");
        oLancadorContas.setLabelAncora("Contas: ");
        oLancadorContas.setTextoFieldset("Contas Selecionadas");
        oLancadorContas.setParametrosPesquisa("func_conplanoexe.php", ['c62_reduz', 'c60_descr']);
        oLancadorContas.setGridHeight(alturaGrid);
        oLancadorContas.setTituloJanela("Pesquisar Contas");
        oLancadorContas.show($("ctnLancadorContas"));
    }
    js_criarLancadorContas();
    
    function retornoRelatorio(oAjax)
    {
        js_removeObj('msgbox');
        var oRetorno = JSON.parse(oAjax.responseText);
        if (oRetorno.erro) {
            alert(oRetorno.mensagem.urlDecode());
            return false;
        }
        window.open(oRetorno.pdf, "Relatório", "fullscreen=yes");
    }

    function js_imprimir()
    {

        filtroAtributos = filtroAtributo(); 
        js_divCarregando('Aguarde, gerando relatório...', 'msgbox');

        var oParam  = new Object();
        var filtros = new Object();
         
        var data1 = document.form1.data1_ano.value+"-"+document.form1.data1_mes.value+"-"+document.form1.data1_dia.value;
        var data2 = document.form1.data2_ano.value+"-"+document.form1.data2_mes.value+"-"+document.form1.data2_dia.value;
        var contas     = oLancadorContas.getRegistros();
        var documentos = oLancadorDocumentos.getRegistros();
        var estrut_inicial       = $F('estrut_inicial');
        
        
        if(data1.valueOf() > data2.valueOf()){
            alert(_M(CAMINHO_MENSAGEM_TELA + "data_inicial_maior_final") );
            js_removeObj('msgbox');
            return false;
        }

        var data1 = js_formatar($F("data1"), 'd');
        var data2 = js_formatar($F("data2"), 'd');
        var unidadeGestora = $F("k171_sequencial");

        filtros.atributos      = filtroAtributos;
        filtros.ug             = unidadeGestora;
        filtros.datainicial    = data1;
        filtros.datafinal      = data2;
        filtros.documentos     = documentos;
        filtros.contas         = contas;
        filtros.estrut_inicial = estrut_inicial;
        filtros.contaCorrente  = cboContasCorrennte.getValue();
        oParam.filtros         = filtros;

        oParam.exec              = 'gerarRelatorio';
        var oAjax                = new Ajax.Request(
            rpc, 
            {
                method:'post',
                parameters:'json='+Object.toJSON(oParam),
                onComplete: retornoRelatorio
            } 
        );
    }

    function buscarContaCorrente()
    {


        var oParam  = new Object();
        oParam.exec = 'buscarContasCorrente';
        var oAjax   = new Ajax.Request(
            rpc,
            {
                method:'post',
                parameters:'json='+Object.toJSON(oParam),
                onComplete: function(oAjax){
                    var oRetorno = JSON.parse(oAjax.responseText);
                    if (oRetorno.erro) {
                        alert(oRetorno.mensagem.urlDecode());
                        return false;
                    } else {

                        oRetorno.contas.forEach(function(conta) {
                            cboContasCorrennte.addItem(conta.codigo, conta.descricao.urlDecode());
                        });
                    }
                }
            }
        );
    }

</script>
