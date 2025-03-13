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

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_utils.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');
require_once modification('libs/db_liborcamento.php');
require_once modification('classes/db_conrelinfo_classe.php');
require_once modification('classes/db_conrelvalor_classe.php');
require_once modification('classes/db_orcparamrel_classe.php');
require_once modification('classes/db_orcparamseq_classe.php');
require_once modification('classes/db_orcparamelemento_classe.php');
require_once modification('classes/db_orcparamrecurso_classe.php');
require_once modification('classes/db_orcparamsubfunc_classe.php');
require_once modification('classes/db_orcparamnivel_classe.php');
require_once modification('classes/db_orcparamfunc_classe.php');
require_once modification('model/linhaRelatorioContabil.model.php');

use ECidade\Configuracao\RelatorioLegal\Enum\OrigemDadosEnum;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaFiltroPadraoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaInformacaoComplementarRepositorio;

$linhaFiltroPadraoRepositorio = new LinhaFiltroPadraoRepositorio();

parse_str($HTTP_SERVER_VARS['QUERY_STRING'], $queryString);

foreach ($queryString as $key => $value) {
    ${$key} = $value;
}

db_postmemory($HTTP_POST_VARS);

if (!isset($iCodigoPeriodo)) {
    $iCodigoPeriodo = null;
}

$clconrelinfo = new cl_conrelinfo;
$clconrelvalor = new cl_conrelvalor;
$clorcparamrel = new cl_orcparamrel;
$clorcparamseq = new cl_orcparamseq;
$clorcparamelemento = new cl_orcparamelemento;
$clorcparamrecurso = new cl_orcparamrecurso;
$clorcparamsubfunc = new cl_orcparamsubfunc;
$clorcparamfunc = new cl_orcparamfunc;

$oGet = db_utils::postMemory($_GET);
$iCodigoRelatorio = $oGet->c83_codrel;
try {
    $relatorio = RelatorioRegistry::get($iCodigoRelatorio);
} catch (Exception $exception) {
    db_msgbox($exception->getMessage());
}
$anoSessao = db_getsession('DB_anousu');

$clrotulo = new rotulocampo;
$clrotulo->label('c83_codrel');
$clrotulo->label('o42_descrrel');

$db_opcao = 1;
$db_botao = true;

if (!isset($filtrar_seq)) {
    $filtrar_seq = "C";
}

$res = $clorcparamrel->sql_record($clorcparamrel->sql_query($relatorio->getSequencial()));

if ($clorcparamrel->numrows > 0) {
    db_fieldsmemory($res, 0);
}

function atualiza_nivel($rel, $linha, $valor)
{
    $anoSessao = db_getsession('DB_anousu');
    $msg = "0| Registro Atualizado !";
    $clorcparamnivel = new cl_orcparamnivel;

    $sSql = $clorcparamnivel->sql_query_file($anoSessao, $rel, $linha);
    $clorcparamnivel->sql_record($sSql);

    $clorcparamnivel->o44_codparrel = $rel;
    $clorcparamnivel->o44_sequencia = $linha;
    $clorcparamnivel->o44_anousu = $anoSessao;
    $clorcparamnivel->o44_nivelexclusao = '0';

    $clorcparamnivel->o44_nivel = $valor;
    if ($clorcparamnivel->numrows > 0) {
        $clorcparamnivel->o44_nivelexclusao = '0';
        $clorcparamnivel->alterar($anoSessao, $rel, $linha);
    } else {
        $clorcparamnivel->o44_nivelexclusao = '0';
        $clorcparamnivel->incluir($anoSessao, $rel, $linha);
    }

    $erro = $clorcparamnivel->erro_msg;
    if ($clorcparamnivel->erro_status == 0) {
        $msg = "1| Falha ao atualizar Nivel" . $erro;
    }

    return $msg;
}

function atualiza_nivel_exclusao($rel, $linha, $valor)
{
    $anoSessao = db_getsession('DB_anousu');
    $msg = "0| Registro Atualizado !";
    $clorcparamnivel = new cl_orcparamnivel;

    $clorcparamnivel->sql_record($clorcparamnivel->sql_query_file($anoSessao, $rel, $linha));

    $clorcparamnivel->o44_codparrel = $rel;
    $clorcparamnivel->o44_sequencia = $linha;
    $clorcparamnivel->o44_anousu = $anoSessao;
    $clorcparamnivel->o44_nivelexclusao = $valor;

    if ($clorcparamnivel->numrows > 0) {
        $clorcparamnivel->o44_nivel = "";
        $clorcparamnivel->alterar($anoSessao, $rel, $linha);
    } else {
        $clorcparamnivel->o44_nivel = '0';
        $clorcparamnivel->incluir($anoSessao, $rel, $linha);
    }

    $erro = $clorcparamnivel->erro_msg;
    if ($clorcparamnivel->erro_status == 0) {
        $msg = "1| Falha ao atualizar Nivel" . $erro;
    }

    return $msg;
}

require_once modification('dbforms/Sajax.php');
sajax_init();// Inicializar o sajax
$sajax_debug_mode = 0;// para Debugar o sajax = 0 desligado 1 = ligado
sajax_export("atualiza_nivel");// função exportada !
sajax_export("atualiza_nivel_exclusao");// função exportada !
sajax_handle_client_request();

$dbwhere = array(
    "o69_codparamrel = {$relatorio->getSequencial()}"
);

