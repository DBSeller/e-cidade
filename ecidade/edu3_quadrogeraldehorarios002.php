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

include(modification("libs/db_stdlibwebseller.php"));
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_regenciahorario_classe.php"));
include(modification("classes/db_periodoescola_classe.php"));
include(modification("classes/db_escola_classe.php"));
include(modification("classes/db_turma_classe.php"));
include(modification("classes/db_turmaturnoadicional_classe.php"));
include(modification("classes/db_diasemana_classe.php"));

?>

<!doctype html>
<html lang="pt-BR">
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link type="text/css" href="assets/bootstrap-table/css/bootstrap.min.css" rel="stylesheet"/>
    <link type="text/css" href="assets/bootstrap-table/bootstrap-table.min.css" rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <?php
        db_app::load("scripts.js, strings.js, arrays.js");
        db_app::load("estilos.css");
    ?>
    <style>
        .regente {
            font-size: 10px;
            font-style: italic;
        }

        .ausencia {
            font-size: 10px;
            color: darkorange;
        }
    </style>
</head>
<body class="body-default">
<table id="data-table"
       data-detail-view="true"
       data-virtual-scroll="true"
       class="table table-sm"
       data-id-field="id"
       data-search="true">
    <thead>
    <tr>
        <th data-field="escolas">Escolas</th>
    </tr>
    </thead>
