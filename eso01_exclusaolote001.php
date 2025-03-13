<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBselller Servicos de Informatica
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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_utils.php');
require_once modification('dbforms/db_funcoes.php');

//PLUGIN ENVIOESOCIAL - Usuários configurados
?>
<!doctype html>
<html lang="pt-BR">
    <head>
        <meta charset="iso-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DBSeller Serviços de Informática Ltda</title>
        <link href="estilos.css" rel="stylesheet" type="text/css">
        <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
        <script src="scripts/scripts.js" rel="script" type="text/javascript"></script>
        <script src="scripts/prototype.js" rel="script" type="text/javascript"></script>
        <script src="scripts/object.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInput.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/DBInputHora.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputCep.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputCNPJ.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputCpf.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputDate.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputInteger.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputTelefone.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputValor.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBInputCheckboxRadio.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBCheckBox.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Input/DBRadio.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/Collection.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/widgets/DBLancador.widget.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/avaliacao/DBViewFormulario.classe.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/avaliacao/DBViewGrupoPerguntas.classe.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/avaliacao/DBViewPergunta.classe.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/avaliacao/DBViewResposta.classe.js" rel="script" type="text/javascript"></script>
        <script src="scripts/AjaxRequest.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/http/http.js" rel="script" type="text/javascript"></script>
        <script src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js" rel="script"
            type="text/javascript"></script>
            <script src="scripts/classes/DBViewFormularioFolha/CaixaFolha.js" rel="script"
            type="text/javascript"></script>
        <style>
            #competenciaTr input[type="text"] {
                width: inherit;
            }
        </style>
    </head>
    <body>
        <form class="container">
            <fieldset>
                <legend>Exclusão de Eventos em lotes para o <span id="span_integracao"></span></legend>
                <table class='form-container'>
                    <tr id="tr_empregador" class="d-none">
                        <td>
                            <label for="empregador">Empregador:</label>
                        </td>
                        <td>
                            <select name="empregador" id="empregador"></select>
                        </td>
                    </tr>
                    <tr id="tr_contribuinte" class="d-none">
                        <td>
                            <label for="descricao">Contribuinte:</label>
                        </td>
                        <td>
                            <input type="text" id="descricao" name="descricao" class="readonly field-size-max" disabled>
                            <input type="hidden" id="contribuinte" name="contribuinte" class="readonly">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="arquivos">Arquivo:</label>
                        </td>
                        <td>
                            <select id="arquivos" name="arquivos" class="field-size-max">
                                <option value="">Selecione...</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="filtrosPeriodoApuracao" style="display:none;">
                        <td>
                            <label for="indicativoPeriodoApuracao">Indicativo de Período de Apuração:</label>
                        </td>
                        <td>
                            <select id="indicativoPeriodoApuracao" name="indicativoPeriodoApuracao" class="field-size-max">
                                <option value="">Selecione...</option>
                                <option value="1">Mensal</option>
                                <option value="2">Anual (13° salário)</option>
                            </select>
                        </td>
                    </tr>
                    <tr id="competenciaTr" style="display:none;">
                        <td id="labelCompetencia"></td>
                        <td id="formularioCompetencia"></td>
                    </tr>
                    <tr id="caixaTr" style="display:none;">
                        <td id="labelCaixa"></td>
                        <td id="formularioCaixa"></td>
                    </tr>
                </table>
            </fieldset>
            <input type="button" id="processarDadosForcados" value="Processamento de Dados">
            <div class="esocial-text" id="s1200">
                <br><br>
                <p style="text-align: left"><strong>Nota:</strong></p>
                <p style="text-align: left"><strong>A rotina excluirá os dados enviados do evento S-1200 de todos os funcionários </strong></p>
                <p style="text-align: left"><strong>que possuem rendimentos e rescisões/desligamento, na competência desejada.</strong></p>
            </div>
            <div class="esocial-text" id="s1210">
                <br><br>
                <p style="text-align: left"><strong>Nota:</strong></p>
                <p style="text-align: left"><strong>A rotina excluirá os dados enviados do evento S-1210 de todos os funcionários </strong></p>
                <p style="text-align: left"><strong>que possuem rendimentos e rescisões/desligamento, na competência desejada.</strong></p>
            </div>
            <div class="esocial-text" id="s2230">
                <br><br>
                <p style="text-align: left"><strong>Atenção! Nesta rotina estamos realizando o processamento dos dados/ eventos para exclusão de todos os S-2230 enviados ao eSocial.</strong></p>
                <p style="text-align: left"><strong>Após acessar rotina:</strong></p>
                <p style="text-align: left"><strong>DB:RECURSOSHUMANOS > eSocial > Procedimentos > Envio de eventos para o eSocial</strong></p>
            </div>
            <script rel="script" type="text/javascript">
                var text = '';

                (() => {
                    const EFD_REINF = '1';
                    const ESOCIAL = '2';
                    const urlParams = new URLSearchParams(window.location.search);
                    const integracao = urlParams.has('integracao') ? urlParams.get('integracao') : '2';
                    const trEmpregador = document.getElementById('tr_empregador');
                    const trContribuinte = document.getElementById('tr_contribuinte');
                    const selectEmpregador = document.getElementById('empregador');
                    const selectArquivos = document.getElementById('arquivos');
                    const inputDescricao = document.getElementById('descricao');
                    const inputContribuinte = document.getElementById('contribuinte');
                    const buttonProcessar = document.getElementById('processarDadosForcados');
                    const spanIntegracao = document.getElementById('span_integracao');
                    const competenciaTr = document.getElementById('competenciaTr');
                    const labelCompetencia = document.getElementById('labelCompetencia');
                    const formularioCompetencia = document.getElementById('formularioCompetencia');
                    const labelCaixa = document.getElementById('labelCaixa');
                    const formularioCaixa = document.getElementById('formularioCaixa');
                    const validar = () => {
                        return new Promise((resolve, reject) => {
                        if (integracao === EFD_REINF && inputContribuinte.value === '') {
                            return reject('O campo "Contribuinte" é obrigatório.');
                        }

                        if (integracao === ESOCIAL && selectEmpregador.value === '') {
                            return reject('O campo "Empregador" é obrigatório.');
                        }
                            resolve();
                        });
                    };

                    const clickButtonProcessar = () => {
                        if (selectArquivos.value === '') {
                            return alert('O campo "Arquivo" é obrigatório.');
                        }

                        validar().then(() => {
                            const formData = new FormData();
                            const parametros = {
                                exec: 'gerarCargaExclusao',
                                layout: selectArquivos.value,
                                ano: null,
                                mes: null,
                                anoCaixa: null,
                                mesCaixa: null
                            };

                            if (selectArquivos.value != '2230') {
                                parametros.indicativoPeriodoApuracao = $F('indicativoPeriodoApuracao');
                                parametros.ano = formularioCompetencia.querySelector("#ano").value;
                                parametros.mes = formularioCompetencia.querySelector("#mes").value;
                                parametros.anoCaixa = formularioCaixa.querySelector("#anoCaixa").value;
                                parametros.mesCaixa = formularioCaixa.querySelector("#mesCaixa").value;
                            }

                            if (integracao === EFD_REINF) {
                                parametros.cgm = inputContribuinte.value;
                            }

                            if (integracao === ESOCIAL) {
                                parametros.cgm = selectEmpregador.value;
                            }

                            formData.append('json', JSON.stringify(parametros));

                            HttpClient.post('eso4_esocialapi.RPC.php', {
                                body: formData
                            }).then(response => {
                                alert(response.sMessage);
                            });
                        }).catch(e => alert(e));
                    };

                    const buscarArquivos = () => {
                        const formData = new FormData();
                        const parametros = {
                            'exec': 'getTipos',
                            'exclusaoLote' : true,
                            'integracao': integracao
                        };

                        formData.append('json', JSON.stringify(parametros));

                        HttpClient.post('eso4_esocialapi.RPC.php', {
                            body: formData
                        }).then(response => {
                            if (response.erro) {
                                throw response.mensagem;
                            }

                            response.tipos.map(tipo => {
                                selectArquivos.add(new Option(tipo.titulo, tipo.layout));
                            });
                        }).catch(mensagem => alert(mensagem));
                    };

                    const adicionarListeners = () => {
                        buttonProcessar.addEventListener('click', clickButtonProcessar);
                    };

                    var dbViewFormularioFolha = new DBViewFormularioFolha.CompetenciaFolha(false);
                    dbViewFormularioFolha.renderizaLabel(labelCompetencia);
                    dbViewFormularioFolha.renderizaFormulario(formularioCompetencia);
                    var dbViewFormularioFolhaCaixa = new DBViewFormularioFolha.CaixaFolha(false);
                    dbViewFormularioFolhaCaixa.renderizaLabel(labelCaixa);
                    dbViewFormularioFolhaCaixa.renderizaFormulario(formularioCaixa);
                    selectArquivos.addEventListener('change', () => {
                        exibeClasse();
                    });
                    const inicializarFiltroCompetencia = exibeCompetencia => {
                        selectArquivos.addEventListener('change', () => {
                            if(exibeCompetencia.includes(parseInt(selectArquivos.value))) {
                                competenciaTr.style.display = "table-row";
                                $('indicativoPeriodoApuracao').value = "";
                                $('ano').value = '';
                                $('mes').value = '';

                                $('ano').removeAttribute('disabled');
                                $('mes').removeAttribute('disabled');
                            } else {
                                competenciaTr.style.display = "none";
                            }
                        });
                    };
                    const inicializarFiltroCaixa = exibeCaixa => {
                        selectArquivos.addEventListener('change', () => {
                            if(exibeCaixa.includes(parseInt(selectArquivos.value))) {
                                caixaTr.style.display = "table-row";
                                $('indicativoPeriodoApuracao').value = "";
                                $('anoCaixa').value = '';
                                $('mesCaixa').value = '';

                                $('anoCaixa').removeAttribute('disabled');
                                $('mesCaixa').removeAttribute('disabled');
                            } else {
                                caixaTr.style.display = "none";
                            }
                        });
                    };

                    const inicializarIndicativoPeriodoApuracao = (exibeIndicativoPeriodoApuracao) => {
                        selectArquivos.addEventListener("change", (event) => {
                            var filtrosPeriodoApuracao = document.getElementsByClassName('filtrosPeriodoApuracao');
                            const indexSelecionado = event.target.selectedIndex;
                            const valor = parseFloat(event.target.options[indexSelecionado].value);

                            if(exibeIndicativoPeriodoApuracao.indexOf(valor) > -1) {
                                filtrosPeriodoApuracao[0].style.display = "table-row";
                                $('ano').setAttribute('disabled', 'disabled');
                                $('mes').setAttribute('disabled', 'disabled');
                            } else {
                                filtrosPeriodoApuracao[0].style.display = "none";
                                $('indicativoPeriodoApuracao').value = '';
                            }
                        });
                    };

                    $('indicativoPeriodoApuracao').addEventListener("change", (event) => {
                        $('ano').setAttribute('disabled', 'disabled');
                        $('mes').setAttribute('disabled', 'disabled');
                        $('ano').value = '';
                        $('mes').value = '';
                        $('anoCaixa').setAttribute('disabled', 'disabled');
                        $('mesCaixa').setAttribute('disabled', 'disabled');
                        $('anoCaixa').value = '';
                        $('mesCaixa').value = '';
                        if ($F('indicativoPeriodoApuracao') == '') {
                            return;
                        }

                        $('ano').removeAttribute('disabled');
                        $('mes').removeAttribute('disabled');
                        //Periodo de apuração.
                        $('anoCaixa').removeAttribute('disabled');
                        $('mesCaixa').removeAttribute('disabled');
                    });

                    const inicializar = () => {
                        if (integracao === ESOCIAL) {
                            text = 'eSocial';
                            spanIntegracao.innerText = text;
                        }

                        if (integracao === EFD_REINF) {
                            text = 'EFD-Reinf';
                            spanIntegracao.innerText = text;
                        }

                        const formData = new FormData();
                        formData.append('acao', 'inicializar');
                        formData.append('integracao', integracao);
                        formData.append('exclusaoLote', true);

                        HttpClient.post('sped02_preenchimento.RPC.php', {
                            body: formData
                        }).then(response => {
                            if (response.erro) {
                                throw response.mensagem;
                            }
                            inicializarFiltroCompetencia(response.exibeCompetencia);
                            inicializarFiltroCaixa(response.exibeCaixa);
                            inicializarIndicativoPeriodoApuracao(response.exibeIndicativoPeriodoApuracao);

                            if (integracao === EFD_REINF) {
                                inputDescricao.defaultValue = response.contribuinte.descricao;
                                inputContribuinte.defaultValue = response.contribuinte.cgm.codigo;
                                trContribuinte.classList.remove('d-none');
                            }

                            if (integracao === ESOCIAL) {
                                response.empregadores.map((empregadorOption, chave) => {
                                    const selecionado = chave === 0;

                                    selectEmpregador.add(
                                        new Option(empregadorOption.nome, empregadorOption.cgm),
                                        selecionado,
                                        selecionado
                                    );
                                });
                                trEmpregador.classList.remove('d-none');
                            }
                            exibeClasse();
                        }).then(buscarArquivos).then(adicionarListeners).catch(mensagem => alert(mensagem));
                    };

                    function resetaClasses() {
                        var elementos = document.getElementsByClassName('esocial-text');
                        for(i = 0; i < elementos.length; i++) {
                            elementos[i].classList.remove('esocial-exibe');
                        }
                    }

                    function exibeClasse() {
                        var layout = selectArquivos.value;
                        resetaClasses();
                        if (layout) {
                            var elemento = document.getElementById('s' + layout);
                            elemento.classList.add('esocial-exibe');
                        }
                    }

                    inicializar();

                })();
            </script>
        </form>
        <style>
            div.esocial-text {
                display: none;
            }
            div.esocial-exibe{
                display: block;
            }
        </style>
    </body>
</html>
