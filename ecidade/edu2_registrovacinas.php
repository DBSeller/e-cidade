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
require(modification("libs/db_app.utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
</head>
<body class="body-default">
<?php MsgAviso(db_getsession("DB_coddepto"),"escola"); ?>
<div class="container">
    <fieldset>
        <legend>Relatório de Vacinas</legend>
        <form action="">
            <table class="form-container">
                <tr>
                    <td>Escola:</td>
                    <td>
                        <select name="escolas" id="escolas">
                            <option value="">Todas</option>
                            <option value="">escola teste</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Filtrar:</td>
                    <td>
                        <select name="tipo-relatorio" id="tipo-relatorio">
                            <option value="1">Todos profissionais</option>
                            <option value="2">Apenas profissionais vacinados</option>
                            <option value="3">Apenas profissionais não vacinados</option>
                        </select>
                    </td>
                </tr>
            </table>
            <fieldset class="separator">
                <legend>Vacinas</legend>
            </fieldset>
            <div id="ctnVacinas" style="text-align: left">
                <button type="button" class="btn btn-light" id="btnMarcar">
                    <i class="far fa-check-square"></i>
                    Marcar/Desmarcar todos
                </button>
            </div>
            <fieldset class="separator">
                <legend>Exportação</legend>
            </fieldset>
            <div>
                <label for="exportacao"><strong>Formato de exportação: </strong></label>
                <select name="exportacao" id="exportacao">
                    <option value="pdf">PDF</option>
                    <option value="csv">CSV</option>
                </select>
            </div>
        </form>
    </fieldset>
    <button type="button" class="btn btn-light" id="btnEmitir">
        <i class="fa fa-file-pdf"></i>
        Emitir Relatório
    </button>
</div>
<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript">
    const TODOS = 1;
    const VACINADOS = 2;
    const NAO_VACINADOS = 3;

    const cboEscolas = document.getElementById('escolas');
    const cboTipo = document.getElementById('tipo-relatorio');
    const cboExportacao = document.getElementById('exportacao');
    const ctnVacinas = document.getElementById('ctnVacinas');
    const btnMarcar = document.getElementById('btnMarcar');
    const btnEmitir = document.getElementById('btnEmitir');

    PHPSession.loadData().then(() => {
        HttpClient.get(`${PHPSession.requestApi}/educacao/escola/vacinas`).then(response => {
            let vacinas = response.data;
            vacinas.map((vacina) => {
                let checkbox = document.createElement('input');
                checkbox.setAttribute('type', 'checkbox');
                checkbox.setAttribute('id', `vacina_${vacina.ed178_codigo}`);
                checkbox.name = 'filtro-vacinas';
                checkbox.value = vacina.ed178_codigo;

                let nomeVacina = document.createElement('label');
                nomeVacina.setAttribute('for', `vacina_${vacina.ed178_codigo}`);
                nomeVacina.innerText = vacina.ed178_descricao;

                let div = document.createElement('div');
                div.appendChild(checkbox);
                div.appendChild(nomeVacina);

                ctnVacinas.appendChild(div);
            });
        });
    });

    const js_retornoEscola = (oAjax) => {
        js_removeObj('msgBox');
        var oRetorno = JSON.parse(oAjax.responseText);


            let escolas = oRetorno.dados;

            escolas.sort((a, b) => {
                return a.codigo_escola - b.codigo_escola;
            });

            cboEscolas.length = 0;
            if (escolas.length > 1) {
                cboEscolas.options.add(new Option('Todas', ''));
            }

            escolas.map((escola) => {
                cboEscolas.options.add(
                    new Option(`${escola.codigo_escola} - ${escola.nome_escola.urlDecode()}`, escola.codigo_escola,)
                );
            });
    }

    function js_buscaEscola() {
        var oParamentro = {
            exec: 'pesquisaEscola'
        };

        js_divCarregando("Aguarde, buscando escolas...", "msgBox");
        new Ajax.Request('edu_educacaobase.RPC.php',
            {
                method: 'post',
                parameters: 'json=' + Object.toJSON(oParamentro),
                onComplete: js_retornoEscola
            }
        );
    }

    js_buscaEscola();

    btnEmitir.addEventListener('click', () => {
        if (cboTipo.value == VACINADOS &&
            document.querySelectorAll('input[type=checkbox][name=filtro-vacinas]:checked').length == 0) {
            alert("Selecione pelo menos uma Vacina no filtro.");
            return;
        }
        const formData = new FormData();
        formData.append('escola', cboEscolas.value);
        formData.append('tipo', cboTipo.value);
        formData.append('exportacao', cboExportacao.value);
        document.querySelectorAll('input[type=checkbox][name=filtro-vacinas]:checked').forEach((chk) => {
            formData.append('vacinas[]', chk.value);
        })

        HttpClient.post(`${PHPSession.requestApi}/educacao/escola/relatorios/vacinacao`, { body: formData })
            .then(response => {
                if (response.error) {
                    alert(response.message);
                    return;
                }

                const download = new DBDownload();
                download.addFile(response.data.path, `${response.data.name}`);
                download.show();
            });
    })

    cboTipo.addEventListener('change', () => {
        if (cboTipo.value == 3) {
            document.querySelectorAll('input[type=checkbox][name=filtro-vacinas]').forEach((chk) => {
                chk.checked = false;
                chk.disabled = true;
            })
            btnMarcar.disabled = true;
        } else {
            document.querySelectorAll('input[type=checkbox][name=filtro-vacinas]').forEach((chk) => {
                chk.disabled = false;
            })
            btnMarcar.disabled = false;
        }
    });

    btnMarcar.addEventListener('click', () => {
        const quantidade = document.querySelectorAll('input[type=checkbox][name=filtro-vacinas]');
        const marcados = document.querySelectorAll('input[type=checkbox][name=filtro-vacinas]:checked');
        let selecionar = true;
        if (quantidade.length == marcados.length) {
            selecionar = false;
        }
        quantidade.forEach((chk) => {
            chk.checked = selecionar;
        })
    });
</script>
<?php db_menu(); ?>
</body>
</html>
