<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2009  DBSeller Servicos de Informatica
*                    www.dbseller.com.br
*                 e-cidade@dbseller.com.br
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

require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));

require_once(modification("libs/db_utils.php"));

try {
  
  if (empty($_POST['z01_numcgm'])) {
    throw new Exception("CGM não informado.");
  }
  if (empty($_POST['ano'])) {
    throw new Exception("Ano não informado.");
  }

  if (empty($_POST['mes'])) {
    throw new Exception("Mês não informado.");
  }

  $z01_numcgm = $_POST['z01_numcgm'];
  $ano = $_POST['ano'];
  $mes = $_POST['mes'];

  $avaliacaogruporesposta = null;
  $daoRemuneracaoRGPS = new cl_avaliacaogruporespostaremuneracaorgps();
  $where = "eso28_cgm = {$z01_numcgm} AND eso28_ano = {$ano} AND eso28_mes = {$mes}";
  $sql = $daoRemuneracaoRGPS->sql_query_file(null, 'eso28_avaliacaogruporesposta', null, $where);
  $rs = db_query($sql);

  if (!$rs) {
    throw new Exception("Erro ao buscar as respostas do formulário para o CGM {$z01_numcgm}.");
  }

  if (pg_num_rows($rs) > 0) {
    $avaliacaogruporesposta = db_utils::fieldsMemory($rs, 0)->eso28_avaliacaogruporesposta;
  }

} catch (Exception $e) {
  db_msgbox($e->getMessage());
  db_redireciona("eso4_regimegeralprevidencia001.php");
}



?>
<style>

    input {
        text-overflow: ellipsis;
    }

</style>

<html>
<head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <?php
        db_app::load("estilos.css, grid.style.css, avaliacao.css");
        db_app::load("prototype.js, scripts.js, strings.js, widgets/Collection.widget.js, datagrid.widget.js,  widgets/DatagridCollection.widget.js, DBAbas.widget.js, Input/DBInput.widget.js");
        db_app::load("avaliacao/DBViewGrupoPerguntas.classe.js, avaliacao/DBViewPergunta.classe.js, avaliacao/DBViewResposta.classe.js, avaliacao/DBViewRespostaNula.classe.js");
        db_app::load("avaliacao/DBViewFormulario.classe.js, Input/DBInputCheckboxRadio.widget.js, Input/DBCheckBox.widget.js, Input/DBRadio.widget.js, classes/http/http.js");
    ?>
    <style>
        .container.abas {
            min-width: 800px;
        }

        #ctnAbas {
            border: 1px solid #b1b1b1;
        }

        .field-size2{
            width: 83px !important;
        }
        .field-size4 {
            width: 169px !important;
        }
        .field-size6 {
            width: 255px !important;
        }
        .field-size8 {
            width: 341px !important;
        }
        .container:not([rel="ignore-css"]) {
            margin-top: 10px;
        }
    </style>
</head>
<body>
	<center id="divGeral">
        <?php db_input('z01_numcgm',7,"",true,'hidden'); ?>
        <?php db_input('mes',7,"",true,'hidden'); ?>
        <?php db_input('ano',7,"",true,'hidden'); ?>
        <?php db_input('avaliacaogruporesposta', 7,"",true,'hidden'); ?>
        <div class='container abas'>
            <div id='ctnAbas'></div>
            <div id="ctnAbaDadosFuncionario" style="visibility:hidden;">
                <?php require_once(modification("eso3_regimegeralprevidenciadadosfuncionario001.php")); ?>
            </div>
            <!-- TODO - IMPEDIMENTO POR HORA, DEPENDE DA TAREFA DE PAGAMENTOS ANTERIORES -->
            <!-- <div id="ctnAbaRemuneracaoAnterior" style="visibility:hidden;"> -->
                <!-- < require_once(modification("eso3_regimegeralprevidenciaremuneracaoanterior001.php")); ?> -->
            <!-- </div> -->
            <div id="ctnAbaPlanoSaude" style="visibility:hidden;">
                <?php require_once(modification("eso3_regimegeralprevidenciaplanosaude001.php")); ?>
            </div>
            <div id="ctnAbaPagamentos" style="visibility:hidden;">
                <?php require_once(modification("eso3_regimegeralprevidenciapagamentos001.php")); ?>
            </div>
            <div id="ctnAbaOutrosVinculos" style="visibility:hidden;">
                <?php require_once(modification("eso3_regimegeralprevidenciaoutrosvinculos001.php")); ?>
            </div>
            <div id="ctnAbaFormularioPreenchimento" style="visibility:hidden;">
                <!-- <iframe src="sped02_preenchimento.php?integracao=2&formularioTipo=22"></iframe> -->
                <?php require_once(modification("eso1_preenchimentoregimegeralprevidencia001.php")); ?>
            </div>
        </div>
        <div class="container">
          <input type="button" name="btnPesquisarCGM" id="btnPesquisarCGM" value="Pesquisar CGM">
        </div>
    </center>
