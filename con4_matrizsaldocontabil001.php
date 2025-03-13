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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<html>
    <head>
      <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <meta http-equiv="" quiv="Expires" CONTENT="0">
      <link href="estilos.css" rel="stylesheet" type="text/css">
      <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
      <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
      <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>

    </head>
    <body class="body-default">
        <div class="container">
            <form>
                <fieldset>
                    <legend>Matriz de Saldos Contábeis</legend>

                    <table class="form-container">
                        <tr>
                            <td>
                                <label for="ultimaCompetenciaProcessada">Última Competência Processada:</label>
                            </td>
                            <td>
                                <input id="ultimaCompetenciaProcessada" style="width: 70px" class="readonly" readonly/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="competencia">Competência:</label>
                            </td>
                            <td>
                                <input id="competencia" />
                            </td>
                        </tr>
                        <tr id="container_encerramento">
                            <td>
                                <label for="encerramento">Matriz de encerramento:</label>
                            </td>
                            <td>
                                <select id="encerramento">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="encerrar">Encerrar Competência:</label>
                            </td>
                            <td>
                                <select id="encerrar">
                                    <option value="0">Não</option>
                                    <option value="1">Sim</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="processamento">Processamento:</label>
                            </td>
                            <td>
                                <select id="processamento">
                                    <option value="2">Apenas processar dados</option>
                                    <option value="1">Processar dados e Emitir Arquivo</option>
                                    <option value="3">Apenas emitir Arquivo</option>
                                </select>
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

                <input type="button" value="Emitir" id="emitir" name="emitir" />
            </form>
        </div>
        <?php db_menu(); ?>
    </body>
</html>

<script type="text/javascript">

    var RPC = "con4_matrizsaldocontabil.RPC.php";
    var competencia = new DBInput($('competencia'));
    var ultimaCompetenciaProcessada;
    var proximaCompetenciaProcessada;
    var instituicoes = new Array();
    $('container_encerramento').hide();

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

	    $('container_encerramento').hide();
	    $('encerramento').value = 0;
	    $('encerramento').dispatchEvent(new Event('change'));

        if (mes == 12) {
	        $('container_encerramento').show();
        }
    }.bind(competencia));

    $('encerramento').observe('change', function(){
	    $('encerrar').disabled = false;
	    $('processamento').disabled = false;

	    if ($('encerramento').value == 1) {
            $('encerrar').value = 0;
            $('processamento').value = 1;

		    // $('encerrar').disabled = true;
		    // $('processamento').disabled = true;
        }
    });

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
                //$('emitir').disabled = true;
                return;
            }


            $('emitir').disabled = false;
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

    /**
     * Carrega o campo Última Competência Processada com a última competência que foi processada a matriz.
     * Carrega o campo Competência com a próxima competência a ser processada.
     */
    function carregaCompetencia() {
        var parametros = {
            'sExecucao'   : 'retornaUltimaCompetenciaProcessada'
        };

        new AjaxRequest(RPC, parametros, function (retorno, erro) {

            var mesUltimaCompetenciaProcessada = retorno.ultimaCompetenciaProcessada.mes;
            var anoUltimaCompetenciaProcessada = retorno.ultimaCompetenciaProcessada.ano;
            var mesProximaCompetenciaProcessada = Number(retorno.ultimaCompetenciaProcessada.mes);
            var anoProximaCompetenciaProcessada = Number(retorno.ultimaCompetenciaProcessada.ano);
            ultimaCompetenciaProcessada = retorno.ultimaCompetenciaProcessada.stringCompetencia;


            if(ultimaCompetenciaProcessada != null) {
                $('ultimaCompetenciaProcessada').value = ultimaCompetenciaProcessada;
            }

            if(mesUltimaCompetenciaProcessada == 12 || mesUltimaCompetenciaProcessada == 13) {
                mesProximaCompetenciaProcessada = 1;
                anoProximaCompetenciaProcessada = parseInt(anoUltimaCompetenciaProcessada) + 1;
            }

            if(mesUltimaCompetenciaProcessada >= 1 && mesUltimaCompetenciaProcessada <= 11) {
                mesProximaCompetenciaProcessada = parseInt(mesProximaCompetenciaProcessada.valueOf()) + 1;
                anoProximaCompetenciaProcessada = anoUltimaCompetenciaProcessada;
            }

            proximaCompetenciaProcessada = js_strLeftPad(mesProximaCompetenciaProcessada, 2, "0") + "/" + anoProximaCompetenciaProcessada;
            $('competencia').value = proximaCompetenciaProcessada;
	        $('competencia').dispatchEvent(new Event('blur'));
        }).execute();
    }

    carregaCompetencia();

    function verficaDeparaRecursos() {

        var parametros = {
            'sExecucao'   : 'verificaDeparaRecursos'
        };

        new AjaxRequest(RPC, parametros, function (retorno, erro) {

            $('emitir').disabled = false;

            if (retorno.iStatus > 1) {
                alert(retorno.sMessage);
                // $('emitir').disabled = true;
            }
        }).execute();
    }

    verficaDeparaRecursos();

    $('emitir').observe('click', function (event) {
        try {
            if ( $F('competencia') == "" ) {
                throw "O campo Competência é de preenchimento obrigatório.";
            }

            if (instituicoes.length == 0) {
                throw "É necessário informar ao menos uma instituição.";
            }
        } catch (erro) {
            alert(erro);
            return false;
        }

        if(ultimaCompetenciaProcessada != null) {
            if($F('competencia') != proximaCompetenciaProcessada && $F('processamento') != 3) {
                var mensagem =  "Atenção: processar competências anteriores/posteriores pode gerar inconsistências.\n";
                mensagem += "A próxima competência indicada é " + proximaCompetenciaProcessada + ", você tem certeza que deseja processar a competência " + $F('competencia') + "?";

                if(!confirm(mensagem)){
                    return false;
                }
            }
        }

        var parametros = {
            'sExecucao' : 'processarMatriz',
            'competencia' : $F('competencia'),
            'encerrar' : $F('encerrar'),
            'processamento' : $F('processamento'),
            'instituicoes' : instituicoes,
            'encerramento' : $('encerramento').value
        };

        var ajaxRequest = new AjaxRequest(RPC, parametros, function (retorno, erro) {
            if (retorno.iStatus == 2) {
                alert(retorno.sMessage);
                return;
            }

            if (retorno.filePath) {
                var oDownload = new DBDownload();
                oDownload.addFile(retorno.filePath, 'SICONFI Matriz de saldos Contabeis.csv');
                oDownload.show();
            }

            if (retorno.filePath == "" && $F('processamento') != 2) {
                alert("Nenhum registro processado. Verifique a configuração das contas e os lançamentos da competência indicada.");
                return false;
            }

            alert(retorno.sMessage);
            carregaCompetencia();
        });

        ajaxRequest.execute();
    });

</script>
