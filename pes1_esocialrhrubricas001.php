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
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));
?>
<html>
<head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/dates.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        input:disabled:not([type=button]), select:disabled {
            background-color: #DEB887 !important;
            color: black !important;
        }
    </style>
</head>
<body class="body-default">
<div class="container">
    <form name="frmESocial" id="frmESocial">
        <fieldset>
            <legend>Informações</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="codigoRubrica">Rubrica:</label>
                    </td>
                    <td>
                        <input type="hidden" name="sequencial" id="sequencial">
                        <input type="hidden" name="instituicao" id="instituicao">
                        <input type="text" name="codigoRubrica" id="codigoRubrica" disabled="disabled"
                               style="width: 15%">
                        <input type="text" name="descricaoRubrica" id="descricaoRubrica" disabled="disabled"
                               style="width: 85%">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cbxIncidenciaPrevi">Incidência de Contrib. Previdenciária:</label>
                    </td>
                    <td>
                        <select id="cbxIncidenciaPrevi">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cbxIncidenciaIRRF">Incidência de IRRF:</label>
                    </td>
                    <td>
                        <select id="cbxIncidenciaIRRF">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cbxIncidenciaFGTS">Incidência de FGTS:</label>
                    </td>
                    <td>
                        <select id="cbxIncidenciaFGTS">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cbxNatureza">Natureza da Rubrica(Conforme tabela 3):</label>
                    </td>
                    <td>
                        <select id="cbxNatureza">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cbxSubCodigoTCE">SubCódigo TCE-RS:</label>
                    </td>
                    <td>
                        <select id="cbxSubCodigoTCE">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cbxIncidenciaRegimeProprio">Incidência da rubrica para RPPS/regime militar:</label>
                    </td>
                    <td>
                        <select id="cbxIncidenciaRegimeProprio">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="cbxIncidenciaTeto">Rubrica compõe o teto remuneratório específico (art. 37, XI, da CF/1988):</label>
                    </td>
                    <td>
                        <select id="cbxIncidenciaTeto">
                            <option value="">Selecione...</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td title="Data de início da validade das informações para o eSocial.">
                        <label for="dataInicial">Início de validade:</label>
                    </td>
                    <td>
                        <input id="dataInicial" name="dataInicial" type="text">
                    </td>
                </tr>
                <tr>
                    <td title="Data final da validade das informações para o eSocial.">
                        <label for="dataFinal">Fim de validade:</label>
                    </td>
                    <td>
                        <input id="dataFinal" name="dataFinal" type="text">
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" name="btnSalvar" id="btnSalvar" value="Salvar">
    </form>
</div>
</body>
</html>