</body>
<script>
    const
        oDBAba                    = new DBAbas($('ctnAbas')),
        oAbaDadosFuncionario      = oDBAba.adicionarAba('Dados do Funcionário', $('ctnAbaDadosFuncionario')),
        // oAbaRemuneracaoAnterior   = oDBAba.adicionarAba('Remuneração Anterior', $('ctnAbaRemuneracaoAnterior')),
        oAbaPlanoSaude            = oDBAba.adicionarAba('Plano de Saúde', $('ctnAbaPlanoSaude')),
        oAbaPagamentos            = oDBAba.adicionarAba('Pagamentos', $('ctnAbaPagamentos')),
        oAbaHorariosFuncionamento = oDBAba.adicionarAba('Outros Vínculos', $('ctnAbaOutrosVinculos')),
        oAbaHorariosAula          = oDBAba.adicionarAba('Dados de Processo', $('ctnAbaFormularioPreenchimento')),
        divGeral                  = $("divGeral"),
        formPlanoSaude            = $("formPlanoSaude"),
        formOutrosVinculos        = $("formOutrosVinculos"),
        formDadosFuncionario      = $("formDadosFuncionario"),
        formPagamentos            = $("formPagamentos"),
        arrTextoTipoContribuicao  = getArrTextoTipoContribuicao(),
        cgmServidor               = divGeral.querySelector("#z01_numcgm").value;


    $('ctnAbaDadosFuncionario').style.visibility = '';
    // $('ctnAbaRemuneracaoAnterior').style.visibility = '';
    $('ctnAbaPlanoSaude').style.visibility = '';
    $('ctnAbaPagamentos').style.visibility = '';
    $('ctnAbaOutrosVinculos').style.visibility = '';
    $('ctnAbaFormularioPreenchimento').style.visibility = '';
    (function () {
        carregar();
    })();
    function carregar() {
        const
            rpc = 'eso4_remuneracaorgps.RPC.php',
            formData = new FormData(),
            objData = {};
        objData.executa = 'buscarDadosCGM';
        objData.cgm = cgmServidor;
        objData.mes = $('mes').value;
        objData.ano = $('ano').value;

        formData.append('json',  JSON.stringify(objData));

        return fetch(rpc, {
            method: 'POST',
            body: formData,
        }).then(response => {
            js_removeObj('loading_message');
            return response;
        }).then(response => response.json()).then(response => {
            if (!!response.erro) {
                return alert(response.mensagem);
            }
            formDadosFuncionario.innerHTML = "";

            /**
             * Dados do Funcionário
             */
            var dadosTrabalhador = response.matriculas[0].dadosTrabalhador;
            if (dadosTrabalhador) {
                const
                    fieldSetTrabalhador = document.createElement("fieldset"),
                    legendTrabalhador   = document.createElement("legend"),
                    tableTrabalhador    = document.createElement("table"),
                    tbodyTrabalhador    = document.createElement('tbody'),
                    inputNomeTrabalhador = criaInput({
                        type : "text",
                        id : "nomeTrabalhador",
                        name: "nomeTrabalhador",
                        class: "field-size8",
                        value: dadosTrabalhador.nome,
                        disabled: true
                    }),
                    inputCpfTrabalhador = criaInput({
                        type : "text",
                        id : "cpfTrabalhador",
                        name: "cpfTrabalhador",
                        class: "field-size4",
                        value: js_formatar(dadosTrabalhador.cpf, 'cpfcnpj'),
                        disabled: true
                    }),
                    inputNisTrabalhador = criaInput({
                        type : "text",
                        id : "nisTrabalhador",
                        name: "nisTrabalhador",
                        class: "field-size4",
                        value: dadosTrabalhador.nis,
                        disabled: true
                    });

                var arrTdTrabalhador = [];

                tableTrabalhador.classList.add('form-container');
                legendTrabalhador.appendChild(document.createTextNode(`CGM: ${cgmServidor}`));
                fieldSetTrabalhador.appendChild(legendTrabalhador);
                fieldSetTrabalhador.appendChild(tableTrabalhador);

                tableTrabalhador.appendChild(
                    criaTr([
                        criaTdLabel("nomeTrabalhador", "Nome:"),
                        criaTdInputs([inputNomeTrabalhador])
                    ])
                );

                tableTrabalhador.appendChild(
                    criaTr([
                        criaTdLabel("cpfTrabalhador", "CPF:"),
                        criaTdInputs([inputCpfTrabalhador])
                    ])
                );

                tableTrabalhador.appendChild(
                    criaTr([
                        criaTdLabel("nisTrabalhador", "NIS:"),
                        criaTdInputs([inputNisTrabalhador])
                    ])
                );

                formDadosFuncionario.appendChild(fieldSetTrabalhador);
            }

            /**
             * Outros Vínculos
             */
            formOutrosVinculos.innerHTML = "";

            /**
             * Plano de Saúde
             */
            formPlanoSaude.innerHTML = "";


            formPagamentos.innerHTML = "";

            var possuiPlanoSaude = false,
                possuiOutrosVinculos = false,
                possuiPagamentos = false;

            response.matriculas.each(function(matricula) {

              if(matricula.outrosVinculos.length > 0) {

                  possuiOutrosVinculos = true;
                  for(const arrOutroVinculo of matricula.outrosVinculos){
                      if(arrOutroVinculo.length > 0){
                          const outroVinculo = arrOutroVinculo[0];
                              fieldSet = document.createElement('fieldset'),
                              legend   = document.createElement('legend'),
                              table    = document.createElement('table'),
                              tbody    = document.createElement('tbody'),
                              gridVinculos    = document.createElement('div'),
                              tdGrid          = document.createElement('td'),
                              referenciaIndex = `_${matricula.matricula}`,
                              inputTipoContribuicao = criaInput({
                                  type : "text",
                                  id : `tipoContribuicao${referenciaIndex}`,
                                  name: `tipoContribuicao${referenciaIndex}`,
                                  lang: "tipoContribuicao",
                                  class: "field-size2",
                                  value: outroVinculo.tipoContribuicao,
                                  disabled: true
                              }),
                              inputNomeTipoContribuicao = criaInput({
                                  type : "text",
                                  id : `tipoContribuicaoText${referenciaIndex}`,
                                  name: `tipoContribuicaoText${referenciaIndex}`,
                                  lang: "tipoContribuicaoText",
                                  class: "field-size8",
                                  value: arrTextoTipoContribuicao[outroVinculo.tipoContribuicao],
                                  disabled: true
                              }),
                              collectionOutrosVinculos = new Collection().setId("sequencial"),
                              dataGridCollectionOutrosVinculo = DatagridCollection.create(collectionOutrosVinculos).configure({'order': false, 'height': '80px'});

                          var
                              arrTd = [],
                              arrInput = [];

                          table.classList.add('form-container');

                          arrInput.push(inputTipoContribuicao);
                          arrInput.push(inputNomeTipoContribuicao);
                          arrTd.push(criaTdLabel(`tipoContribuicao${referenciaIndex}`, "Tipo de Crontribuição:"));
                          arrTd.push(criaTdInputs(arrInput));

                          legend.appendChild(document.createTextNode(`Outro Vínculo - Matrícula ${outroVinculo.servidor.matricula}`));
                          fieldSet.appendChild(legend);
                          table.appendChild(criaTr(arrTd));
                          fieldSet.appendChild(table);

                          tdGrid.setAttribute('colspan', 2);
                          tdGrid.appendChild(gridVinculos);
                          gridVinculos.setAttribute('id', `gridVinculos${referenciaIndex}`);
                          table.appendChild(criaTr([tdGrid]));

                          formOutrosVinculos.appendChild(fieldSet);

                          dataGridCollectionOutrosVinculo.addColumn('sequencial', {'label': 'Sequencial'});
                          dataGridCollectionOutrosVinculo.addColumn('numeroInscricao', {'label': 'Número da Inscrição', 'width': '33%', 'align' : 'right'});
                          dataGridCollectionOutrosVinculo.addColumn('codigoCategoria', {'label': 'Código da Categoria', 'width': '33%', 'align' : 'right'});
                          dataGridCollectionOutrosVinculo.addColumn('valorRemuneracao', {'label': 'Valor Remuneração', 'width': '33%', 'align' : 'right'});
                          dataGridCollectionOutrosVinculo.hideColumns([0]);

                          arrOutroVinculo.forEach((vinculo) => {
                              collectionOutrosVinculos.add({
                                  sequencial: vinculo.sequencial,
                                  numeroInscricao: js_formatar(vinculo.numeroInscricao, 'cpfcnpj'),
                                  codigoCategoria: vinculo.codigoCategoria,
                                  valorRemuneracao: vinculo.valorRemuneracao,
                              });
                          });


                          dataGridCollectionOutrosVinculo.show(
                              formOutrosVinculos.querySelector(`#gridVinculos${referenciaIndex}`)
                          );

                      }
                  }
              }

              if(matricula.planosSaude.length > 0) {

                possuiPlanoSaude = true;
                matricula.planosSaude.forEach((planoSaudeMatricula, indexMatricula) => {
                  planoSaudeMatricula.forEach((planoSaude, index) => {
                  const
                    referenciaIndex = `_${indexMatricula+1}_${index+1}`,
                  fieldSet = document.createElement('fieldset'),
                  legend   = document.createElement('legend'),
                  table    = document.createElement('table'),
                  tbody    = document.createElement('tbody'),
                  gridDependentes = document.createElement('div'),
                  tdGrid          = document.createElement('td'),
                  inputCgmOperadora = criaInput({
                    type : "text",
                    id : `cgmOperadora${referenciaIndex}`,
                    name: `cgmOperadora${referenciaIndex}`,
                    class: "field-size2",
                    value: planoSaude.operadoraSaude.cgm.codigo,
                    disabled: true
                  }),
                  inputNomeOperadora = criaInput({
                    type : "text",
                    id : `nomeOperadora${referenciaIndex}`,
                    name: `nomeOperadora${referenciaIndex}`,
                    class: "field-size6",
                    value: planoSaude.operadoraSaude.cgm.nome,
                    disabled: true
                  }),
                  inputSequencialOperadora = criaInput({
                    type : "hidden",
                    id : `sequencialOperadora${referenciaIndex}`,
                    name: `sequencialOperadora${referenciaIndex}`,
                    value: planoSaude.operadoraSaude.sequencial
                  }),
                  inputAnsOperadora = criaInput({
                    type : "text",
                    id : `ansOperadora${referenciaIndex}`,
                    name: `ansOperadora${referenciaIndex}`,
                    class: "field-size2",
                    value: planoSaude.operadoraSaude.ans,
                    disabled: true
                  }),
                  inputRubrica = criaInput({
                    type : "text",
                    id : `rubrica${referenciaIndex}`,
                    name: `rubrica${referenciaIndex}`,
                    value: planoSaude.rubrica.codigo,
                    class: "field-size2",
                    disabled: true
                  }),
                  inputNomeRubrica = criaInput({
                    type : "text",
                    id : `nomeRubrica${referenciaIndex}`,
                    name: `nomeRubrica${referenciaIndex}`,
                    value: planoSaude.rubrica.descricao,
                    class: "field-size6",
                    disabled: true
                  }),
                  inputValor = criaInput({
                    type : "text",
                    id : `valor${referenciaIndex}`,
                    name: `valor${referenciaIndex}`,
                    value: planoSaude.valor,
                    class: "field-size2",
                    disabled: true
                  }),
                  collectionDependentes = new Collection().setId("sequencial"),
                  dataGridCollectionDependentes = DatagridCollection.create(collectionDependentes).configure({'order': false, 'height': '80px'});

                var
                  arrTd = [],
                  arrInput = [];

                table.classList.add('form-container');
                legend.appendChild(document.createTextNode(`Plano de Saúde - Matrícula do Servidor: ${planoSaude.servidor.matricula}`));
                arrInput.push(inputCgmOperadora);
                arrInput.push(inputNomeOperadora);
                arrInput.push(inputSequencialOperadora);

                arrTd.push(criaTdLabel(`cgmOperadora${referenciaIndex}`, "Operadora:"));
                arrTd.push(criaTdInputs(arrInput));
                arrTd.push(criaTdLabel(`ansOperadora${referenciaIndex}`, "ANS:"));
                arrTd.push(criaTdInputs([inputAnsOperadora]));
                table.appendChild(criaTr(arrTd));

                arrInput = [];
                arrTd = [];

                arrInput.push(inputRubrica);
                arrInput.push(inputNomeRubrica);

                arrTd.push(criaTdLabel(`rubrica${referenciaIndex}`, "Rubrica:"));
                arrTd.push(criaTdInputs(arrInput));
                table.appendChild(criaTr(arrTd));

                arrInput = [];
                arrTd = [];

                table.appendChild(
                  criaTr([
                    criaTdLabel(`valor${referenciaIndex}`, "Valor:"),
                    criaTdInputs([inputValor])
                  ])
                );


                tdGrid.setAttribute('colspan', 4);
                tdGrid.appendChild(gridDependentes);
                gridDependentes.setAttribute('id', `gridDependentes${referenciaIndex}`);
                table.appendChild(criaTr([tdGrid]));

                fieldSet.appendChild(legend);
                fieldSet.appendChild(table);

                formPlanoSaude.appendChild(fieldSet);

                dataGridCollectionDependentes.addColumn('nome', { label: 'Nome do Dependente', align: 'left', width: '200px' });
                dataGridCollectionDependentes.addColumn('nascimento', {label: 'Data de Nascimento', align: 'left', width: '120px' });
                dataGridCollectionDependentes.addColumn('cpf', { label: 'CPF', align: 'left', width: '120px'});
                dataGridCollectionDependentes.addColumn('valor', { label: 'Valor', align: 'right', width: '120px' }).transform('dinheiro');
                planoSaude.servidorOperadoraSaudeDependentes.forEach((dependentePlanoSaude) => {
                  collectionDependentes.add({
                  sequencial: dependentePlanoSaude.sequencial,
                  nome: dependentePlanoSaude.dependente.nome,
                  nascimento: dependentePlanoSaude.dependente.nascimento,
                  cpf: js_formatar(dependentePlanoSaude.dependente.cpf, 'cpfcnpj') || "",
                  valor: dependentePlanoSaude.valor
                });
              });
                dataGridCollectionDependentes.show(gridDependentes);

              });
              });
              }

              if(matricula.pagamentos.length > 0) {

                possuiPagamentos = true;
                const
                  fieldSet = document.createElement('fieldset'),
                  legend   = document.createElement('legend'),
                  table    = document.createElement('table'),
                  tbody    = document.createElement('tbody'),
                  gridPagamentos = document.createElement('div'),
                  tdGrid          = document.createElement('td');

                var
                  arrTd = [],
                  arrInput = [];

                table.classList.add('form-container');
                legend.appendChild(document.createTextNode(`Pagamentos - Matrícula ${matricula.matricula}`));

                tdGrid.setAttribute('colspan', 4);
                tdGrid.appendChild(gridPagamentos);
                gridPagamentos.setAttribute('id', `gridPagamentos${matricula.matricula}`);
                table.appendChild(criaTr([tdGrid]));

                fieldSet.appendChild(legend);
                fieldSet.appendChild(table);

                formPagamentos.appendChild(fieldSet);
                var collectionPagamentos      = new Collection().setId("codigo");
                var gridCollectionPagamentos  = new DatagridCollection(collectionPagamentos, 'gridCollectionPagamentos' + matricula.matricula).configure({'order': false, 'height': '200px'});
                gridCollectionPagamentos.addColumn('codigo', {
                    label: 'Rubrica',
                    align: 'left',
                    width: '340px'
                  }).transform((rubrica, linha) => {
                    return `${rubrica} - ${linha.descricao}`;
                });
                gridCollectionPagamentos.addColumn('quantidade', {
                  label: 'Quantidade',
                  align: 'right',
                  width: '80px'
                });
                gridCollectionPagamentos.addColumn('descricaoTipo', {
                  label: 'Tipo',
                  align: 'left',
                  width: '80px'
                });
                gridCollectionPagamentos.addColumn('valor', {
                  label: 'Valor',
                  align: 'right',
                  width: '120px'
                }).transform("dinheiro");
                gridCollectionPagamentos.addColumn('folha', {
                  label: 'Folha',
                  align: 'left',
                  width: '80px'
                });

                gridCollectionPagamentos.show(gridPagamentos);

                matricula.pagamentos.forEach((agrupamento, index) => {
                  var tipoPagamento = index == 0 ? "Salário" : "Complementar";
                  if(agrupamento) {
                    agrupamento.forEach((pagamento) => {
                      pagamento.folha = tipoPagamento;
                      collectionPagamentos.add(pagamento);
                    });
                  }
                })

                gridCollectionPagamentos.reload();

              }
            });

            if (!possuiPlanoSaude) {
              formPlanoSaude.innerHTML = "<h1>Sem Plano de Saúde</h1>";
            }
            if (!possuiOutrosVinculos) {
              formOutrosVinculos.innerHTML = "<h1>Sem Outros Vínculos</h1>";
            }
            if (!possuiPagamentos) {
              formPagamentos.innerHTML = "<h1>Sem Pagamentos</h1>";
            }
        });
    }

    function criaInput(objInput){
        const input = document.createElement('input');

        for(var key in objInput){
            if(objInput.hasOwnProperty(key)){
                if(key == 'disabled'){
                    input.disabled = objInput[key];
                } else {
                    input.setAttribute(key, objInput[key]);
                }
            }
        }

        return input;
    }

    function criaTdLabel(labelFor, labelText){
        const
            td = document.createElement('td'),
            label = document.createElement('label');

        label.appendChild(document.createTextNode(labelText));
        label.setAttribute('for', labelFor);
        td.appendChild(label);

        return td;
    }

    function criaTdInputs(arrInput){
        const td = document.createElement('td');
        for(var input of arrInput){
            td.appendChild(input);
            td.appendChild(document.createTextNode(" "));
        }
        return td;
    }

    function criaTr(arrTds){
        const tr = document.createElement('tr');
        for(var td of arrTds){
            tr.appendChild(td);
        }
        return tr;
    }

    function getArrTextoTipoContribuicao(){
        const arr = [
            "",
            "Contribuição descontada pelo primeiro empregador",
            "Contribuição descontada por outra(s) empresa(s) sobre valor inferior ao limite máximo do salário de contribuição",
            "Contribuição sobre o limite máximo de salário de contribuição já descontada em outra(s) empresa(s)"
        ];
        return arr;
    }

    $('btnPesquisarCGM').onclick = function() {
      location.href = "eso4_regimegeralprevidencia001.php";
    }
</script>
</html>
