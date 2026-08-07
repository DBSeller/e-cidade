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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");
require_once modification("dbforms/db_funcoes.php");
$rh01_regist      = '';
$matricula        = null;
$lTrazerSugestoes = false;
db_postmemory($_GET);

?>
<html>
<head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("object.js");
    db_app::load("Input/DBInput.widget.js, DBInputHora.widget.js, Input/DBInputCep.widget.js,Input/DBInputCNPJ.js,Input/DBInputCpf.widget.js,Input/DBInputDate.widget.js");
    db_app::load("Input/DBInputInteger.widget.js, Input/DBInputTelefone.widget.js,Input/DBInputValor.widget.js");
    db_app::load("Input/DBInputCheckboxRadio.widget.js, Input/DBCheckBox.widget.js,Input/DBRadio.widget.js,Collection.widget.js");
    db_app::load("avaliacao/DBViewFormulario.classe.js, avaliacao/DBViewGrupoPerguntas.classe.js,avaliacao/DBViewPergunta.classe.js,avaliacao/DBViewResposta.classe.js,avaliacao/DBViewResposta.classe.js,awesomplete.js,avaliacao/DBAutoComplete.js,classes/eSocial/DBAutoCompleteEsocial.js, avaliacao/DBViewRespostaNula.classe.js");
    db_app::load("AjaxRequest.js,estilos.css,grid.style.css,avaliacao.css,awesomplete.css");

    db_postmemory($_POST);


    if (!empty($rh01_regist)) {
        $matricula = $rh01_regist;
    }

    if (!isset($semVinculo)) {
        $semVinculo = false;
    }

    ?>
    <style>
        .controle {
            width: 80px;
        }

        #anterior {
            margin-left: 2px;
            float: left;
        }

        #proximo {
            margin-right: 2px;
            float: right;
        }

        .db-tooltip {
            display: none;
        }

        td, th{
            text-align: center;
            border: solid #ccc 1px;
        }

        th {
            background-color: #b5afaf;
        }

        #table-rubricas{
            width: 100%;
        }

        .input-buttons{
            display: inline-block;
        }
    </style>
</head>
<body>

