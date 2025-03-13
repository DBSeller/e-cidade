<?php
/*
 *  E-cidade Software Publico para Gestao Municipal
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once(modification("classes/db_cgm_classe.php"));

$clcgm    = new cl_cgm;
$clcgm->rotulo->label();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <style>
        #informacoes_complementares {
            width: 100%
        }
    </style>
</head>
<body>
<div id='ctnAbaEmissao' class='subcontainer' style="margin-top: 20px; width: 450px;">
    <form name="form1" id="form1">
        <fieldset>
            <legend>Formulário de Retenções IN 1234/2012</legend>
            <table class="form-container">
                <tr>
                    <td id="ctnInstituicao" colspan="4" style="font-weight: normal" class="field-size-max">
                        <input type="hidden" id="db_selinstit" value="">
                    </td>
                </tr>
            </table>
            <table class="form-container">
                    <tr>
                        <td>Data Inicial:</td>
                        <td><input id="dataInicio" name="dataInicio" type="text"/></td>
                        <td>Data Final:</td>
                        <td><input id="dataFinal" name="dataFinal" type="text"/></td>
                    </tr>
            </table>
            <fieldset class="separator">
                <table class="form-container">
                    <tr>
                        <td style="width: 30px;">Apuração de Rendimentos:</td>
                        <td>
                            &nbsp;
                            <select name="apuracaoRendimentos" id="apuracaoRendimentos">
                                <option value="1">Total dos Rendimentos</option>
                                <option value="2">Apenas os Rendimentos com Retenção</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset class="separator">
            <table class="form-container">
                <tr>
                    <td  align="left" nowrap title="<?=$Tz01_numcgm?>">
                        <label for="z01_numcgm"><a href="" id="labelCredor">Credor:</a></label>
                    </td>
                    <td align="left" nowrap>
                        <?php
                            db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 4);
                            db_input("z01_nome", 30, "", true, "text", 3);
                        ?>
                    </td>
                </tr>
            </table>
            </fieldset>
            <fieldset class="separator">
                <table class="form-container">
                    <tr>
                        <td style="width: 30px;">Tipo:</td>
                        <td>
                            &nbsp;
                            <select name="tipoRelatorio" id="tipoRelatorio">
                                <option value="sintetico">Sintético</option>
                                <option value="analitico">Analítico</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </fieldset>
            <fieldset class="separator" id="secao_informacoes_complementares">
                <table class="form-container">
                    <tr>
                        <td colspan="2">Informações Complementares:</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php db_textarea('informacoes_complementares', 5, 70, 0, true, 'text', 2); ?>
                        </td>
                    </tr>
                </table>
            </fieldset>

        </fieldset>
    </form>
    <button id="emitir" type="button">
        <i class="fas fa-print"></i>
        Emitir
    </button>
</div>

</body>
<?php db_menu() ?>
<script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
<script type="text/javascript">

    const btnEmitir = document.getElementById('emitir');
    const formulario = document.getElementById('form1');
    const campoCGM = document.getElementById('z01_numcgm');
    const tipoRelatorio = document.getElementById('tipoRelatorio');
    const apuracaoRendimentos = document.getElementById('apuracaoRendimentos');
    const informacoesComplementares = document.getElementById('informacoes_complementares');
    const inputDataInicio = new DBInputDate(document.getElementById('dataInicio'));
    const campoDataInicio = document.getElementById('dataInicio');
    const inputDataFinal = new DBInputDate(document.getElementById('dataFinal'));
    const campoDataFinal = document.getElementById('dataFinal');
    const dataHoje = new Date();

    inputDataInicio.setValue(`${dataHoje.getUTCFullYear()}-01-01`);
    inputDataFinal.setValue(dataHoje.toLocaleString());

    const validarInputs = () => {
        try {

            if (campoCGM.value == '') {
                throw 'Informe o número do Credor.';
            }

            if (inputDataInicio.value == null || inputDataFinal.value == null) {
                throw 'A data inicial e final devem estar preenchidas.';
            }

            if (inputDataInicio.value.getUTCFullYear() != inputDataFinal.value.getUTCFullYear()) {
                throw 'As datas devem estar dentro do mesmo exercício.';
            }

            if (js_comparadata(inputDataInicio.inputElement.value, inputDataFinal.inputElement.value, '>')) {
                throw 'Data de inicio deve ser menor que a data final.';
            }
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    }

    const atualizaParametrosLookupCredor = (lookup) => {
        lookup.setParametrosAdicionais(['tipo_apuracao='+$F('apuracaoRendimentos'), 'data_inicial='+js_formatar(inputDataInicio.inputElement.value,'d'), 'data_final='+js_formatar(inputDataFinal.inputElement.value,'d')]);
    }

    (function() {

        var lookupCredor = new DBLookUp($('labelCredor'), $('z01_numcgm'), $('z01_nome'), {
            'sArquivo': 'func_cgm_empenho_retencoes_in12342012.php',
            'sObjetoLookUp': 'db_iframe_nome_credor_in1324',
            'sLabel': '',
            'aParametrosAdicionais': null
        });

        apuracaoRendimentos.addEventListener('change', () => {
            atualizaParametrosLookupCredor(lookupCredor);
        });

        atualizaParametrosLookupCredor(lookupCredor);

        btnEmitir.addEventListener('click', () => {

            if (!validarInputs()) {
                return;
            }

            var nomeRelatorio = "emp2_formularioretencoesin12342012s_002.php";
            if (tipoRelatorio.value == 'analitico') {
                nomeRelatorio = "emp2_formularioretencoesin12342012a_002.php";
            }

            jan = window.open(
                    nomeRelatorio+'?cgm='+campoCGM.value+
                    '&data_inicio='+js_formatar(inputDataInicio.inputElement.value,'d')+
                    '&data_final='+js_formatar(inputDataFinal.inputElement.value,'d')+
                    '&apuracao='+apuracaoRendimentos.value+
                    '&informacoes_complementares='+encodeURIComponent(informacoesComplementares.value),
                    '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            jan.moveTo(0,0);
        });

        campoDataInicio.addEventListener('change', () => {
            atualizaParametrosLookupCredor(lookupCredor);
        });

        campoDataFinal.addEventListener('change', () => {
            atualizaParametrosLookupCredor(lookupCredor);
        });

        campoDataInicio.addEventListener('blur', () => {
            atualizaParametrosLookupCredor(lookupCredor);
        });

        campoDataFinal.addEventListener('blur', () => {
            atualizaParametrosLookupCredor(lookupCredor);
        });
    })();
</script>
