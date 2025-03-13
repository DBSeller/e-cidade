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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));

$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
?>
<html>
<head>

    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript"
            src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">

</head>

<body  class="body-default">
<div class="container">

    <form name="frmBalanceteVerificacao" method="post" action="">
        <table>
            <tr>
                <td>
                    <fieldset>
                        <legend>Balancete da Matriz de Saldo Contábeis</legend>
                        <table>
                            <tr>
                                <td>
                                    <label for="competencia">
                                        <b>Competência:</b>
                                    </label>
                                </td>
                                <td>
                                    <input id="competencia" />
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="bold" id="lbl_estruturais" for="estrut_inicial">Estruturais:</label>
                                </td>
                                <td colspan="3">
                                    <?php
                                    $Testrut_inicial = 'Informe os estruturais separados por vírgula';
                                    db_input('estrut_inicial', '', '', false, '', '', 'style="width: 100%;"')
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="bold" id="lbl_encerramento" for="encerramento">Com Encerramento:</label>
                                </td>
                                <td colspan="3">
                                    <?php
                                    $opcoes = array('n' => "Não", 's' => "Sim");
                                    db_select('encerramento', $opcoes, true, 1);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="bold" id="lbl_tipoemissao" for="tipoemissao">Tipo de emissão:</label>
                                </td>
                                <td colspan="3">
                                    <?php
                                    $opcoes = array('a' => "Analítico", 's' => "Sintético");
                                    db_select('tipoemissao', $opcoes, true, 1);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="bold" id="lbl_formato" for="tipoemissao">Formato:</label>
                                </td>
                                <td colspan="3">
                                    <?php
                                    $formatos = array('pdf' => "PDF", 'csv' => "CSV");
                                    db_select('formato', $formatos, true, 1);
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <fieldset style="width: 500px;">
                                        <legend>Instituições</legend>
                                        <div id="oGridInstituicoes">&nbsp;</div>
                                    </fieldset>
                                </td>
                            </tr>
                        </table>
                    </fieldset>

                </td>
            </tr>
        </table>
        <input type="button" value="Emitir" id="emitir" name="emitir" />
    </form>
</div>
</body>
</html>
<script>
    var RPC = "con4_matrizsaldocontabil.RPC.php";
    var competencia = new DBInput($('competencia'));
    competencia.inputElement.placeholder = '__/____';
    competencia.inputElement.size        = '7';
    competencia.inputElement.maxLength   = '7';

    competencia.inputElement.observe('blur', function(){
        var conteudo = $('competencia').value.replace(/\s+/g, '');
        var dataCampo = conteudo.split('/');
        var mes = dataCampo[0].trim();

        if (mes < 1 || mes > 12) {
            alert('Mês inválido.');
            $('competencia').value = '';
            return;
        }
    }.bind(competencia));

    new MaskedInput(competencia.inputElement, '99/9999', {placeholder: '_'});

    var oGridInstituicao = new DBGrid('listainstituicoes');
    oGridInstituicao.nameInstance = 'oGridInstituicoes';
    oGridInstituicao.setHeader(new Array("Código", "Instituições"));
    oGridInstituicao.setCellWidth(['10%', '90%']);
    oGridInstituicao.setCellAlign(new Array("center", "left"));
    oGridInstituicao.show($('oGridInstituicoes'));

    function getInstituicoesConfiguradas() {
        var ajax = new AjaxRequest(RPC, {sExecucao: 'buscarInstituicoesConfiguradas'}, function (oRetorno, lErro) {

            if (lErro){
                alert('Ocorreu um erro ao buscar as instituições configuradas.');
                return;
            }

            if (oRetorno.instituicoes.length == 0) {
                alert("Nenhuma instituição configurada. Para configurar acesse: \nContabilidade > Procedimentos > Matriz de Saldos Contábeis > Configuração de Instituições.");
                return;
            }

            instituicoes = oRetorno.instituicoes;

            oGridInstituicao.clearAll(true);
            for(var instituicao of instituicoes) {
                var aLinha = [];
                aLinha[0]  = instituicao.codigo;
                aLinha[1]  = instituicao.nome;

                oGridInstituicao.addRow(aLinha, true, true, false);
            }

            oGridInstituicao.renderRows();
        }).execute();
    }
    getInstituicoesConfiguradas();


    $('emitir').observe('click', function(){
       var param = {
           "tipo": "pdf",
           "competencia" : $F('competencia'),
           "tipoemissao" : $F('tipoemissao'),
           "formato"     : $F('formato'),
           "estruturais" : $F('estrut_inicial'),
           "encerramento" : $F('encerramento')
       };

       var relatorio = 'con2_balancetemsc002.php?tipo='+param.tipo+'&competencia='+param.competencia;
       relatorio += '&estruturais='+param.estruturais;
       relatorio += '&tipoemissao='+param.tipoemissao;
       relatorio += '&formato='+param.formato;
       relatorio += '&encerramento='+param.encerramento;
       window.open(relatorio, '', 'location=0');
       return;
    });


</script>
