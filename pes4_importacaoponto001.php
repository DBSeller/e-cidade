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
<div class="alert alert-primary text-left" role="alert">
    Importe a planilha de movimentação do ponto mensal no formato CSV!<br>
</div>
<div class="container">
    <form id="form-upload" method="post" action="" enctype="multipart/form-data">
        <fieldset>
            <legend>Clique em <kbd>Arquivo</kbd>, selecione a planilha para importar</legend>
            <fieldset>
                <legend style="margin: 0 auto;">Configurações</legend>
            <div class="text-left" style="display: flex; align-items: center; margin-top: 5px">
                    <label for="exercicio" class="bold">Competência: </label>
                <div style="margin-left: 2px;">
                    <input id="exercicio" name="exercicio" type="text" class="field-size1" maxlength="4"
                           oninput="js_ValidaCampos(this,1,'Ano','t','f',event);"> /
                    <input id="mes" name="mes" type="text" class="field-size1" maxlength="2"
                           oninput="js_ValidaCampos(this,1,'Mês','t','f',event);">
                </div>
            </div>
            <div class="text-left" style="display: flex; align-items: center; margin-top: 5px">
                <label for="acao" class="bold">Em caso de duplicidade:</label>
                <input type="radio" name="acao" value="ignorar">
                <label for="acao">Ignorar</label>
                <input type="radio" name="acao" value="substituir">
                <label for="acao">Substituir</label> 
                <!-- <input type="radio" name="acao" value="duplicar">
                <label for="acao">Duplicar</label> -->
            </div>
            <div class="text-left" style="display: flex; align-items: center; margin-top: 5px">
                <label for="tabela" class="bold">Tabela:</label>
                <input type="radio" name="ponto" value="S">
                <label>Ponto Salário</label>
                <input type="radio" name="ponto" value="F">
                <label>Ponto Fixo</label> 
                <input type="radio" name="ponto" value="C">
                <label>Ponto Complementar</label>
            </div>
            <div class="text-left" style="display: flex; align-items: center; margin-top: 5px">
                <label for="separador" class="bold">Separador CSV:</label>
            <input type="text" id="separador" name="separador" style="width: 20px" maxlength="1">
            </div>
        </fieldset>
            <div id="ctnImportacao"></div>
        </fieldset>
        <button type="button" class="btn btn-light" id="importar" disabled>
            <i class="fa fa-save" aria-hidden="true"></i>
            Salvar
        </button>
    </form>
</div>

<?php db_menu() ?>

<script type="text/javascript" src="scripts/classes/http/http.js"></script>
<script type="text/javascript" src="scripts/widgets/DBFileUpload.widget.js"></script>
<script type="text/javascript" src="scripts/session.js"></script>
<script>

    const btnImportar = document.getElementById('importar');
    const inputExercicio = document.getElementById('exercicio');
    const inputMes = document.getElementById('mes');
    const inputSeparador = document.getElementById('separador');

    /**
     * Configuração Jetom
     */
    const urlBase = "<?php echo ECIDADE_REQUEST_PATH;?>" + 'v4/api/recursos-humanos/pessoal/jetom';
    
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

    function displayRadioValue(atributo) {
        var ele = document.getElementsByName(atributo);
        for (i = 0; i < ele.length; i++) {
            if (ele[i].checked) {
                return ele[i].value;
            }
        }
    }

    const fileUpload = new DBFileUpload({callBack: retornoEnvioArquivo, labelButton: 'Arquivo'});
    fileUpload.show($('ctnImportacao'));
    document.querySelector(".inputUploadFile").addClassName('field-size8');

    PHPSession.loadData().then(() => {

        inputExercicio.value = PHPSession.getValueSession('DB_anousu');
        inputMes.value = '<?php echo DBPessoal::getMesFolha() ?>';
        inputSeparador.value = ';';

        btnImportar.addEventListener('click', () => {

            const inputSeparador = document.getElementById('separador');
            const optionPonto = displayRadioValue('ponto');
            const optionAcao = displayRadioValue('acao');
            
            if (empty(inputExercicio.value)) {
                alert('Informe o exercício');
                return;
            }

            if (empty(inputMes.value)) {
                alert('Informe o mês.');
                return;
            }

            if (empty(inputSeparador.value)) {
                alert('Informe o separador.');
                return;
            }

            if (empty(optionPonto)) {
                alert('Informe a tabela.');
                return;
            }

            if (empty(optionAcao)) {
                alert('Informe a ação em caso de duplicidade.');
                return;
            }

            const formData = new FormData();
            formData.append('exercicio', inputExercicio.value);
            formData.append('mes', inputMes.value);
            formData.append('ponto', optionPonto);
            formData.append('acao', optionAcao);
            formData.append('separador', inputSeparador.value);
            formData.append('file', JSON.stringify({
                "extension": fileUpload.extension,
                "name": fileUpload.file,
                "path": fileUpload.filePath
            }));

            PHPSession.appendFormData(formData);
            HttpClient.post(`${urlBase}/importar/arquivoponto`, {body: formData}).then(response => {
                alert(response.message);
                if (response.error) {
                    return;
                }
                if (!response.data) {
                    location.reload();
                }
            });
        });
    });
</script>
