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
require(modification("libs/db_utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="ISO-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script src="scripts/classes/http/http.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        .container .row {
            text-align: left !important;
            display: block;
            clear: both;
        }

        .col {
            float: left;
            padding: 0 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <form action="" id="formGerarSigapFiscal">
        <fieldset>
            <legend>Gerar SIGAP Fiscal - Xml</legend>
            <div class="row">
                <div class="col">
                    <b>Período: </b>
                    <select name="periodo" id="periodo">
                        <option selected disabled value="">Selecione um periodo</option>
                        <option value="6">1º BIMESTRE</option>
                        <option value="7">2º BIMESTRE</option>
                        <option value="8">3º BIMESTRE</option>
                        <option value="9">4º BIMESTRE</option>
                        <option value="10">5º BIMESTRE</option>
                        <option value="11">6º BIMESTRE</option>
                    </select>
                    <b> Código TCE: </b>
                    <input type="text" name="codigoTCE" id="codigoTCE" size="7">
                </div>
                <div class="col text-right">
                    <input type="button" value="Selecionar todos" id="marcarTodos">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <h4>RREO</h4>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREOBalancoOrcamentario" id="RREOBalancoOrcamentario" disabled>
                        <label for="RREOBalancoOrcamentario">Balanco Orçamentário</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREOBalancoFuncao" id="RREOBalancoFuncao" disabled>
                        <label for="RREOBalancoFuncao">Balanço Função</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREOReceitaCorrenteLiquida" id="RREOReceitaCorrenteLiquida" disabled>
                        <label for="RREOReceitaCorrenteLiquida">Receita Corrente Liquida</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREODespesaReceitaRPPS" id="RREODespesaReceitaRPPS" disabled>
                        <label for="RREODespesaReceitaRPPS">Despesa e Receita RPPS</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREOResultadoPrimarioNominal" id="RREOResultadoPrimarioNominal" disabled>
                        <label for="RREOResultadoPrimarioNominal">Resultado Primário e Nominal</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREORestosPagar" id="RREORestosPagar" disabled>
                        <label for="RREORestosPagar">Restos a Pagar</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREOReceitasDespesasMDE" id="RREOReceitasDespesasMDE" disabled>
                        <label for="RREOReceitasDespesasMDE">Receitas e Despesas MDE</label>
                    </div>
                    <div class="row hide" >
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="anual"
                               value="RREOOperacoesCreditoDespesasCapital" id="RREOOperacoesCreditoDespesasCapital" disabled>
                        <label for="RREOOperacoesCreditoDespesasCapital">Operações de Crédito e Despesas de
                            Capital</label>
                    </div>
                    <div class="row hide">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="anual"
                               value="RREOProjecaoAtuarial" id="RREOProjecaoAtuarial" disabled>
                        <label for="RREOProjecaoAtuarial">Projeção Atuarial</label>
                    </div>
                    <div class="row hide">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="anual"
                               value="RREOAlienacaoAtivosAplicacaoRecursos" id="RREOAlienacaoAtivosAplicacaoRecursos" disabled>
                        <label for="RREOAlienacaoAtivosAplicacaoRecursos">Alienação de Ativos e Aplicação dos
                            Recursos</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREOReceitasDespesasSaude" id="RREOReceitasDespesasSaude" disabled>
                        <label for="RREOReceitasDespesasSaude">Receitas e Despesas com Saúde</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREOParticipacaoPublicaPrivada" id="RREOParticipacaoPublicaPrivada" disabled>
                        <label for="RREOParticipacaoPublicaPrivada">Participação Pública e Privada</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="bimestral"
                               value="RREODemonstrativoSimplificado" id="RREODemonstrativoSimplificado" disabled>
                        <label for="RREODemonstrativoSimplificado">Demonstrativo Simplificado</label>
                    </div>
                </div>
                <div class="col">
                    <h4>RGF</h4>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="quadrimestral"
                               value="RGFDespesaPessoalDetalhada" id="RGFDespesaPessoalDetalhada" disabled>
                        <label for="RGFDespesaPessoalDetalhada">Despesa com Pessoal Detalhada</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="quadrimestral"
                               value="RGFDividaConsolidadaLiquida" id="RGFDividaConsolidadaLiquida" disabled>
                        <label for="RGFDividaConsolidadaLiquida">Dívida Consolidada</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="quadrimestral"
                               value="RGFGarantiasContraGarantias" id="RGFGarantiasContraGarantias" disabled>
                        <label for="RGFGarantiasContraGarantias">Garantias e Contra Garantias</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="quadrimestral"
                               value="RGFOperacaoCredito" id="RGFOperacaoCredito" disabled>
                        <label for="RGFOperacaoCredito">Operação Crédito</label>
                    </div>
                    <div class="row hide">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="anual"
                               value="RGFDisponibilidadeCaixa" id="RGFDisponibilidadeCaixa" disabled>
                        <label for="RGFDisponibilidadeCaixa">Disponíbilidade de Caixa</label>
                    </div>
                    <div class="row">
                        <input type="checkbox" class="cboRelatorio" name="relatorios[]" data-periodo="quadrimestral"
                               value="RGFDemonstrativoSimplificado" id="RGFDemonstrativoSimplificado" disabled>
                        <label for="RGFDemonstrativoSimplificado">Demonstrativo Simplificado</label>
                    </div>
                </div>
            </div>
        </fieldset>
        <div class="row">
            <div class="container">
                <button type="button" id="gerarArquivo">
                    <i class="fa fa-file"></i> Gerar Arquivos
                </button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    const formulario = document.getElementById('formGerarSigapFiscal');
    const inputPeriodo = document.getElementById('periodo');
    const inputCodigoTCE = document.getElementById('codigoTCE');
    const btnMarcarTodos = document.getElementById('marcarTodos');
    const btnGerar = document.getElementById('gerarArquivo');
    const cboRelatorios = document.getElementsByName('relatorios[]');

    btnMarcarTodos.addEventListener('click', () => {
        const nomeBotao = btnMarcarTodos.getAttribute('value');
        const estado = nomeBotao === 'Selecionar todos';
        Array.from(cboRelatorios).forEach((cbo) => {
            cbo.checked = cbo.disabled ? false : estado;
        });
        btnMarcarTodos.value = estado ? 'Desmarcar todos' : 'Selecionar todos';
    });

    formulario.addEventListener('submit', (e) => { e.preventDefault(); });
    btnGerar.addEventListener('click', () => {
        if (inputPeriodo.value == '') {
            alert("Informe um Período.");
            return;
        }
        if (empty(inputCodigoTCE.value)) {
            alert("Informe o Código TCE.");
            return;
        }
        var count = 0;
        Array.from(cboRelatorios).forEach((cbo) => {
            if (cbo.checked) {
                count++;
            }
        });
        if (count == 0) {
            alert("Selecione um Relatório.");
            return;
        }
        const parametros = new FormData(formulario);
        parametros.append('acao', 'gerarFiscal');
        HttpClient.post('con4_gerarsigap_fiscal.RPC.php', {body: parametros}).then((response) => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            var download = new DBDownload();
            download.addFile(response.zip, "SIGAP.zip");
            response.arquivos.map((arquivo) => {
                download.addFile(arquivo.filePath, arquivo.fileName);
            });
            download.show();
        });
    });

    inputPeriodo.addEventListener('change', (event) => {
        var periodo = parseInt(event.target.value);
        var quadrimestral = ([7, 9, 11].indexOf(periodo)) >= 0 ? true : false;
        var semestral = ([8, 11].indexOf(periodo)) >= 0 ? true : false;
        var anual = ([11].indexOf(periodo)) >= 0 ? true : false;

        Array.from(cboRelatorios).map((cboRelatorio) => {
            const periodoRelatorio = cboRelatorio.getAttribute('data-periodo');

            cboRelatorio.disabled = false;
            if (!anual && periodoRelatorio == 'anual') {
                cboRelatorio.disabled = true;
                cboRelatorio.checked = false;
                return;
            }
            if (!quadrimestral && periodoRelatorio == 'quadrimestral') {
                cboRelatorio.disabled = true;
                cboRelatorio.checked = false;
                return;
            }
            if (!semestral && periodoRelatorio == 'semestral') {
                cboRelatorio.disabled = true;
                cboRelatorio.checked = false;
                return;
            }
        });
    });

    (function () {
        const parametros = new FormData(formulario);
        parametros.append('acao', 'validarInstituicao');
        HttpClient.post('con4_gerarsigap_fiscal.RPC.php', {body: parametros}).then((response) => {
            if (response.erro) {
                alert(response.mensagem);
                btnGerar.setAttribute('disabled', 'disabled');
                return;
            }
        });
    })();
</script>
</body>
</html>
