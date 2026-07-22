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
    <script>
        const arquivos = {
            UnidadeOrcamentaria: {cod: 1, label: 'Unidade Orçamentária', anual: true},
            Programas: {cod: 2, label: 'Programas', anual: true},
            Acao: {cod: 3, label: 'Ação', anual: true},
            Dotacao: {cod: 4, label: 'Dotação', anual: true},
            AtualizacaoOrcamentaria: {cod: 5, label: 'Atualização Orcamentária', mensal: true, diario: true},
            DecretoseOficios: {cod: 6, label: 'Decretos e Ofícios', mensal: true, diario: true},
            ReceitaPrevista: {cod: 7, label: 'Receita Prevista', janeiro: true, anual: true},
            Empenhos: {cod: 8, label: 'Empenhos', diario: true, obrigatorio: true},
            Estorno: {cod: 9, label: 'Estornos', diario: true, obrigatorio: true},
            Liquidacao: {cod: 10, label: 'Liquidação', diario: true, obrigatorio: true},
            EstornoLiquidacao: {cod: 11, label: 'Estorno Liquidação', diario: true, obrigatorio: true},
            Pagamentos: {cod: 12, label: 'Pagamentos', diario: true, obrigatorio: true},
            EstornoPagamento: {cod: 13, label: 'Estorno Pagamento', diario: true, obrigatorio: true},
            Retencao: {cod: 14, label: 'Retenção', diario: true, obrigatorio: true},
            EstornoRetencao: {cod: 15, label: 'Estorno Retenção', diario: true, obrigatorio: true},
            ReceitaOrcamentaria: {cod: 16, label: 'Receita Orçamentária', mensal: true},
            TransfRecebida: {cod: 17, label: 'Transf. Recebida', mensal: true},
            TransfConcedida: {cod: 18, label: 'Transf. Concedida', mensal: true},
            ReceitaExtra: {cod: 19, label: 'Receita Extra', mensal: true},
            DespesaExtra: {cod: 20, label: 'Despesa Extra', mensal: true},
            EstornoReceitaExtra: {cod: 21, label: 'Estorno Receita Extra', mensal: true},
            EstornoDespesaExtra: {cod: 22, label: 'Estorno Despesa Extra', mensal: true},
            CadastroContaBancaria: {cod: 23, label: 'Cadastro Conta Bancária', diario: true},
            RelacionamentoCCorrenteFontePagadora: {cod: 24, label: 'Rel. CC com Fonte Pagadora', diario: true},
            SaldoInicial: {cod: 25, label: 'Saldo Inicial', janeiro: true},
            SaldoMensal: {cod: 26, label: 'Saldo Mensal', mensal: true},
            ConciliacaoBancaria: {cod: 27, label: 'Conciliação Bancária', mensal: true},
            PagamentosRestos: {cod: 28, label: 'Pagamentos dos Restos', mensal: true},
            EstornoPagamentoRestos: {cod: 29, label: 'Estorno Pagamentos dos Restos', mensal: true},
            CancelamentoRestos: {cod: 30, label: 'Cancelamento Pagamentos dos Restos', mensal: true},
            LiquidacaoRestos: {cod: 31, label: 'Liquidação Pagamentos dos Restos', mensal: true},
            EstornoLiquidacaoRestos: {cod: 32, label: 'Estorno Liquidação dos Restos', mensal: true},
            RetencaoRestos: {cod: 33, label: 'Retenção dos Restos', mensal: true},
            EstornoRetencaoRestos: {cod: 34, label: 'Estorno Retenção dos Restos', mensal: true},
            Fornecedores: {cod: 35, label: 'Fornecedores', diario: true},
            Ordenador: {cod: 36, label: 'Ordenador', diario: true},
            RelacionamentoEmpenhoObra: {cod: 37, label: 'Relacionamento Empenho Obra', mensal: true},
            RelacionamentoEmpenhoLicitacao: {cod: 38, label: 'Relacionamento Empenho Licitação', mensal: true},
            RelacionamentoLiquidacaoCodigoAgrupamentoFolhaPagamento: {cod: 39, label: 'Relacionamento Liquidações Código<br>Agrupamento Folha de Pagamento', mensal: true},
            RestosInscritos: {cod: 40, label: 'Restos a Pagar Inscritos', mensal: true},
            PloaAcao: {cod: 41, label: 'Ploa Ação', anual: true},
            PloaDotacao: {cod: 42, label: 'Ploa Dotação', anual: true},
            PloaPrograma: {cod: 43, label: 'Ploa Programa', anual: true},
            PloaReceitaPrevista: {cod: 44, label: 'Ploa Receita Prevista', anual: true},
            PloaUnidadeOrcamentaria: {cod: 45, label: 'Ploa Unidade Orçamentária', anual: true},
            RelacionamentoEmpenhoTipoMeta: {cod: 46, label: 'Relacionamento Empenho Tipo Meta', mensal: true},
            SaldoMensalCoConciliado: {cod: 47, label: 'Saldo Mensal CO Conciliados', mensal: true},

        }
    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        #files {
            display: flex;
        }
        .row {
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }
        #files.row {
            align-items: flex-start;
        }
        #files .row label {
            display: flex;
            align-items: center;
            text-align: initial;
        }
        #files .row label.disabled {
            color: #999;
        }
        #files h4 {
            margin-left: 5px;
            text-align: left;
        }
        .col {
            display: flex;
            flex-direction: column;
            padding: 0 10px;
        }
        #files.obrigatorio .col .row:not(.obrigatorio) {
            display: none;
        }
        .windowAux12 > div:nth-child(3) {
            overflow-y: auto;
        }
        #mes {
            height: 22px;
            padding-top: 0;
        }
        input[name="ano"] {
            width: 60px;
            height: 18px;
        }
    </style>