<script type="text/javascript">
    const urlParams = new URLSearchParams(window.location.search);
    const urlRpc = 'pes1_esocialrhrubricas001.RPC.php';
    const dataInicial = new DBInputDate($('dataInicial'));
    const dataFinal = new DBInputDate($('dataFinal'));

    const comboTCE = document.getElementById('cbxSubCodigoTCE');
    const comboNatureza = document.getElementById('cbxNatureza');
    var opcoesSubGrupo;

    if (!urlParams.get('codigoRubrica')) {
        setFormReadOnly($('frmESocial'), true);
    }

    $('codigoRubrica').value = urlParams.get('codigoRubrica');
    $('descricaoRubrica').value = urlParams.get('descricaoRubrica');

    function buscar() {
        js_divCarregando('Aguarde, buscando informações...', 'loading_message');
        const formData = new FormData();
        formData.append('acao', 'buscar');
        formData.append('codigoRubrica', $F('codigoRubrica'));

        return fetch(urlRpc, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        }).then(response => {
            js_removeObj('loading_message');
            return response;
        }).then(response => response.json()).then(response => {
            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            opcoesSubGrupo = response.subgruposrubricas;

            response.opcoesCodIncCP.forEach(opcao => {
                $('cbxIncidenciaPrevi').add(new Option(opcao.label, opcao.value));
            });

            response.opcoesCodIncIRRF.forEach(opcao => {
                $('cbxIncidenciaIRRF').add(new Option(opcao.label, opcao.value));
            });

            response.opcoesCodIncFGTS.forEach(opcao => {
                $('cbxIncidenciaFGTS').add(new Option(opcao.label, opcao.value));
            });

            response.opcoesCodIncCPRP.forEach(opcao => {
                $('cbxIncidenciaRegimeProprio').add(new Option(opcao.label, opcao.value));
            });

            response.opcoesCodTetoRemun.forEach(opcao => {
                $('cbxIncidenciaTeto').add(new Option(opcao.label, opcao.value));
            });

            response.opcoesNatureza.forEach(opcao => {
                $('cbxNatureza').add(new Option(opcao.label, opcao.value));
            });

            $('sequencial').value = response.rubrica.sequencial;
            $('instituicao').value = response.rubrica.instituicao;
            $('cbxIncidenciaPrevi').value = (response.rubrica.codIncCP) ? response.rubrica.codIncCP : '';
            $('cbxIncidenciaIRRF').value = (response.rubrica.codIncIRRF) ? response.rubrica.codIncIRRF : '';
            $('cbxIncidenciaFGTS').value = (response.rubrica.codIncFGTS) ? response.rubrica.codIncFGTS : '';
            $('cbxIncidenciaRegimeProprio').value = (response.rubrica.codIncCPRP) ? response.rubrica.codIncCPRP : '';
            $('cbxIncidenciaTeto').value = (response.rubrica.codTetoRemun) ? response.rubrica.codTetoRemun : '';
            $('cbxNatureza').value = (response.rubrica.natureza) ? response.rubrica.natureza : '';

            if (response.rubrica.dataInicial) {
                dataInicial.setValue(response.rubrica.dataInicial);
            }

            if (response.rubrica.dataFinal) {
                dataFinal.setValue(response.rubrica.dataFinal);
            }

            atualizaSubGrupo(response.rubrica.subgrupotce);
        });
    }

    buscar();

    $('btnSalvar').onclick = function() {
        if (!validarFormulario()) {
            return false;
        }

        var rubrica = {
            sequencial: $F('sequencial'),
            rubrica: $F('codigoRubrica'),
            instituicao: $F('instituicao'),
            codIncCP: $F('cbxIncidenciaPrevi'),
            codIncIRRF: $F('cbxIncidenciaIRRF'),
            codIncFGTS: $F('cbxIncidenciaFGTS'),
            codTetoRemun: $F('cbxIncidenciaTeto'),
            codIncCPRP: $F('cbxIncidenciaRegimeProprio'),
            natureza: $F('cbxNatureza'),
            dataInicial: dataInicial.getValue(),
            dataFinal: dataFinal.getValue(),
            subgrupotce: comboTCE.value
        };

        js_divCarregando('Aguarde, salvando informações...', 'loading_message');
        const formData = new FormData();
        formData.append('acao', 'salvar');
        formData.append('rubrica', JSON.stringify(rubrica));

        return fetch(urlRpc, {
            method: 'POST',
            body: formData,
            credentials: 'include',
        }).then(response => {
            js_removeObj('loading_message');
            return response;
        }).then(response => response.json()).then(response => {
            alert(response.mensagem);

            if (response.erro) {
                return;
            }

            $('sequencial').value = response.rubrica.sequencial;
            $('instituicao').value = response.rubrica.instituicao;
        });
    };

    function validarFormulario() {
        let isPB = '<?php echo isParaiba() ?>';

        if (!isPB) {
            if ($F('cbxIncidenciaPrevi') == '') {
                alert('Incidência de Contrib. Previdenciária não informada.');
                return false;
            }

            if ($F('cbxIncidenciaIRRF') == '') {
                alert('Incidência de IRRF não informada.');
                return false;
            }

            if ($F('cbxIncidenciaFGTS') == '') {
                alert('Incidência de FGTS não informada.');
                return false;
            }

            if ($F('cbxNatureza') == '') {
                alert('Natureza da rubrica não informada.');
                return false;
            }
        }

        if ($F('codigoRubrica') == '') {
            alert('Código da rubrica não informado.');
            return false;
        }

        if (dataInicial.getValue() == '') {
            alert('Data de início de validade não informada.');
            return false;
        }

        if (dataFinal.getValue()) {
            const inicial = Date.convertFrom($('dataInicial').value, DATA_PTBR);
            const final = Date.convertFrom($('dataFinal').value, DATA_PTBR);

            if (inicial.getTime() > final.getTime()) {
                alert('Data de Inicio de validade deve ser menor que a Data de Fim de validade.');
                return false;
            }
        }

        return true;
    }

    function atualizaSubGrupo(selecionado='') {
        var opt = document.createElement('option');
        opt.value = '';
        opt.innerHTML = 'Selecione...';
        comboTCE.innerHTML = "";
        comboTCE.appendChild(opt);
        opcoesSubGrupo.forEach((elemento) => {
            if (elemento.grupo == comboNatureza.value) {
                elemento.dado.forEach((dado) => {
                    var opt = document.createElement('option');
                    opt.value = dado.value;
                    opt.innerHTML = dado.label;
                    comboTCE.appendChild(opt);
                    if (dado.value == selecionado) {
                        comboTCE.value = dado.value;
                    }
                });
            }
        });
    }

    $('cbxNatureza').onchange = function() {
        atualizaSubGrupo();
    };
</script>
