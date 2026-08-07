<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009 DBSeller Servicos de Informatica
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

use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\RelatorioPeriodoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\RelatorioRepositorio;
use ECidade\Configuracao\RelatorioLegal\Servico\LinhaServico;

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('classes/db_orcparamrel_classe.php');
require_once modification('classes/db_orcparamrelperiodos_classe.php');

$clorcparamrel = new cl_orcparamrel();
$clorcparamrelperiodos = new cl_orcparamrelperiodos();

$db_opcao = 33;
$db_botao = false;
$erro = false;
$excluiu = false;
$mensagem = '';

db_postmemory($HTTP_POST_VARS);
db_inicio_transacao();

try {
    if (isset($excluir)) {
        if (empty($o42_codparrel)) {
            throw new Exception('É necessário informar o código do relatório.');
        }

        $relatorio = RelatorioRegistry::get($o42_codparrel);

        $relatorioPeriodoRepositorio = new RelatorioPeriodoRepositorio();
        $relatorioPeriodoRepositorio->scopeRelatorio($relatorio)->delete();

        $linhaRepositorio = new LinhaRepositorio();
        $linhas = $linhaRepositorio->scopeRelatorio($relatorio)->get();

        $linhaServico = new LinhaServico();

        foreach ($linhas as $linha) {
            $linhaServico->excluirLinha($relatorio, $linha);
        }

        $relatorioRepositorio = new RelatorioRepositorio();
        $relatorioRepositorio->delete($relatorio);

        $mensagem = 'Relatório excluído com sucesso!';
        $db_opcao = 3;
        $db_botao = true;
        $excluiu = true;
    } elseif (isset($chavepesquisa)) {
        $db_opcao = 3;
        $db_botao = true;
        $result = $clorcparamrel->sql_record($clorcparamrel->sql_query($chavepesquisa));
        db_fieldsmemory($result, 0);
    }
} catch (Exception $exception) {
    $erro = true;
    $mensagem = $exception->getMessage();
}

db_fim_transacao($erro);

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DBSeller Informática Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script src="scripts/scripts.js"></script>
    <script src="scripts/prototype.js"></script>
</head>
<body class="container">
<?php

require_once modification('forms/db_frmorcparamrel.php');

if ($mensagem) {
    db_msgbox($mensagem);
}

if ($excluiu) {
    ?>
    <script>location.href = 'orc1_orcparamrel006.php';</script>
    <?php
}

if (isset($chavepesquisa)) {
    ?>
    <script>
        function js_db_libera() {
            parent.document.formaba.orcparamrelperiodos.disabled = false;
            (window.CurrentWindow ||
                parent.CurrentWindow).corpo.iframe_orcparamrelperiodos.location.href = 'orc1_orcparamrelperiodos001.php?db_opcaoal=33&o113_orcparamrel=<?php echo @$o42_codparrel; ?>';

            <?php if (isset($liberaaba)) {
                ?>
            parent.mo_camada('orcparamrelperiodos');
        }
                <?php
            } ?>

        js_db_libera();
    </script>
    <?php
}

if ($db_opcao == 22 || $db_opcao == 33) {
    echo "<script>document.form1.pesquisar.click();</script>";
}

?>
</body>
</html>