<form class="container" style="width: 800px;">
    <?php
    if (empty($matricula)) {
        $lTrazerSugestoes = true;
        ?>
        <fieldset >
            <legend ><label for="matricula" > Escolha sua Matrícula </label ></legend>
            <select id = 'matricula' style="width:100%" onchange="buscarAvaliacao(Event)">
            </select >
        </fieldset >
        <?php
    } else {
        $lTrazerSugestoes = true;
        ?>
        <input type="hidden" id="matricula" name="matricula" value="<?=$matricula?>" />
        <input type="hidden" id='preenchimento' value='' />
        <input type="hidden" id='cgm'/>
        <?php
    }
    ?>
    <fieldset>
        <legend>Formulário de Cadastro para o eSocial</legend>
        <div id="questionario"></div>
    </fieldset>
    <input type="button" id="anterior" name="anterior" value="Anterior" class="controle" />
    <input type="button" id="excluir" name="excluir" value="Excluir" class="controle"/>
    <input type="button" id="limpar"   name="limpar"   value="Limpar"   class="controle" />
    <input type="button" id="salvar"   name="salvar"   value="Salvar"   class="controle" />
    <input type="button" id="pesquisar" name="Pesquisar" value="Pesquisar" class="controle"/>
    <input type="button" id="proximo"  name="proximo"  value="Próximo"  class="controle" />
    <input type="hidden" id="sem_vinculo"  name="sem_vinculo"  value="<?php echo $semVinculo?>" />
    <form>

        <?php
        if(!isset($iframe) || !$iframe) {
            db_menu();
        }
        ?>

        <script>
            viewAvaliacao          = '';
            var iMatriculaAnterior = '';
            var rubricasFormulario = '';
            var rubricasFormularioValores = '';
            (function(){

                try {


                    instituicao = <?=db_getsession("DB_instit")?>;
                    var parametros = {'exec' : 'buscaCgmEmpregador', 'instituicao' : instituicao, 'matricula' : $F('matricula')};

                    new AjaxRequest('eso4_trabalhadorsemvinculotermino.RPC.php', parametros, function (retorno) {
                        if (retorno.erro) {
                            alert(retorno.mensagem);
                            window.location = 'eso4_trabalhadorsemvinculotermino001.php';
                            return;
                        }

                        $('cgm').value = retorno.cgm;
                        buscarAvaliacao();
                    }).setMessage('Buscando empregador.').execute();

                } catch (e) {
                    alert(e);
                }
            })();

            function buscarAvaliacao(Event) {

                if(Event) {
                    if(!confirmaSaida("Se você alterar a matrícula os dados que não foram salvos serão perdidos.\nTem certeza que deseja trocar de matrícula?")) {
                        $('matricula').value = iMatriculaAnterior;
                        return false;
                    }
                }

                removeEventoBotoes();
                iMatriculaAnterior = $F('matricula');
                $('questionario').innerHTML = '';
                var iMatricula =  $F('matricula');


                var oDados  = {};
                oDados.exec            = 'buscarAvaliacao';
                oDados.trazerSugestoes = true;
                oDados.semVinculo      = $F('sem_vinculo');
                oDados.cgm      = $F('cgm');
                oDados.preenchimento      = $F('preenchimento');


                if (!empty(iMatricula)) {
                    oDados.iMatricula = iMatricula;
                }

                var oAjaxRequest = new AjaxRequest('eso4_trabalhadorsemvinculotermino.RPC.php', oDados, montarAvaliacao);
                oAjaxRequest.setMessage('Buscando dados da avaliação...');
                oAjaxRequest.execute();
            }

            function montarAvaliacao(oResponse, lErro) {

                if (lErro) {
                    alert(oResponse.mensagem);
                }

                if (oResponse.preenchimento) {
                    $('preenchimento').value = oResponse.preenchimento;
                }

                viewAvaliacao   = DBViewFormulario.makeFromObject(oResponse.oFormulario)
                    .setSugestao(oResponse.sugestao)
                    .setEvent('changeStep', controlarBotoes)
                    .show($('questionario'))
                ;

                function removeRubricasInputs(el){
                    if(!confirm('Deseja mesmo remover esta rubrica?')) return;
                    el.target.parentNode
                    .parentElement
                    .parentElement.remove();
                }
                
                viewAvaliacao.grupos.itens.each(function(grupo){
                    // console.log(grupo.identificador_campo)
                    if (grupo.identificador_campo === 'termino_rubricas') {
                        grupo.perguntas.itens.each(function (pergunta) {

                            jsonValueElement = pergunta.elemento.childNodes[1];

                            rubricasHeader = ['Rubrica', 'Quantidade', 'Valor', 'Identificador', 'Tipo', 'Ação']
                            tableRubricas = document.createElement('table')
                            tableRubricas.id = 'table-rubricas';
                            head = tableRubricas.createTHead();
                            row = head.insertRow();

                            rubricasHeader.forEach(function(el){
                            th = document.createElement('th')
                            th.innerText = el
                            row.appendChild(th)
                            })

                            rubricaTBody = tableRubricas.createTBody();
                            jsonValueElement.appendChild(tableRubricas)
                            
                            if (pergunta.identificador_campo === 'desligamento_rubricas_json') {
                                // pergunta.elemento.down("input").readOnly = true;
                                pergunta.elemento.down("input").style = ';background-color: #EDEDED;display:none';
                                
                                rubricasFormularioValores = pergunta.elemento.down("input");
                                
                                const itemRubrica = pergunta.respostas.itens[0];
                                valoresRubricas = JSON.parse(itemRubrica.valor)
                                
                                
                                inputCounter = 0;
                                valoresRubricas.each(function(rubrica){
                                    
                                    rowBody = rubricaTBody.insertRow();
                                    rowBody.id = 'wrap-'+rubrica.codrubr;

                                    const inputsParaRemover = [
                                    'fatorrubr',
                                    'vrunit',
                                    'identificador_grupo',
                                    ]
                                    
                                    Object.keys(rubrica).forEach(function (key) {

                                    if(!inputsParaRemover.includes(key)){
                                        input = document.createElement('input')
                                        input.setAttribute('type', 'text')
                                        input.setAttribute('id', 'valor-'+key+inputCounter)
                                        input.setAttribute('name', 'valor-'+key+inputCounter);
                                        input.setAttribute('class', 'input-rubrica');
                                        input.setAttribute('campo-json', key);
                                        input.value = rubrica[key];
                                        
                                        const labelName = document.createElement('label');
                                        labelName.innerText = key;

                                        td = document.createElement('td')
                                        td.appendChild(input)
                                        rowBody.appendChild(td)
                                    }
                                    })
                                    inputCounter++;

                                    removeInputButton = document.createElement('div');
                                    removeInputButton.setAttribute('class', 'input-buttons');
                                    removeInputButton.innerHTML = '<i class="fas fa-trash"></i>';
                                    removeInputButton.addEventListener('click', removeRubricasInputs)
                                    
                                    td = document.createElement('td')
                                    td.appendChild(removeInputButton)
                                    rowBody.appendChild(td)
                                
                                })
                                
                                addInputButton = document.createElement('div');
                                addInputButton.style.display = 'inline-block';
                                addInputButton.style.borderRadius = '100px';
                                addInputButton.style.padding = '5px';
                                addInputButton.style.backgroundColor = '#90c49f';
                                addInputButton.innerHTML = '<i class="fas fa-plus"></i>';
                                jsonValueElement.appendChild(addInputButton)
                            }////
                            rubricasFormulario = rubricaTBody; 
                        });
                    }
                });

                var inputCounter = 100;
                addInputButton.onclick = function(){

                    newRowBody = rubricaTBody.insertRow();
                    newRowBody.id = 'wrap-'+inputCounter;

                    ["codrubr", "qtdrubr", "vrrubr", "idetabrubr", "tiprubr"].forEach(element => {
                    input = document.createElement('input')
                    input.setAttribute('type', 'text')
                    input.setAttribute('id', 'valor-'+element+inputCounter)
                    input.setAttribute('name', 'valor-'+element+inputCounter);
                    input.setAttribute('class', 'input-rubrica');
                    input.setAttribute('campo-json', element);

                    td = document.createElement('td')
                    td.appendChild(input)

                    newRowBody.appendChild(td)
                    });
                    inputCounter++;


                    removeInputButton = document.createElement('div');
                    removeInputButton.setAttribute('class', 'input-buttons')
                    removeInputButton.innerHTML = '<i class="fas fa-trash"></i>';
                    removeInputButton.addEventListener('click', removeRubricasInputs)
                    
                    td = document.createElement('td')
                    td.appendChild(removeInputButton)
                    
                    newRowBody.appendChild(td)
                }

                $('proximo').observe('click', function() {

                    this.blur();
                    viewAvaliacao.avancarGrupo();
                });

                $('anterior').observe('click', function() {
                    viewAvaliacao.recurarGrupo();
                });


                $('salvar').observe('click', function() {
                    salvarQuestionario(viewAvaliacao);
                });
                $('excluir').observe('click', function () {
                    excluir(oResponse.preenchimento);
                });
                $('limpar').observe('click', function() {

                    if (viewAvaliacao.getStatus().grupoAtual) {
                        viewAvaliacao.getStatus().grupoAtual.limparRespostas();
                    }
                });

                $('pesquisar').observe('click', function () {
                    window.location.href='eso4_trabalhadorsemvinculotermino001.php';
                });
            }

            function montarJsonRubricas(rubricasFormulario) {
                const rubricasJsonList = [];
                const emptyList = [];
                rubricasFormulario.childNodes.forEach(tableRow => {
                    rubricaObj = {}; 
                    tableRow.childNodes.forEach(tableData => {
                    if(tableData.children[0].tagName === 'INPUT'){
                        tableInput = tableData.children[0];

                        if(tableInput.value === ''){
                        emptyList.push(tableInput.getAttribute('campo-json'))
                        return false;
                        }
                        
                        rubricaObj[tableInput.getAttribute('campo-json')] = tableInput.value
                    }
                    });

                    rubricasJsonList.push(rubricaObj);
                });

                rubricasJsonFiltradoList = rubricasJsonList.filter(obj=>{
                    objVazio = (Object.keys(obj).length === 0 && obj.constructor === Object);
                    objCompleto = Object.keys(obj).length === 5;
                    if(!objVazio && objCompleto){
                    return obj;
                    }
                    
                })
                
                return emptyList.length > 0 ? false : JSON.stringify(rubricasJsonFiltradoList);
            }
            
            function salvarQuestionario(viewAvaliacao, iCodigoGrupo) {

                if(! viewAvaliacao.getStatus().grupoAtual.isValido()) {
                    alert("Há informações obrigatórias inconsistentes.\nVerifique.");
                    return false;
                }

                rubricasJsonConvertido = montarJsonRubricas(rubricasFormulario);

                
                if(!rubricasJsonConvertido){ 
                    alert('Todos os campos de Rubricas precisam ser preenchidos!');
                    return;
                }
                
                rubricasFormularioValores.value = rubricasJsonConvertido; 
                
                // console.log('resultado conversao', rubricasJsonConvertido)
                // console.log('form via tabela', rubricasFormulario)
                // console.log('salvando')
                // console.log('para salvar', rubricasFormularioValores.value)
                // return;

                preenchimento = $('preenchimento').value;
                var lRetorno = true;
                var oAjaxRequest = new AjaxRequest(
                    'eso4_trabalhadorsemvinculotermino.RPC.php',
                    {
                        exec                  : 'salvarAvaliacao',
                        iMatricula            : iMatriculaAnterior,
                        iCodigoAvaliacao      : viewAvaliacao.codigo,
                        iCodigoGrupoPerguntas : iCodigoGrupo,
                        iCodigoPreenchimento  : preenchimento,
                        lSemVinculo           : $F('sem_vinculo'),
                        cgmEmpregador         : $F('cgm'),
                        aPerguntasRespostas   : viewAvaliacao.getDados(iCodigoGrupo)
                    },

                    function(oResponse, lErro){

                        if (!iCodigoGrupo || lErro) {
                            alert(oResponse.mensagem);
                        }
                        if (lErro) {
                            lRetorno = false;
                            return ;
                        }

                        $('preenchimento').value = oResponse.preenchimento;
                        viewAvaliacao.avancarGrupo();
                    }
                );

                oAjaxRequest.setMessage('Salvando dados da avaliação...');
                oAjaxRequest.execute();
                return lRetorno;
            }

            var controlarBotoes = function(event) {
                DBAutoCompleteEsocial.gerarAutoComplete();

                var status = this.getStatus();

                $('proximo').disabled  = true;
                $('anterior').disabled = true;
                $('salvar').disabled   = true;

                if (status.grupoPosterior) {
                    $('proximo').disabled = false;
                }

                if (status.grupoAnterior) {
                    $('anterior').disabled = false;
                }

                if (status.grupoAtual) {
                    $('salvar').disabled = false;
                }
            };



            function removeEventoBotoes() {

                $('salvar').stopObserving('click');
                $('proximo').stopObserving('click');
                $('anterior').stopObserving('click');
            }

            function confirmaSaida (sMensagem) {

                if(typeof sMensagem == 'undefined' || sMensagem == null || sMensagem == false) {
                    sMensagem = 'Você está saindo do cadastro do eSocial.\nAntes de sair, salve seus dados.';
                }

                if (!confirm(sMensagem)) {
                    return false;
                }
                return true;
            }

            if (parent.windowLiberacao) {
                parent.windowLiberacao.setShutDownFunction(function() {
                    if(!confirmaSaida()) {
                        return false;
                    }
                    parent.windowLiberacao.destroy();
                });
            }

            /**
             * Exclui o dados da Resposta
             */
            function excluir(id) {

                if (empty(id)) {
                    alert('Nenhum desligamento foi selecionado.');
                    return false;
                }

                if (!confirm('Confirma a exclusão do desligamento do servidor sem vínculo?')) {
                    return;
                }
                var request = {
                    exec: 'remover',
                    formulario: viewAvaliacao.codigo,
                    codigo_resposta: id
                };

                new AjaxRequest('eso4_trabalhadorsemvinculotermino.RPC.php', request, function (response, erro) {

                    alert(response.mensagem);
                    if (erro) {
                        return;
                    }

                    window.location.href='eso4_trabalhadorsemvinculotermino001.php';

                    $('limpar').click();
                    iCodigoRespostaFormulario = '';
                }).setMessage('Aguarde, removendo resposta ...').execute();
            }
        </script>
</body>
</html>