</table>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="assets/jquery/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/bootstrap-table.min.js"></script>
<script type="text/javascript" src="assets/bootstrap-table/locale/bootstrap-table-pt-BR.min.js"></script>
<script type="text/javascript" src='extension/package/Desktop/assets/vendors/alertify/alertify.js'></script>
<script>
    (() => {
        var vinculo = parent.document.getElementById('vinculo').value,
            escola = parent.document.getElementById('escola').value,
            selectDisciplina = parent.document.getElementById('disciplina'),
            disciplina = selectDisciplina.value,
            disciplinaDescricao = selectDisciplina[selectDisciplina.selectedIndex].text,
            etapa = parent.document.getElementById('etapa').value,
            dia = parent.document.getElementById('dia').value,
            turno = (new URL(window.location.href)).searchParams.get('aCodigoTurno'),
            periodo = parent.document.getElementById('periodo').value,
            funcionario = parent.document.getElementById('funcionario').value,
            table = $('#data-table');

        const formData = new FormData();
        formData.append('acao', 'getEscolasByFiltro');
        formData.append('iVinculo', vinculo);
        formData.append('iEscola', escola);
        formData.append('iDisciplina', disciplina);
        formData.append('iEtapa', etapa);
        formData.append('iDia', dia);
        formData.append('aCodigoTurno', turno);
        formData.append('iPeriodo', periodo);
        formData.append('iFuncionario', funcionario);

        HttpClient.post('edu_quadrogeraldehorarios.RPC.php', {body: formData}).then((response) => {
            listaEscolas(table, 1, response.dados, 'Escolas')
        })

        function buscaResultado(index, row, $detail) {
            const formData = new FormData();
            formData.append('acao', 'getDadosQuadroGeralDeHorarios');
            formData.append('iVinculo', vinculo);
            formData.append('iEscola', row.codigo);
            formData.append('iDisciplina', disciplina);
            formData.append('iEtapa', etapa);
            formData.append('iDia', dia);
            formData.append('aCodigoTurno', turno);
            formData.append('iPeriodo', periodo);
            formData.append('iFuncionario', funcionario);
            HttpClient.post('edu_quadrogeraldehorarios.RPC.php', {body: formData}).then((response) => {
                let dados = ''

                if (empty(response.mensagem)) {
                    dados = response.dados
                }

                expandTable($detail, dados, 'Turmas')
            });
        }

        function listaEscolas($el, cells, dados, title) {
            let i;
            let row
            let columns = []
            let data = []

            for (i = 0; i < cells; i++) {
                columns.push({
                    field: 'escola',
                    title: title,
                    sortable: false
                })
            }

            for (i = 0; i < dados.length; i++) {
                row = {}
                row['codigo'] = dados[i].codigo_escola
                row['escola'] = dados[i].nome_escola
                data.push(row)
            }

            $el.bootstrapTable({
                columns: columns,
                data: data,
                checkboxHeader: true,
                detailView: cells > 0,
                onExpandRow: buscaResultado
            })
        }

        function montaTabelaTurmas($el, dados) {
            var i;
            var row
            var columns = []
            var data = []

            const formatarHorarioDisponivel = (value, row, index) => {
                if (value !== undefined) {
                    return value;
                }

                return `<i class='fas fa-check' style='color: #00de00'></i>`
                        + `<br><b style='color: #00de00'>HORÁRIO DISPONÍVEL</b>`

            };

            columns.push({
                field: 'Dias',
                title: `<i class='fas fa-calendar-alt'></i>  Dias`,
                valign: 'middle',
                align: 'center',
                width: 50,
                sortable: false
            })

            columns.push({
                field: 'Horários',
                title: `<i class='far fa-clock'></i> Horários`,
                align: 'center',
                valign: 'middle',
                width: 100,
                sortable: false
            })

            /* Cria array de turmas e turnos */
            const turmas = dados.map(turma => {
                return oTurma = {
                    turma: turma.sTurma,
                    turno: turma.sTurno
                }
            });

            /* Ordena array de turmas e turnos */
            turmas.sort((a, b) => {
                let x = a.turno + a.turma;
                let y = b.turno + b.turma;
                return x.toLowerCase().localeCompare(y.toLowerCase());
            });

            /* Distinct no array de turmas e turnos */
            let flags = [], turmasOrdenadasPorTurno = [], l = turmas.length, count;
            for (count = 0; count < l; count++) {
                if (flags[`${turmas[count].turma} - ${turmas[count].turno}`]) continue;
                flags[`${turmas[count].turma} - ${turmas[count].turno}`] = true;
                turmasOrdenadasPorTurno.push(`${turmas[count].turma} - ${turmas[count].turno}`);
            }

            turmasOrdenadasPorTurno.map((turma) => {
                columns.push({
                    field: turma,
                    title: `<i class='fas fa-users'></i> ${turma}`,
                    align: 'center',
                    valign: 'middle',
                    sortable: false,
                    formatter: formatarHorarioDisponivel
                })
            })

            /* PEGAMOS O ARRAY DE PERIODOS E DIAS E DAMOS UM NEW SET NELE PARA DAR UM DISTINCT, APÓS ISSO PERCORREMOS
            E IMPRIMIMOS NA NOSSA TABELA */
            const periodos = [...new Set(dados.map(x => x.sPeriodo))];
            const dias = [...new Set(dados.map(x => x.sDia))];

            dias.map((dia) => {
                periodos.map((periodo) => {
                    row = {};
                    row['Dias'] = `<b>${dia}</b>`
                    row['Horários'] = `<b>${periodo}</b>`
                    dados.map((dado) => {
                        if (dado.sDia == dia && dado.sPeriodo == periodo) {
                            row[`${dado.sTurma} - ${dado.sTurno}`] = imprimeResultadoQuadro(
                                dado.sDisciplina,
                                dado.iRegente,
                                dado.sRegente,
                                dado.iMatricula,
                                dado.iTipoHora,
                                dado.sTipoHora,
                                dado.lAusenteHoje,
                                dado.dAusenciaInicio,
                                dado.dAusenciaFinal,
                                dado.sSubstituto,
                                dado.dSubstitutoInicio,
                                dado.dSubstitutoFinal
                            );
                        }
                    })
                    data.push(row)
                })
            })
            /* IMPRIME A TABELA */
            $el.bootstrapTable({
                columns: columns,
                data: data,
                detailView: dados > 0,
            })

            /* DA ROWSPAN NOS DIAS DA SEMANA PARA ALINHAR AOS PERIODOS */
            const rowspan = periodos.length;
            let index = 0;

            for (i = 0; i < columns.length; i++) {
                $el.bootstrapTable('mergeCells', {
                    index: index,
                    field: 'Dias',
                    rowspan: rowspan,
                    colspan: 1
                })
                index = index + rowspan
            }

        }

        /* EXPANDE AS TABELAS DOS RESULTADOS */
        function expandTable($detail, dados, title) {
            montaTabelaTurmas($detail.html('<table></table>').find('table'), dados, title)
        }

        function imprimeResultadoQuadro(
            disciplina,
            codigoRegente,
            regente,
            matricula,
            tipohoracodigo,
            tipohora,
            ausencia,
            ausenciaInicio,
            ausenciaFinal,
            substituto,
            substituoInicio,
            substitutoFinal
        ) {
            let isTrueAusencia = (ausencia === 't');
            let isTrueSubstituto = !empty(substituto);

            tipoDeHora = '';

            if (tipohora == 'H') {
                tipoDeHora = `<br><b style='color: #2b669a'>HN</b>`
            } else {
                tipoDeHora = `<br><b style='color: #2b669a'>${tipohora}</b>`
            }

            let sAusencia = '';
            let sAusenciaMarcador = '';
            if (isTrueAusencia) {
                if (isTrueSubstituto) {
                    let sAusenciaFinal = empty(ausenciaFinal) ? 'em aberto' : ausenciaFinal;
                    let sSubstitutoFinal = empty(substitutoFinal) ? 'em aberto' : substitutoFinal;

                    sAusencia = `Professor Ausente (${ausenciaInicio} - ${sAusenciaFinal}) \n`
                        + `Substituto: ${substituto} (${substituoInicio} - ${sSubstitutoFinal})`;

                    sAusenciaMarcador = `<i class="fas fa-info-circle ausencia"></i>`;
                } else {
                    let sAusenciaFinal = empty(ausenciaFinal) ? 'em aberto' : ausenciaFinal;
                    sAusencia = `Professor Ausente (${ausenciaInicio} - ${sAusenciaFinal})`
                    sAusenciaMarcador = `<i style="" class="fas fa-info-circle ausencia"></i>`;
                }
            }

            if ((tipohoracodigo != vinculo && vinculo != 0)
                || (codigoRegente != funcionario
                    && funcionario != 0
                    && funcionario != ''
                    && funcionario != 1)
                || (vinculo == 0
                    && funcionario == 1
                    && regente != '')
                || (disciplinaDescricao != disciplina
                    && selectDisciplina.value != ''
                    && selectDisciplina.value != 0
                )
            ) {
                return `<i class='far fa-calendar-alt' style='color: #0000CC; font-size: 12px'></i>`
                    + `<br><b style='color: #0000CC; font-size: 10px;'>HORÁRIO PREENCHIDO</b>`
            }

            if (regente == '') {
                return "<b style='font-size: 10px;'>"
                    + disciplina
                    + "</b> <br>"
                    + "<i style='color: red;font-size: 10px'>"
                    + 'DISCIPLINA SEM REGENTE'
                    + "</i>"
            }
            if (disciplina == "LINGUAGENS") {
                return `<b style='font-size: 10px; color: red'>`
                    + disciplina
                    + `</b> <br>`
                    + `<span class='regente' style='color: red' title='${sAusencia}'>`
                    + `${regente} ${sAusenciaMarcador}`
                    + `<br>`
                    + `<b>MATR.(${matricula})</b>`
                    + `</span>`
                    + tipoDeHora
            }
            if (disciplina == "MATEMATICA") {
                return `<b style='font-size: 10px; color: blue'>`
                    + disciplina
                    + `</b> <br>`
                    + `<span class='regente' style='color: blue' title='${sAusencia}'>`
                    + `${regente} ${sAusenciaMarcador}`
                    + `<br>`
                    + `<b>MATR.(${matricula})</b>`
                    + "</span>"
                    + tipoDeHora
            }
            if (disciplina == "CIÊNCIAS HUMANAS E DA NATUREZA") {
                return `<b style='font-size: 10px; color: blueviolet'>`
                    + disciplina
                    + `</b> <br>`
                    + `<span class='regente' style="color: blueviolet" title='${sAusencia}'>`
                    + `${regente} ${sAusenciaMarcador}`
                    + `<br>`
                    + `<b>MATR.(${matricula})</b>`
                    + `</span>`
                    + tipoDeHora
            }
            if (disciplina == "EDUCACAO FISICA") {
                return `<b style='font-size: 10px; color: dodgerblue'>`
                    + disciplina
                    + `</b> <br>`
                    + `<span class='regente' style='color: dodgerblue' title='${sAusencia}'>`
                    + `${regente} ${sAusenciaMarcador}`
                    + `<br>`
                    + `<b>MATR.(${matricula})</b>`
                    + `</span>`
                    + tipoDeHora
            }
            if (disciplina == "CIENCIAS") {
                return `<b style='font-size: 10px; color: burlywood'>`
                    + disciplina
                    + `</b> <br>`
                    + `<span class='regente' style='color: burlywood' title='${sAusencia}'>`
                    + `${regente} ${sAusenciaMarcador}`
                    + `<br>`
                    + `<b>MATR.(${matricula})</b>`
                    + `</span>`
                    + tipoDeHora
            }
            if (disciplina == "GEOGRAFIA") {
                return `<b style='font-size: 10px;color: deeppink'>`
                    + disciplina
                    + `</b> <br>`
                    + `<span class='regente' style='color: deeppink' title='${sAusencia}'>`
                    + `${regente} ${sAusenciaMarcador}`
                    + `<br>`
                    + `<b>MATR.(${matricula})</b>`
                    + `</span>`
                    + tipoDeHora
            }
            if (disciplina == "LINGUA PORTUGUESA") {
                return `<b style='font-size: 10px;color: #0c5460'>`
                    + disciplina
                    + `</b> <br>`
                    + `<span class='regente' style='color: #0c5460' title='${sAusencia}'>`
                    + `${regente} ${sAusenciaMarcador}`
                    + `<br>`
                    + `<b>MATR.(${matricula})</b>`
                    + `</span>`
                    + tipoDeHora
            }
            if (disciplina == "EDUCACAO INFANTIL") {
                return `<b style='font-size: 10px;color: #09e7b8'>`
                    + disciplina
                    + `</b> <br>`
                    + `<span class='regente' style='color: #09e7b8' title='${sAusencia}'>`
                    + `${regente} ${sAusenciaMarcador}`
                    + `<br>`
                    + `<b>MATR.(${matricula})</b>`
                    + `</span>`
                    + tipoDeHora
            } else {
                return `<b style='font-size: 10px'>`
                    + disciplina
                    + `</b> <br>`
                    + `<span class='regente' title='${sAusencia}'>`
                    + `${regente} ${sAusenciaMarcador}`
                    + `<br>`
                    + `<b>MATR.(${matricula})</b>`
                    + `</span>`
                    + tipoDeHora
            }
        }
    })()
</script>
</body>
</html>


