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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_caracter_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_db_usuarios_classe.php"));

$cldb_usuarios = new cl_db_usuarios;
$cliframe_seleciona = new cl_iframe_seleciona;

$clrotulo = new rotulocampo;
$cldb_usuarios->rotulo->label();

parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <fieldset>
        <legend>Solicitações em processo</legend>
        <form name="form1" method="post" action="" target="rel">
            <div>
                <?php
                $aux = new cl_arquivo_auxiliar;
                $aux->cabecalho = "<strong>Usuários</strong>";
                $aux->codigo = "id_usuario";
                $aux->descr = "nome";
                $aux->nomeobjeto = 'db_usuarios';
                $aux->funcao_js = 'js_mostra';
                $aux->funcao_js_hide = 'js_mostra1';
                $aux->sql_exec = "";
                $aux->func_arquivo = "func_db_usuarios.php";
                $aux->nomeiframe = "db_iframe";
                $aux->localjan = "";
                $aux->db_opcao = 2;
                $aux->tipo = 2;
                $aux->top = 2;
                $aux->linhas = 10;
                $aux->vwhidth = 400;
                $aux->funcao_gera_formulario();
                ?>
            </div>

            <div style="display: flex;justify-content: start;margin: 5px auto auto 5px">
                <label for="param_db_usuarios" style="margin-right: 20px"><b>Opções de seleção: </b></label>

                <?php
                $opcoesUsuarios = array("S" => "Somente Selecionados", "N" => "Menos os Selecionados");
                db_select('param_db_usuarios', $opcoesUsuarios, true, 2);
                ?>
            </div>
        </form>
    </fieldset>
</div>
</body>
<script>
    function js_testa(campo, valor) {
        let msg = "Informe um intervalo de código válido!";
        let erro = false;

        if (campo === "i") {
            if (
                document.form1.id_usuarioFIM.value !== ""
                && parseInt(valor) >= parseInt(document.form1.id_usuarioFIM.value)
            ) {
                erro = true;
            }
        } else if (campo === "f") {
            if (
                document.form1.id_usuarioINI.value !== ""
                && parseInt(valor) <= parseInt(document.form1.id_usuarioINI.value)
            ) {
                erro = true;
            }
        }
        if (erro) {
            alert(msg);
            document.form1.id_usuarioINI.value = "";
            document.form1.id_usuarioFIM.value = "";
            document.form1.id_usuarioINI.focus();
        }
    }
</script>
</html>