switch ($relatorio->getSequencial()) {
    case AnexoVIResultadoPrimario::CODIGO_RELATORIO:
        $dbwhere[] = "o69_codseq <> 17";
        break;
}

$lista_seq = "";
$virgula = "";

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>DBSeller Informática Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css"/>
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css"/>
    <link href="assets/fontawesome/css/all.min.css" rel="stylesheet" type="text/css"/>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        html {
            font-family: sans-serif;
            line-height: 1.15;
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 12px;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff;
        }

        button {
            border-radius: 0;
            margin: 0;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            overflow: visible;
            text-transform: none;
        }

        table {
            border-collapse: collapse;
        }

        .align-middle {
            vertical-align: middle !important;
        }

        .table-linhas {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table-linhas th,
        .table-linhas td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table-linhas thead th {
            color: white;
            background-color: #4a789c;
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table-linhas tbody + tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-linhas-sm th,
        .table-linhas-sm td {
            padding: 0.3rem;
        }

        .table-linhas-bordered {
            border: 1px solid #dee2e6;
        }

        .table-linhas-bordered th,
        .table-linhas-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-linhas-bordered thead th,
        .table-linhas-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-linhas-hover tbody tr:hover {
            color: #212529;
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-linhas-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .btn-parametros {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-parametros-link {
            font-weight: 400;
            color: #4a789c;
            text-decoration: none;
        }

        .btn-parametros-link:hover {
            color: #2c5676;
            text-decoration: underline;
        }

        .btn-parametros-link:focus {
            text-decoration: underline;
            box-shadow: none;
        }

        .btn-parametros-link:disabled {
            color: #6c757d;
            pointer-events: none;
        }

        .btn-parametros-customizados {
            padding-left: 30px;
        }

        .btn-parametros-sm {
            font-size: 0.8rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .btn-parametros > i {
            margin-left: 5px;
            color: green;
        }

    </style>
    <script src="scripts/scripts.js"></script>
    <script src="scripts/prototype.js"></script>
    <script src="scripts/widgets/windowAux.widget.js"></script>
    <script src="scripts/strings.js"></script>
    <script src="scripts/widgets/filtroorcamento.widget.js"></script>
    <script src="scripts/widgets/messageboard.widget.js"></script>
</head>
<body style="background-color: #CCCCCC">
<form name="form1" method="post">
    <table border=0 width="100%">
        <tr>
            <td>
                <fieldset>
                    <table style="width: 100%">
                        <tbody>
                        <tr>
                            <td style="width: 7.5%">
                                <label for="c83_codrel">
                                    <strong>Código do Relatório:</strong>
                                </label>
                            </td>
                            <td style="width: 5%">
                                <input name="c83_codrel" id="c83_codrel"
                                       value="<?php echo $relatorio->getSequencial(); ?>" readonly
                                       class="form-control" style="width: 100%">
                            </td>
                            <td style="width: 87.5%">
                                <input name="o42_descrrel" id="o42_descrrel"
                                       value="<?php echo $relatorio->getDescricao(); ?>"
                                       readonly class="form-control" style="width: 40%">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td colspan=8 height=20px>
                <fieldset style="overflow: scroll; max-height: 700px; ">
                    <table class="table-linhas table-linhas-sm table-linhas-bordered table-linhas-hover table-linhas-striped bg-white">
                        <thead>
                        <tr>
                            <th style="width: 5%" class="text-center align-middle">
                                <b>Linha</b>
                            </th>
                            <th style="width: 65%" class="text-center align-middle">
                                <b>Descrição</b>
                            </th>
                            <th style="width: 10%" class="text-center align-middle">
                                <b>Configuração Padrão</b>
                            </th>
                            <th style="width: 10%" class="text-center align-middle">
                                <b>Customizar Configuração</b>
                            </th>
                            <th style="width: 10%" class="text-center align-middle">
                                <b>Edição</b>
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php
                        $sSqlLinhas = $clorcparamseq->sql_query_nivel(
                            $relatorio->getSequencial(),
                            null,
                            "distinct o69_codparamrel,
                                          o69_codseq,
                                          o69_grupo,
                                          o69_grupoexclusao,
                                          o69_descr,
                                          o69_librec,
                                          o69_libsubfunc,
                                          o69_libfunc,
                                          o69_manual,
                                          o69_ordem,
                                          o69_totalizador,
                                          o69_labelrel,
                                          o69_nivellinha,
                                          o44_nivel as o69_nivel,
                                          o44_nivelexclusao as o69_nivelexclusao,
                                          o69_libnivel,
                                          o69_origem",
                            "o69_ordem",
                            implode(' AND ', $dbwhere)
                        );

                        $record = $clorcparamseq->sql_record($sSqlLinhas);
                        if ($clorcparamseq->numrows > 0) {
                            for ($x = 0; $x < pg_numrows($record); $x++) {
                                db_fieldsmemory($record, $x);

                                $linha = LinhaRegistry::get($relatorio, $o69_codseq);

                                // Permissao de menu para alterar parametro de relatorio, modulo 209 (Contabilidade)
                                $flag_permissao = db_permissaomenu($anoSessao, 209, 228050);

                                if ($flag_permissao == "true") {
                                    $lb_texto = "Editar";
                                } else {
                                    $lb_texto = "Visualizar";
                                }

                                $paddingLeft = $linha->getNivelLinha() * 10 ?: 10;
                                $sStyleLinha = "padding-left: {$paddingLeft}px;";

                                if ($linha->isTotalizadora()) {
                                    $sStyleLinha .= 'font-weight: bold;';
                                }

                                if ($linha->getOrigem() === OrigemDadosEnum::MSC) {
                                    $sStyleLinha .= 'cursor: pointer;';
                                } ?>
                                <tr style="<?php echo $sStyleLinha; ?>"
                                    data-linha="<?php echo $linha->getOrdem(); ?>"
                                    data-origem="<?php echo $linha->getOrigem(); ?>"
                                    data-descricao="<?php echo $linha->getDescricao(); ?>">
                                    <td class="align-middle text-right decompoe"><?php echo $linha->getOrdem(); ?></td>
                                    <td class="align-middle text-left decompoe" style="<?php echo $sStyleLinha; ?>"
                                        id="descr<?php echo "{$linha->getLinha()}_{$linha->getRelatorio()->getSequencial()}"; ?>">
                                        <?php echo $linha->getLabel(); ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?php

                                        $temFiltro = false;

                                        if ($linha->getOrigem() === OrigemDadosEnum::MSC) {
                                            $linhaInformacaoComplementarRepositorio = new LinhaInformacaoComplementarRepositorio();
                                            $linhaInformacoesComplementares = $linhaInformacaoComplementarRepositorio
                                                ->scopeLinha($linha)
                                                ->scopeRelatorio($linha->getRelatorio())
                                                ->scopePadrao(true)
                                                ->get();

                                            $temFiltro = count($linhaInformacoesComplementares) > 0;
                                        } else {
                                            $filtrosPadroes = $linhaFiltroPadraoRepositorio->resetScopes()
                                                ->scopeLinha($linha)
                                                ->scopeRelatorio($linha->getRelatorio())
                                                ->scopeAno($anoSessao)
                                                ->count();

                                            $temFiltro = $filtrosPadroes > 0;
                                        }

                                        if ($temFiltro) {
                                            ?>
                                            <button class="btn-parametros btn-parametros-sm btn-parametros-link" href="#" onclick="js_mostrapadrao(
                                            <?php echo $linha->getLinha(); ?>,
                                            <?php echo $linha->getRelatorio()->getSequencial(); ?>,
                                            <?php echo $linha->getOrigem(); ?>); return false">Ver</button>
                                            <?php
                                        }

                                        $oLinhaRelatorio = new linhaRelatorioContabil(
                                            $linha->getRelatorio()->getSequencial(),
                                            $linha->getLinha()
                                        ); ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?php
                                        if (!$linha->isTotalizadora() && $linha->getGrupoExclusao() === 0) {
                                            $temCustomizada = false;

                                            if ($linha->getOrigem() === OrigemDadosEnum::MSC) {
                                                $linhaInformacaoComplementarRepositorio = new LinhaInformacaoComplementarRepositorio();
                                                $linhaInformacoesComplementares = $linhaInformacaoComplementarRepositorio
                                                    ->scopeLinha($linha)
                                                    ->scopeRelatorio($linha->getRelatorio())
                                                    ->scopePadrao(false)
                                                    ->get();

                                                $temCustomizada = count($linhaInformacoesComplementares) > 0;
                                            } else {
                                                $sSql = "
                                                    SELECT *
                                                    FROM orcparamseqfiltroorcamento
                                                    WHERE o133_orcparamseq = {$linha->getLinha()}
                                                      AND o133_orcparamrel = {$linha->getRelatorio()->getSequencial()}
                                                      AND o133_anousu = {$anoSessao};
                                                ";

                                                $rsFiltroPadrao = db_query($sSql);

                                                $temCustomizada = pg_num_rows($rsFiltroPadrao) > 0;
                                            } ?>
                                            <button class="btn-parametros btn-parametros-sm btn-parametros-link <?php if ($temCustomizada) echo 'btn-parametros-customizados'; ?>"
                                                    onclick='js_mostrafiltrousuario(
                                                    <?php echo $linha->getLinha(); ?>,
                                                    <?php echo $linha->getRelatorio()->getSequencial(); ?>,
                                                    <?php echo $linha->getOrigem(); ?>); return false'>
                                                Editar <?php if($temCustomizada) echo '<i class="fas fa-check"></i>'; ?>
                                            </button>
                                            <?php
                                        } ?>
                                    </td>
                                    <td class="align-middle text-center" valign="top">
                                        <?php

                                        if ($linha->isManual()) {
                                            $aColunas = $oLinhaRelatorio->getCols();
                                            $avalores = array();
                                            if (count($aColunas) > 0) {
                                                $oLinhaRelatorio->setPeriodo($iCodigoPeriodo);
                                                $avalores = $oLinhaRelatorio->getValoresColunas(
                                                    null,
                                                    null,
                                                    null,
                                                    $anoSessao
                                                );

                                                $temManual = count($avalores) > 0;

                                                ?>
                                                <button class="btn-parametros btn-parametros-sm btn-parametros-link <?php if ($temManual) echo 'btn-parametros-customizados'; ?>"
                                                        onclick="js_editar_colunas(
                                                        <?php echo $linha->getRelatorio()->getSequencial(); ?>,
                                                        <?php echo $linha->getLinha(); ?>
                                                                ); return false;">
                                                    Edição Manual <?php if ($temManual) echo '<i class="fas fa-check"></i>'; ?>
                                                </button>
                                                <?php
                                            } else {
                                                echo "&nbsp;";
                                            }
                                        } else {
                                            echo "&nbsp;";
                                        } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                        </tbody>
                    </table>
            </td>
        </tr>
    </table>
</form>
<script src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script src="scripts/classes/http/http.js"></script>
<script>
    var iPeriodo = '<?=$iCodigoPeriodo?>';
    var sURlRPC = 'con4_configuracaorelatorioRPC.php';
    var MSC = <?php echo OrigemDadosEnum::MSC; ?>;

    function js_editar_elemento(codrel, linha, grupo_de_contas) {
        js_OpenJanelaIframe('', 'wndContas',
          'func_selecionaparametrocontas.php?o69_codparamrel=' + codrel +
          '&o69_codseq=' + linha +
          '&grupo=' + grupo_de_contas + '&flag_permissao=<?=$flag_permissao?>',
          'Escolha as Contas',
          true
        );
    }

    function js_editar_exclusao_elemento(codrel, linha, grupo_de_contas) {
        parent.iframe_parametro.location.href = 'func_seleciona_exclusao_plano.php?o69_codparamrel=' + codrel +
          '&o69_codseq=' + linha + '&grupo=' + grupo_de_contas + '&flag_permissao=<?=$flag_permissao?>';
    }

    function js_editar_recurso(codrel, linha) {
        parent.iframe_parametro.location.href = 'func_seleciona_recursos.php?o69_codparamrel=' + codrel +
          '&o69_codseq=' + linha + '&flag_permissao=<?=$flag_permissao?>';
    }

    function js_editar_subfunc(codrel, linha) {
        parent.iframe_parametro.location.href = 'func_seleciona_subfunc.php?o69_codparamrel=' + codrel +
          '&o69_codseq=' + linha + '&flag_permissao=<?=$flag_permissao?>';
    }

    function js_editar_func(codrel, linha) {
        parent.iframe_parametro.location.href = 'func_seleciona_func.php?o69_codparamrel=' + codrel + '&o69_codseq=' +
          linha + '&flag_permissao=<?=$flag_permissao?>';
    }

    function js_refresh() {
        atualiza_hp();
    }

    function atualiza_hp() {
        document.form1.submit();
    }
    <?php sajax_show_javascript(); ?>
    function js_updateNivel(relatorio, linha, valor) {
        x_atualiza_nivel(relatorio, linha, valor, mensagem);
    }

    function js_updateNivelExclusao(relatorio, linha, valor) {
        x_atualiza_nivel_exclusao(relatorio, linha, valor, mensagem);
    }

    function mensagem(retorno) {
        if (retorno.substr(0, 1) != '0') {
            alert(retorno);
        }
    }

    function js_editar_colunas(iCodRel, iLinha) {

        iAnoPesquisa = <?php echo $anoSessao; ?>;
        var iPeriodo = (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_relatorio.document.getElementById(
          'o116_periodo').value;
        if (iPeriodo == '0') {

            alert('Para configurar manualmente os valores das colunas é necessário selecionar um Período.');
            return false;
        }

        var sUrl = 'con4_orcrelatorioscolunas.php?iCodRel=' + iCodRel + '&iLinha=' + iLinha + '&iPeriodo=' + iPeriodo +
          '&iAnoPesquisa=' + iAnoPesquisa;

        if ($('windowColunas')) {
            oWindowColunas.destroy();
        }

        oWindowColunas = new windowAux(
          'windowColunas',
          'Tela de Edição Manual',
          window.screen.availWidth - 250
        );
        var sConteudo = '<iframe width=\'100%\' style=\'height:99%\' frameborder=0 src=\'' + sUrl + '\'>';
        oWindowColunas.setContent(sConteudo);

        $('windowwindowColunas_btnclose').onclick = function() {
            oWindowColunas.destroy();
            location.reload();
        };

        oWindowColunas.allowCloseWithEsc(false);
        oWindowColunas.allowDrag(false);
        oWindowColunas.show(0, 0, false, 0, 0);
    }

    function js_criafiltro(iLinha, iRelatorio) {
        js_getDadosLinhas(iLinha, iRelatorio);
    }

    function js_saveParametro(iRelatorio, iLinha) {

        var oParam = {};
        oParam.iRelatorio = iRelatorio;
        oParam.iLinha = iLinha;
        oParam.exec = 'salvarParametrosUsuario';
        oParam.filters = {};
        oParam.filters.orgao = filtro.getOrgaos();
        oParam.filters.unidade = filtro.getUnidades();
        oParam.filters.funcao = filtro.getFuncoes();
        oParam.filters.subfuncao = filtro.getSubFuncoes();
        oParam.filters.programa = filtro.getProgramas();
        oParam.filters.projativ = filtro.getProjAtivs();
        oParam.filters.recurso = filtro.getRecursos();
        js_divCarregando('Aguarde, Salvando Dados', 'msgBox');
        $('btnSalvar').disabled = true;

        new Ajax.Request(sURlRPC, {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: js_retornosaveParametro
        });
    }

    function js_retornosaveParametro(oAjax) {

        $('btnSalvar').disabled = false;
        js_removeObj('msgBox');
        var oRetorno = JSON.parse(oAjax.responseText);
        if (oRetorno.status == 1) {

            alert('Dados Salvos com sucesso.');
            filtro.window.destroy();
            location.reload();
        } else {
            alert(oRetorno.message.urlDecode());
        }
    }

    function js_getDadosLinhas(iLinha, iRelatorio) {

        var oParam = {};
        oParam.iRelatorio = iRelatorio;
        oParam.iLinha = iLinha;
        oParam.exec = 'getParametrosUsuario';

        var oAjax = new Ajax.Request(sURlRPC,
          {
              method: 'post',
              parameters: 'json=' + Object.toJSON(oParam),
              onComplete: js_retornoGetDadosLinha
          }
        );
    }

    function js_retornoGetDadosLinha(oAjax) {

        var oRetorno = JSON.parse(oAjax.responseText);
        if (oRetorno.status == 1) {

            filtro = new filtroOrcamento(oRetorno.iLinha + '_' + oRetorno.iRelatorio);
            filtro.showSaveButton(true);
            filtro.setCallBackSave(function() {js_saveParametro(oRetorno.iRelatorio, oRetorno.iLinha);});
            filtro.setData(oRetorno.filter);
            var oMessageBoard = new messageBoard('msg0',
              'Filtro para linha ' +
              $('descr' + oRetorno.iLinha + '_' + oRetorno.iRelatorio).innerHTML,
              'Marque os itens que deseja vincular a linha. ' +
              'Para salvar a informação, clique em salvar.',
              $('windowwindowFiltros' + oRetorno.iLinha + '_' + oRetorno.iRelatorio + '_content')
            );
            oMessageBoard.show();
            filtro.show();
        }
    }

    function js_mostrafiltrousuario(iLinha, iRelatorio, origemLinha) {

        if ($('windowFiltroUsuario')) {
            owindowFiltroUsuario.destroy();
        }

        oWindowFiltroUsuario = new windowAux(
          'windowFiltroUsuario',
          'Tela de Configuração do Usuário',
          1000,
          550
        );

        var sUrl = 'orc4_orcparamseqfiltropadrao.php?o116_codseq=' + iLinha + '&o116_codparamrel=' + iRelatorio +
          '&usuario=true';
        if (origemLinha == MSC) {
            sUrl = 'orc4_configuracaopadraocontacorrente.php?URLSearchParams=true&o116_codparamrel=' + iRelatorio + '&o116_codseq=' + iLinha;
        }

        var sConteudo = '<iframe width=\'100%\' style=\'height:99%\' frameborder=0 src=\'' + sUrl + '\'>';
        sConteudo += '</iframe>';

        oWindowFiltroUsuario.setContent(sConteudo);
        oWindowFiltroUsuario.allowDrag(false);
        var oMessageBoardUsuario = new messageBoard('msg1',
          'Configurações para a linha ' +
          $('descr' + iLinha + '_' + iRelatorio).innerHTML + '.',
          'Informe as contas para a linha e o vínculo com o orçamento.',
          $('windowwindowFiltroUsuario_content')
        );
        oMessageBoardUsuario.show();
        oWindowFiltroUsuario.setShutDownFunction(function() {

            oWindowFiltroUsuario.destroy();
            location.reload();
        });
        oWindowFiltroUsuario.allowCloseWithEsc(false);
        oWindowFiltroUsuario.show(0, 0, false, 0, 0);
    }

    function js_mostrapadrao(iLinha, iRelatorio, origemLinha) {

        console.log(iLinha, iRelatorio, origemLinha);
        if ($('windowFiltrosPadrao')) {
            owindowFiltrosPadrao.destroy();
        }

        oWindowFiltroPadrao = new windowAux(
          'windowFiltrosPadrao',
          'Tela de Configuração Padrão',
            1000,
            550
        );

        var sUrl = 'orc4_orcparamseqfiltropadrao.php?o116_codseq=' + iLinha + '&o116_codparamrel=' + iRelatorio +
          '&readonly=true';
        if (origemLinha == MSC) {
            sUrl = 'orc4_configuracaopadraocontacorrente.php?URLSearchParams=true&o116_codparamrel=' + iRelatorio + '&o116_codseq=' +
              iLinha + '&padrao=true&readonly=true';
        }

        var sConteudo = '<iframe width=\'100%\' style=\'height:99%\' frameborder=0 src=\'' + sUrl + '\'>';
        sConteudo += '</iframe>';

        oWindowFiltroPadrao.setContent(sConteudo);
        oWindowFiltroPadrao.allowDrag(false);
        var oMessageBoard2 = new messageBoard('msg2',
          'Configurações padrões para linha ' +
          $('descr' + iLinha + '_' + iRelatorio).innerHTML + '.',
          '',
          $('windowwindowFiltrosPadrao_content')
        );
        oMessageBoard2.show();
        $('windowwindowFiltrosPadrao_btnclose').onclick = function() {
            oWindowFiltroPadrao.destroy();
        };

        oWindowFiltroPadrao.allowCloseWithEsc(false);
        oWindowFiltroPadrao.show(0, 0, false, 0, 0);
    }

    var oCboPeriodo = parent[0].document.getElementById('o116_periodo');

    if (oCboPeriodo) {
        oCboPeriodo.onchange = js_recarregaTela;
    }

    function js_recarregaTela() {
        var oCboPeriodo = parent[0].document.getElementById('o116_periodo');
        var sUrl = 'con4_parametrosrelatorioslegais001.php?c83_codrel=<?=$iCodigoRelatorio?>&iCodigoPeriodo=' +
          oCboPeriodo.value;
        (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_parametro.location.href = sUrl;
    }

    if (iPeriodo != oCboPeriodo.value) {
        js_recarregaTela();
    }

    /**
     * Cria windowAux com formulario
     */
    function js_editarVinculo(iLinha, iRelatorio) {

        var iWidth = document.width - 10;
        var iHeight = document.body.scrollHeight - 100;
        var sConteudo = ' <form method=\'post\' id=\'form1\'>';
        sConteudo += '   <center>';
        sConteudo += '     <table>';
        sConteudo += '       <tr>';
        sConteudo += '         <td>';
        sConteudo += '           <fieldset>';
        sConteudo += '             <legend><b>Vinculos SIGAP</b>';
        sConteudo += '             <table>';
        sConteudo += '               <tr>';
        sConteudo += '                 <td nowrap title=\'Código Sequencial Campo:o141_sequencial\'>';
        sConteudo += '                   <strong>Código Sequencial:</strong>';
        sConteudo += '                 </td>';
        sConteudo += '                 <td>';
        sConteudo += '                   <input title=\'Código Sequencial Campo:o141_sequencial\'';
        sConteudo += '                          name=\'o141_sequencial\' type=\'text\'';
        sConteudo += '                          id=\'o141_sequencial\' value=\'\' size=\'10\' maxlength=\'10\' readonly';
        sConteudo += '                          style=\'background-color:#DEB887;\' autocomplete=\'off\'>';
        sConteudo += '                 </td>';
        sConteudo += '               </tr>';
        sConteudo += '               <tr>';
        sConteudo += '                 <td nowrap title=\'Conta SIGAP Campo:o141_contasigap\'>';
        sConteudo += '                   <strong>Conta SIGAP:</strong>';
        sConteudo += '                 </td>';
        sConteudo += '                 <td>';
        sConteudo += '                   <input title=\'Conta SIGAP Campo:o141_contasigap\'';
        sConteudo += '                          name=\'o141_contasigap\' type=\'text\'';
        sConteudo += '                          id=\'o141_contasigap\' value=\'\' size=\'100\' maxlength=\'100\'';
        sConteudo += '                          autocomplete=\'off\'>';
        sConteudo += '                 </td>';
        sConteudo += '               </tr>';
        sConteudo += '               <tr>';
        sConteudo += '                 <td nowrap title=\'Descrição Campo:o141_descricao \'>';
        sConteudo += '                   <strong>Descrição:</strong>';
        sConteudo += '                 </td>';
        sConteudo += '                 <td>';
        sConteudo += '                   <input title=\'Descrição Campo:o141_descricao\' name=\'o141_descricao\'';
        sConteudo += '                          type=\'text\' id=\'o141_descricao\' value=\'\' size=\'100\' maxlength=\'100\'';
        sConteudo += '                          autocomplete=\'off\'>';
        sConteudo += '                   </td>';
        sConteudo += '               </tr>';
        sConteudo += '               <tr>';
        sConteudo += '                 <td nowrap title=\'Estrutural Campo:o141_estrutural \'>';
        sConteudo += '                   <strong>Estrutural:</strong>';
        sConteudo += '                 </td>';
        sConteudo += '                 <td>';
        sConteudo += '                   <input title=\'Estrutural Campo:o141_estrutural\' name=\'o141_estrutural\'';
        sConteudo += '                          type=\'text\' id=\'o141_estrutural\' value=\'\' size=\'20\' maxlength=\'20\'';
        sConteudo += '                          autocomplete=\'off\'>';
        sConteudo += '                 </td>';
        sConteudo += '               </tr>';
        sConteudo += '             </table>';
        sConteudo += '           </fieldset>';
        sConteudo += '         </td>';
        sConteudo += '       </tr>';
        sConteudo += '       <tr>';
        sConteudo += '         <td align=\'center\'>';
        sConteudo += '           <input type=\'button\' id=\'btnSalvarVinculo\' value=\'Salvar\'';
        sConteudo += '                  onclick=\'return js_salvarVinculo(' + iLinha + ',' + iRelatorio + ');\'>';
        sConteudo += '           <input type=\'button\' id=\'btnExcluirVinculo\' value=\'Excluir\'';
        sConteudo += '                  onclick=\'return js_excluirVinculo(' + iLinha + ',' + iRelatorio + ');\'';
        sConteudo += '                  style=\'display: ;\'>';
        sConteudo += '         </td>';
        sConteudo += '       </tr>';
        sConteudo += '     </table>';
        sConteudo += '   </center>';
        sConteudo += ' </form>';

        if ($('windowVinculosSigap')) {
            oWindowVinculosSigap.destroy();
        }

        oWindowVinculosSigap = new windowAux('windowVinculosSigap', 'Vinculos SIGAP', iWidth);
        oWindowVinculosSigap.setContent(sConteudo);
        oWindowVinculosSigap.allowDrag(false);

        var oMessageBoardVinculoSigap = new messageBoard('msg3',
          'Manutenção do Vinculo SIGAP ' +
          $('descr' + iLinha + '_' + iRelatorio).innerHTML,
          'Informe o vínculo com o SIGAP.',
          $('windowwindowVinculosSigap_content')
        );
        oMessageBoardVinculoSigap.show();

        $('windowwindowVinculosSigap_btnclose').onclick = function() {

            oWindowVinculosSigap.destroy();
            location.reload();
        };

        oWindowVinculosSigap.allowCloseWithEsc(false);
        oWindowVinculosSigap.show(3, 2);

        js_getDadosVinculo(iLinha, iRelatorio);
    }

    /**
     * Busca dados vunculo sigap
     */
    function js_getDadosVinculo(iLinha, iRelatorio) {

        $('o141_sequencial').value = '';
        $('o141_contasigap').value = '';
        $('o141_descricao').value = $('descr' + iLinha + '_' + iRelatorio).innerHTML.trim();
        $('o141_estrutural').value = '';

        js_divCarregando('Aguarde, pesquisando vinculo SIGAP...', 'msgBoxVinculoSigap');

        if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

            $('btnSalvarVinculo').disabled = true;
            $('btnExcluirVinculo').disabled = true;
        }

        var oParam = {};
        oParam.exec = 'getVinculoSigap';
        oParam.linha = iLinha;
        oParam.relatorio = iRelatorio;

        new Ajax.Request('con4_configuracaorelatorioRPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: function(oAjax) {
                js_removeObj('msgBoxVinculoSigap');

                if ($('btnSalvarVinculo')) {
                    $('btnSalvarVinculo').disabled = false;
                }

                /*
                 * Trata o retorno da function js_getDadosVinculo()
                 */
                var oRetorno = JSON.parse(oAjax.responseText);
                if (oRetorno.status == 1) {

                    if (oRetorno.filter != false) {

                        if ($('btnExcluirVinculo')) {
                            $('btnExcluirVinculo').disabled = false;
                        }

                        $('o141_sequencial').value = oRetorno.filter.sequencial;
                        $('o141_contasigap').value = oRetorno.filter.contasigap;
                        $('o141_descricao').value = oRetorno.filter.descricao;
                        $('o141_estrutural').value = oRetorno.filter.estrutural;
                    }
                    return true;
                } else {

                    alert(oRetorno.message.urlDecode());
                    return false;
                }
            }
        });
    }

    /**
     * Salvar vinculo sigap
     */
    function js_salvarVinculo(iLinha, iRelatorio) {

        if ($('o141_contasigap').value == '') {

            alert('Informe a conta SIGAP!');
            return false;
        }

        if ($('o141_descricao').value == '') {

            alert('Informe uma descrição!');
            return false;
        }

        if ($('o141_estrutural').value == '') {

            alert('Informe o estrutural!');
            return false;
        }

        js_divCarregando('Aguarde, salvando vinculo SIGAP...', 'msgBoxVinculoSigap');

        if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

            $('btnSalvarVinculo').disabled = true;
            $('btnExcluirVinculo').disabled = true;
        }

        var oParam = {};
        oParam.exec = 'salvarVinculoSigap';
        oParam.linha = iLinha;
        oParam.relatorio = iRelatorio;

        oParam.filters = {};
        oParam.filters.contasigap = encodeURIComponent(tagString($('o141_contasigap').value));
        oParam.filters.descricao = encodeURIComponent(tagString($('o141_descricao').value));
        oParam.filters.estrutural = encodeURIComponent(tagString($('o141_estrutural').value));

        new Ajax.Request('con4_configuracaorelatorioRPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: function(oAjax) {

                js_removeObj('msgBoxVinculoSigap');

                if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

                    $('btnSalvarVinculo').disabled = false;
                    $('btnExcluirVinculo').disabled = false;
                }

                /*
                 * Trata o retorno da function js_salvarVinculo()
                 */
                var oRetorno = JSON.parse(oAjax.responseText);
                if (oRetorno.status == 1) {

                    js_getDadosVinculo(oRetorno.iLinha, oRetorno.iRelatorio);
                    return true;
                } else {

                    alert(oRetorno.message.urlDecode());
                    return false;
                }
            }
        });
    }

    function js_excluirVinculo(iLinha, iRelatorio) {

        if (!confirm('Confirma exclusão do vinculo SIGAP?')) {
            return false;
        }

        js_divCarregando('Aguarde, excluindo vinculo SIGAP...', 'msgBoxVinculoSigap');

        if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

            $('btnSalvarVinculo').disabled = true;
            $('btnExcluirVinculo').disabled = true;
        }

        var oParam = {};
        oParam.exec = 'excluirVinculoSigap';
        oParam.linha = iLinha;
        oParam.relatorio = iRelatorio;

        new Ajax.Request('con4_configuracaorelatorioRPC.php', {
            method: 'post',
            parameters: 'json=' + Object.toJSON(oParam),
            onComplete: function(oAjax) {

                js_removeObj('msgBoxVinculoSigap');

                if ($('btnSalvarVinculo') && $('btnExcluirVinculo')) {

                    $('btnSalvarVinculo').disabled = false;
                    $('btnExcluirVinculo').disabled = false;
                }

                /*
                 * Trata o retorno da function js_salvarVinculo()
                 */
                var oRetorno = JSON.parse(oAjax.responseText);
                if (oRetorno.status == 1) {

                    alert('Vinculo excluido com sucesso.');
                    js_getDadosVinculo(oRetorno.iLinha, oRetorno.iRelatorio);
                    return true;
                } else {

                    alert(oRetorno.message.urlDecode());
                    return false;
                }
            }
        });
    }

    (() => {
        var windowAutorizacoes = null;

        document.querySelectorAll('.decompoe').forEach(td => {
            const trLinha = td.parentElement;

            if (trLinha.dataset.origem == MSC) {
                trLinha.style.cursor = 'pointer';

                td.addEventListener('click', () => {
                    const get = new URLSearchParams(window.location.search);

                    const formData = new FormData();
                    formData.append('acao', 'decomporValorLinha');
                    formData.append('periodo', get.get('iCodigoPeriodo'));
                    formData.append('relatorio', get.get('c83_codrel'));
                    formData.append('linha', trLinha.dataset.linha);

                    HttpClient.post('con1_relatorio_legal.RPC.php', {
                        body: formData
                    }).then(response => {
                        if (response.erro) {
                            return alert(response.mensagem);
                        }

                        const colunas = response.decomposicao.colunas;
                        const contas = response.decomposicao.contas;
                        const atributos = response.decomposicao.atributos;
                        const table = document.createElement('table');
                        table.classList.add(
                          'table-linhas',
                          'table-linhas-sm',
                          'table-linhas-striped',
                          'table-linhas-bordered',
                          'bg-white'
                        );

                        var td = document.createElement('td');
                        td.rowSpan = 2;

                        var tr = document.createElement('tr');
                        tr.appendChild(td);

                        Object.keys(colunas).forEach(indexColuna => {
                            const coluna = colunas[indexColuna];

                            td = document.createElement('td');
                            td.classList.add('text-center');
                            td.colSpan = coluna.contas.length;
                            td.innerText = coluna.descricao.toUpperCase();

                            tr.appendChild(td);
                        });

                        table.appendChild(tr);

                        tr = document.createElement('tr');

                        Object.keys(colunas).forEach(indexColuna => {
                            const coluna = colunas[indexColuna];

                            if (coluna.contas.length === 0) {
                                td = document.createElement('td');
                                td.innerText = '-';
                                td.classList.add('text-center');
                                tr.appendChild(td);
                            }

                            Object.keys(coluna.contas).forEach(indexConta => {
                                const conta = colunas[indexColuna].contas[indexConta];

                                td = document.createElement('td');
                                td.classList.add('text-center');
                                td.innerText = conta;

                                tr.appendChild(td);
                            });
                        });

                        table.appendChild(tr);

                        Object.keys(atributos).forEach(indexAtributo => {
                            var contadorLinhas = 0;

                            const atributoLancamentoContabil = atributos[indexAtributo];
                            const informacoes = {};

                            Object.keys(atributoLancamentoContabil.informacoes).sort().forEach(key => {
                                informacoes[key] = atributos[indexAtributo].informacoes[key];
                            });

                            atributoLancamentoContabil.informacoes = informacoes;

                            Object.keys(atributoLancamentoContabil.informacoes).forEach(siglaInformacaoComplementar => {
                                const valorInformacaoComplementar = atributoLancamentoContabil.informacoes[siglaInformacaoComplementar];
                                const quantidadeInformacoes = Object.keys(
                                  atributoLancamentoContabil.informacoes).length;

                                td = document.createElement('td');

                                if (contadorLinhas + 1 === quantidadeInformacoes) {
                                    td.style.borderTop = '0';
                                }
                                else {
                                    td.style.border = '0';
                                }

                                td.innerText = `${siglaInformacaoComplementar}: ${valorInformacaoComplementar}`;

                                tr = document.createElement('tr');
                                tr.appendChild(td);

                                if (contadorLinhas === 0) {
                                    Object.keys(contas).forEach(ordemColuna => {
                                        const colunaEstruturais = contas[ordemColuna];

                                        if (colunaEstruturais.length === 0) {
                                            td = document.createElement('td');
                                            td.classList.add('text-right', 'align-middle');
                                            td.rowSpan = quantidadeInformacoes;
                                            td.innerText = 'R$ 0,00';

                                            tr.appendChild(td);
                                        }

                                        Object.keys(colunaEstruturais).forEach(indexColunaEstrutural => {
                                            const colunaEstrutural = colunaEstruturais[indexColunaEstrutural];

                                            var valorLancamentoContabil = atributoLancamentoContabil.valores[ordemColuna][colunaEstrutural];
                                            valorLancamentoContabil = parseFloat(valorLancamentoContabil);
                                            valorLancamentoContabil = valorLancamentoContabil.toLocaleString('pt-BR', {
                                                style: 'currency',
                                                currency: 'BRL'
                                            });

                                            td = document.createElement('td');
                                            td.classList.add('text-right', 'align-middle');
                                            td.rowSpan = quantidadeInformacoes;
                                            td.innerText = valorLancamentoContabil;

                                            tr.appendChild(td);
                                        });
                                    });
                                }

                                table.appendChild(tr);

                                contadorLinhas++;
                            });
                        });

                        if (windowAutorizacoes !== null) {
                            windowAutorizacoes.destroy();
                        }

                        windowAutorizacoes = new windowAux(
                          'windowAuxDecomposicao',
                          'Decomposição do Valor',
                          (screen.availWidth - 200)
                        );
                        windowAutorizacoes.setContent(table.outerHTML);
                        windowAutorizacoes.allowCloseWithEsc(true);
                        windowAutorizacoes.setShutDownFunction(() => {
                            windowAutorizacoes.destroy();
                            windowAutorizacoes = null;
                        });
                        new DBMessageBoard(
                          'DBMessageBoardDecomposicao',
                          `Linha ${trLinha.dataset.linha} - ${trLinha.dataset.descricao}`,
                          '',
                          windowAutorizacoes.getContentContainer()
                        ).show();
                        windowAutorizacoes.show(0, 0, false, 0, 0);
                    });
                });
            }
        });
    })();
</script>
</body>
</html>
