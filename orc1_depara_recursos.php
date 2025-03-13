<?php

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification('libs/db_conecta.php');
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="estilos.css">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
</head>
<body class="body-default">

<div class="container">
    <form id="form-upload" method="post" action="" enctype="multipart/form-data">

        <fieldset>
            <legend>Selecione o exercício de origem dos dados
                <select id="exercicio">
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                </select>
            </legend>

            <fieldset id="filtros" style="display:none;">
                <legend>Atualização da tabela de recursos para <span class="exercicioFuturo"></span></legend>

                <fieldset class="separator">
                    <legend>1º Exporte a tabela de recursos para atualizar a versão de
                        <span class="exercicioFuturo"></span>
                    </legend>
                    <button type="button" class="btn btn-light" id="exportar">
                        <i class="fas fa-download"></i>
                        Exportar Recursos <span class="exercicioOrigem"></span>
                    </button>

                    <button type="button" class="btn btn-light" id="listaRecursos">
                        <i class="fas fa-download"></i>
                        Lista dos recursos SICONFI
                    </button>
                </fieldset>

                <fieldset class="separator">
                    <legend>2º Clique em <kbd>Arquivo</kbd>, selecione a planilha para importar</legend>
                    <div id="ctnImportacao"></div>
                    <div class="text-left" style="margin-top: 5px">
                        <label for="atualizarNome" class="bold">
                            Atualizar nome conforme tabela siconfi <span class="exercicioFuturo"></span>:
                        </label>
                        <select id="atualizarNome">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>

                </fieldset>
            </fieldset>
            <button type="button" class="btn btn-light" id="importar" disabled>
                <i class="fas fa-save"></i>
                Importar
            </button>

        </fieldset>
    </form>
</div>
</body>
<?php db_menu() ?>

<script type="text/javascript" src="scripts/classes/http/http.js"></script>
<script type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
<script type="text/javascript" src="scripts/session.js"></script>
<script>

    const btnImportar = document.getElementById('importar');
    const btnExportar = document.getElementById('exportar');
    const btnListaRecursos = document.getElementById('listaRecursos');
    const cboAtualizarNome = document.getElementById('atualizarNome');

    const cboExercicio = document.getElementById('exercicio');
    cboExercicio.addEventListener('change', () => {
        document.getElementById('filtros').style.display = '';
        document.querySelectorAll('.exercicioFuturo').forEach(elemento => {
            elemento.innerHTML = Number(cboExercicio.value) + 1;
        })
        document.querySelectorAll('.exercicioOrigem').forEach(elemento => {
            elemento.innerHTML = Number(cboExercicio.value);
        })

    });

    cboExercicio.dispatchEvent(new Event('change'));

    const routs = {
        listaRecursos: 'financeiro/orcamento/relatorios/siconfi-recursos-2022',
        exportar: 'financeiro/orcamento/de-para-siconfi/exportar',
        importar: 'financeiro/orcamento/de-para-siconfi/importar'
    }

    const erroRetornoArquivo = mensagem => {
        alert(mensagem);
        btnImportar.disabled = true;
        fileUpload.clear();
    }

    function retornoEnvioArquivo(retorno) {
        if (retorno.error) {
            erroRetornoArquivo(retorno.error);
            return false;
        }

        if (retorno.extension.toLowerCase() != 'csv') {
            erroRetornoArquivo('Arquivo inválido! O arquivo deve ser uma planilha em "csv".');
            return false;
        }

        btnImportar.disabled = false;
    }

    const fileUpload = new DBFileUpload({callBack: retornoEnvioArquivo, labelButton: 'Arquivo'});
    fileUpload.show($('ctnImportacao'));
    document.querySelector(".inputUploadFile").addClassName('field-size8');

    PHPSession.loadData().then(() => {
        btnListaRecursos.addEventListener('click', () => {

            const formData = new FormData();
            PHPSession.appendFormData(formData);

            HttpClient.post(`${PHPSession.requestApi}/${routs.listaRecursos}`, {body: formData}).then(response => {
                if (response.error) {
                    alert(response.message);
                    return;
                }

                criaTelaDownload(response.data.pdf, "Lista de recursos do SICONFI - PDF");
            });
        });

        btnExportar.addEventListener('click', () => {
            let exercicio = cboExercicio.value;
            HttpClient.get(`${PHPSession.requestApi}/${routs.exportar}/${exercicio}`).then(response => {
                if (response.error) {
                    alert(response.message);
                    return;
                }

                criaTelaDownload(response.data.csv, "De Para - csv");
            });
        });

        btnImportar.addEventListener('click', () => {
            let exercicio = Number(cboExercicio.value) + 1;
            const formData = new FormData();
            formData.append('atualizaNome', cboAtualizarNome.value);
            formData.append('exercicioAtualizar', exercicio);
            formData.append('file', JSON.stringify({
                "extension": fileUpload.extension,
                "name": fileUpload.file,
                "path": fileUpload.filePath
            }));

            PHPSession.appendFormData(formData);
            HttpClient.post(`${PHPSession.requestApi}/${routs.importar}`, {body: formData}).then(response => {
                alert(response.message);
                if (response.error) {
                    return;
                }
                location.reload();
            });
        });
    });


    const criaTelaDownload = (filePath, name) => {
        const download = new DBDownload();
        download.addFile(filePath, name);
        download.show();
    };
</script>
</html>
