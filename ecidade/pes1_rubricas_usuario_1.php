<?php

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
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
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/strings.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
</head>
<body>
<form class="container">
    <fieldset>
        <legend>Configure as Permissões de Rubricas por Usuário</legend>
        <table class="form-container">
            <tbody>
            <tr>
                <td>
                    <label for="instituicao">Instituição:</label>
                </td>
                <td>
                    <input type="text" id="instituicao" name="instituicao" class="field-size9 readonly" disabled>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="usuario">
                        <a id="ancoraUsuario" href="#">Usuário:</a>
                    </label>
                </td>
                <td>
                    <input type="text" id="usuario" name="usuario" class="field-size2" lang="id_usuario">
                    <input type="text" id="nome" name="nome" title="Nome" class="field-size7" lang="nome">
                </td>
            </tr>
            </tbody>
        </table>
        <div id="lancador" style="margin-top: 2.5%"></div>
    </fieldset>
    <div>
        <input type="button" value="Salvar" id="salvar">
    </div>
</form>
<?php db_menu(); ?>
<script rel="script" type="text/javascript">
    var instituicao;

    document.getElementById('salvar').addEventListener('click', salvar);

    const lookUpUsuario = new DBLookUp($('ancoraUsuario'), $('usuario'), $('nome'), {
        'sArquivo': 'func_db_usuarios.php',
        'sObjetoLookUp': 'db_iframe_db_usuarios',
        'sLabel': 'Pesquisar Usuário'
    });
    lookUpUsuario.setCallBack('onClick', buscarRubricasUsuario);
    lookUpUsuario.setCallBack('onChange', buscarRubricasUsuario);

    var lancador = new DBLancador('lancador');
    lancador.iGridHeight = '250px';
    lancador.sTextoFieldset = 'Selecione as rubricas para o usuário';
    lancador.setLabelAncora('Rubrica:');
    lancador.setNomeInstancia('lancador');
    lancador.show($('lancador'));

    const parametros = new FormData();
    parametros.append('acao', 'instituicao');

    HttpClient.post('pes1_rubricas_usuario.RPC.php', {body: parametros}).then(response => {
        instituicao = response.instituicao;
        $('instituicao').value = instituicao.descricao;
        lancador.setParametrosPesquisa('func_rhrubricas.php', ['rh27_rubric', 'rh27_descr'],
            'naoFiltraUsuario=true&instituicao=' + instituicao.codigo);
    });

    function buscarRubricasUsuario() {
        lancador.clearAll();

        if ($F('usuario') === '') {
            return alert('Informe o usuário.');
        }

        const parametros = new FormData();
        parametros.append('acao', 'buscarRubricasUsuario');
        parametros.append('usuario', $F('usuario'));
        parametros.append('instituicao', instituicao.codigo);

        HttpClient.post('pes1_rubricas_usuario.RPC.php', {body: parametros}).then(response => {
            response.rubricasUsuario.map(rubricaUsuario => {
                lancador.adicionarRegistro(rubricaUsuario.rubrica.codigo, rubricaUsuario.rubrica.descricao, '');
            });
        });
    }

    function salvar() {
        if ($F('usuario') === '') {
            return alert('É necessário informar o usuário.');
        }

        const parametros = new FormData();
        parametros.append('acao', 'salvarRubricasUsuario');
        parametros.append('usuario', $F('usuario'));
        parametros.append('instituicao', instituicao.codigo);

        lancador.getRegistros().map(registro => {
            parametros.append('rubricas[]', registro.sCodigo);
        });

        if (parametros.getAll('rubricas[]').length === 0 &&
            !confirm('Este processo excluirá todas as rubricas configuradas para o usuário.\nDeseja continuar?')) {
            return buscarRubricasUsuario();
        }

        HttpClient.post('pes1_rubricas_usuario.RPC.php', {body: parametros}).then(response => {
            alert(response.mensagem);

            if (response.erro) {
                return;
            }

            limpar();
        });
    }

    function limpar() {
        lancador.clearAll();

        $('usuario').value = '';
        $('nome').value = '';
    }
</script>
</body>
</html>