</head>
<body>
<div class="container">
    <form action="" id="formGerarSagres">
        <input name="codigoTCE" value="0" type="hidden">
        <fieldset style="width: 725px;">
            <br/>
            <legend>Gerar SAGRES</legend>
       
            <div class="row">
                    <b>Período: </b>&nbsp;
                    <select name="periodo" id="periodo">
                        <option selected disabled value="">Selecione um período</option>
                        <option value="anual">ANUAL</option>
                        <option value="diario">DIÁRIO</option>
                        <option value="janeiro">JANEIRO</option>
                        <option value="mensal">MENSAL</option>
                    </select>&nbsp;
                    <input type="button" value="Selecionar todos" id="marcarTodos">
                    <input id="obrigatorio" type="checkbox" onclick="toogleObrigatorio()">
                    <label for="obrigatorio">Obrigatórios</label>
            </div>

            <div class="row" style="margin-top: 2px">
                <strong style="padding-right: 12px">Data :&nbsp;&nbsp;</strong>
                <span id="ct_mensal" hidden>
                    <?php $result1=array("01"=>"Janeiro","02"=>"Fevereiro","03"=>"Março","04"=>"Abril","05"=>"Maio","06"=>"Junho","07"=>"Julho","08"=>"Agosto","09"=>"Setembro","10"=>"Outubro","11"=>"Novembro","12"=>"Dezembro");
                    db_select("mes",$result1,true,2);
                    ?>
                </span>
                <span id="ct_anual" hidden>
                    <input name="ano" type="number" value="<?php echo db_getsession("DB_anousu")?>">
                </span>
                <span id="ct_diario">
                    <?php db_inputdata('data', '', '', '', true, 'text', 1) ?>
                </span>
            </div>
            
            <div class="row" style="margin-top: 2px">
                <b>Formatos:</b>
                <input name="txt" type="checkbox">
                <label for="txt">TXT</label>

                <input name="xml" type="checkbox">
                <label for="xml">XML</label>

                <input name="csv" type="checkbox">
                <label for="csv">CSV</label>
            </div>

            <div id="files" class="row">
                <div id="col-0" class="col">
                    <h4>Arquivos</h4>
                </div>

                <div id="col-1" class="col">
                    <h4>&nbsp;</h4>
                </div>

                <div id="col-2" class="col">
                    <h4>&nbsp;</h4>
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

    function toogleObrigatorio() {
        var element = document.getElementById("files");
        element.classList.toggle("obrigatorio");
        selecionarPeriodo()
    }

    let countRow = 0;
    for (const file in arquivos) {
        let i = countRow++ % 3;

        const files = document.querySelector(`#col-${i}`);
        let row = document.createElement('div');
        let input = document.createElement('input');
        let label = document.createElement('label');

        row.classList.add('row');

        if(arquivos[file].obrigatorio) {
            row.classList.add('obrigatorio');
        }

        input.setAttribute('type', 'checkbox');
        input.setAttribute('name', 'relatorios[]');
        input.setAttribute('value', file);
        input.setAttribute('id', file);
        input.setAttribute('disabled', 'disabled');
        input.classList.add('cboRelatorio');

        label.setAttribute('for', file);
        label.classList.add('disabled');
        label.innerHTML = arquivos[file].label;

        row.append(input);
        row.append(label);
        files.appendChild(row);
    }

    const formulario = document.getElementById('formGerarSagres');
    const inputPeriodo = document.getElementById('periodo');
    const btnMarcarTodos = document.getElementById('marcarTodos');
    const btnGerar = document.getElementById('gerarArquivo');
    const cboRelatorios = document.getElementsByName('relatorios[]');

    btnMarcarTodos.addEventListener('click', () => {
        const nomeBotao = btnMarcarTodos.getAttribute('value');
        const estado = nomeBotao === 'Selecionar todos';
        Array.from(cboRelatorios).forEach((cbo) => {
            cbo.checked = cbo.disabled ? false : estado;
            const relatorio = cbo.getAttribute('id');

            if (document.getElementById("obrigatorio").checked) {
               if (!!arquivos[relatorio]['obrigatorio']) {
                  cbo.checked = cbo.disabled ? false : estado;
               }else{
                  cbo.checked = false;
               }
            }
        });
        btnMarcarTodos.value = estado ? 'Desmarcar todos' : 'Selecionar todos';
    });

    formulario.addEventListener('submit', (e) => { e.preventDefault(); });

    btnGerar.addEventListener('click', () => {
        if (inputPeriodo.value == '') {
            alert("Informe um Período.");
            return;
        }
        if(inputPeriodo.value == 'diario' && $F(data) == '') {
            alert("Informe uma data.");
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
        parametros.append('exec', 'gerarSagres');
        HttpClient.post(
            'con4_sagres.RPC.php',
            { body: parametros }
        )
        .then((response) => {
            if (response.erro) {
                alert(response.message);
                return;
            }

            var download = new DBDownload();
            download.addFile(response.zip, "Sagres.zip");
            response.arquivos.map((arquivo) => {
                download.addFile(arquivo.filePath, arquivo.fileName);
            });
            download.show();
        });
    });

    inputPeriodo.addEventListener('change', selecionarPeriodo);
   
    const showPeriodo = (periodo) => ({
        'mensal': () => {
            document.querySelector('#ct_diario').setAttribute('hidden','hidden');
            document.querySelector('#ct_mensal').removeAttribute('hidden');
            document.querySelector('#ct_anual').removeAttribute('hidden');
        },
        'anual': () => {
            document.querySelector('#ct_diario').setAttribute('hidden','hidden');
            document.querySelector('#ct_mensal').setAttribute('hidden','hidden');
            document.querySelector('#ct_anual').removeAttribute('hidden');
        },
        'janeiro': () => {
            document.querySelector('#ct_diario').setAttribute('hidden','hidden');
            document.querySelector('#ct_mensal').setAttribute('hidden','hidden');
            document.querySelector('#ct_anual').removeAttribute('hidden');
        },
    }[periodo] || function () {
        document.querySelector('#ct_diario').removeAttribute('hidden');
        document.querySelector('#ct_mensal').setAttribute('hidden','hidden');
        document.querySelector('#ct_anual').setAttribute('hidden','hidden');
    });

    function selecionarPeriodo() {
        const event = document.getElementById("periodo");
        const periodo = event.value;
        btnMarcarTodos.value = 'Selecionar todos';
        
        showPeriodo(periodo)();

        Array.from(cboRelatorios).map((cboRelatorio) => {
            const relatorio = cboRelatorio.getAttribute('id');
            const label = document.querySelector(`label[for="${relatorio}"]`);

            cboRelatorio.disabled = true;
            cboRelatorio.checked = false;
            label.classList.add('disabled');

            if (!!arquivos[relatorio][periodo]) {
                cboRelatorio.disabled = false;
                label.classList.remove('disabled');
                return;
            }
        });
    }

</script>
</body>
</html>
