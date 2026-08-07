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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link rel="stylesheet" type="text/css" href="estilos.css" />
    <link rel="stylesheet" type="text/css" href="estilos/DBtab.style.css" />
    <link rel="stylesheet" type="text/css" href="estilos/dbtreeview.style.css" />
    <link rel="stylesheet" type="text/css" href="estilos/grid.style.css" />
    <link rel="stylesheet" href="assets/fontawesome/css/all.min.css">
    <script language="javascript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/DBAbasItem.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBTreeView.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="javascript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBOrderRows.plugin.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewArvoreTurma.classe.js" ></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js" ></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js" ></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/DBViewCriterioAvaliacaoOrdenar.classe.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/DBViewCriterioAvaliacaoTurma.classe.js"></script>
    <script type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script type="text/javascript" src="scripts/classes/http/http.js"></script>
</head>

<body bgcolor="#cccccc">

<div style="margin-top: 15px;" id = 'ctnAbas'></div>

<!-- ************************************************************************************************************
     *************************************** Aba de Inclusão/alteração de comissão ******************************
     ************************************************************************************************************ -->
<div id='abaComissao' class="container conteudo-aba">
    <form id="form-comissao" name="form-comissao" method="post" action="">
        <input type="text" name="input-sequencial" id="input-sequencial-comissao" style="display: none">
        <fieldset class="container">
            <legend>Cadastro de Comissões</legend>
            <table class="form-container">
                <tr>
                    <td nowrap title="Descrição">
                        Descrição:
                    </td>
                    <td>
                        <?php db_input('descricao', 50, '', true, 'text', 1); ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        Data de Inicio:
                    <td>
                        <?php db_inputdata('datainicio', null, null, null, true, 'text', 1); ?>
                        <strong>&nbsp;Data de Fim:<strong/>
                            <?php db_inputdata('datafim', null, null, null, true, 'text', 1); ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Tipo de Ação">
                        Tipo:
                    </td>
                    <td>
                        <select name="Tipo" id="incluir-alterar-comissao">
                            <option value="incluir">Inclusão</option>
                            <option value="alterar">Alteração</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <br/>
        <input name="incluir" type="button" id="incluir-comissao" value="Incluir">
    </form>
    <form>
        <fieldset>
            <legend>Manutenção de Comissões</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <div id="container-comissao"></div>
                    </td>
                </tr>
            </table>
        </fieldset>
        <tr>
            <td nowrap title="Buscar todas as comissões">
                Buscar todas as comissões:
            </td>
            <td>
                <input type="checkbox" id="checkBuscaComissoes">
            </td>
        </tr>
    </form>
</div>

<!-- ************************************************************************************************************
     *************************************** Aba de Inclusão/alteração de Permissão ******************************
     ************************************************************************************************************ -->
<div id='abaPermissao' class="container conteudo-aba">
    <form id="form-permissao" name="form-permissao" method="post" action="">
        <input type="text" name="input-sequencial" id="input-sequencial-permissao" style="display: none">
        <fieldset class="container">
            <legend>Permissão</legend>
            <table class="form-container">
                <tr>
                    <td title="Matricula">
                        <?php db_ancora('Matricula','js_pesquisaPermi(true);', 1);?>
                    </td>
                    <td nowrap>
                        <?php
                        db_input("input-matricula-permissao",8, 1, true ,'text',1,"onchange='js_pesquisaPermi(false);'");
                        db_input("input-nome-permissao",40,true,'text',3);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Comissões">
                       Comissão:
                    </td>
                    <td>
                        <select name="comissoes-permissao" id="comissoes-permissao">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Tipo de Ação">
                        Tipo:
                    </td>
                    <td>
                        <select name="Tipo" id="incluir-alterar-comissaoPermissao">
                            <option value="incluir">Inclusão</option>
                            <option value="alterar">Alteração</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <br/>
        <input name="incluir" type="button" id="incluir-permissao" value="Incluir">
    </form>
    <form>
        <fieldset>
            <legend>Manutenção de Permissão</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <div id="container-permissao"></div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>


<!-- ************************************************************************************************************
 ********************************** Aba de Tipos de Sessoes da comissão *************************************
 ************************************************************************************************************ -->
<div class="container conteudo-aba" id='abaTipoSessao'>
    <form id="form-tiposessao" name="form-tiposessao method="post" action="" >
    <input type="text" name="input-sequencial-comissao-tiposessao" id="input-sequencial-comissao-tiposessao" style="display: none">
    <input type="text" name="input-sequencial-tiposessao" id="input-sequencial-tiposessao" style="display: none">
    <fieldset class="container">
        <legend>Tipo Sessão</legend>
        <table class="form-container">
            <tr>
                <td nowrap title="Descrição do tipo de Sessão">
                    Tipo de Sessão:
                </td>
                <td>
                    <select name="comissaotiposessao" id="comissao-tipo-sessao">
                        <option value="">Selecione</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td nowrap title="Quantidade Máxima de Sessões">
                    Quantidade Máxima de Sessões no mês:
                </td>
                <td>
                    <?php db_input('tiposessao-quantidade', 50, 1, true, 'text', 1); ?>
                </td>
            </tr>
            <tr>
                <td nowrap title="Tipo de Ação">
                    Tipo:
                </td>
                <td>
                    <select name="Tipo" id="incluir-alterar-tiposessao">
                        <option value="incluir">Inclusão</option>
                        <option value="alterar">Alteração</option>
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>
    <br/>
    <input name="incluirComissaoTipoSessao" type="button" id="incluir-tiposessao" value="Incluir">
    </form>
    <form>
        <fieldset>
            <legend>Manutenção de Tipos de Sessões</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <div id="container-tiposessao"></div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>


<!-- ************************************************************************************************************
     ********************************** Aba de Funções da comissão *************************************
     ************************************************************************************************************ -->
<div class="container" id='abaFuncao'>
    <form id="form-comissao-funcao" name="form-comissao-funcao" method="post" action="" >
        <input type="text" name="input-sequencial-funcao" id="input-sequencial-comissao-funcao" style="display: none">
        <fieldset class="container">
            <legend>Funções</legend>
            <table class="form-container">
                <tr>
                    <td nowrap title="Descrição">
                        Função:
                    </td>
                    <td>
                        <select name="funcoes" id="funcoes">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Descrição">
                        Sessões na Competência:
                    </td>
                    <td>
                        <?php db_input('comissao-funcao-quantidade', 50, 1, true, 'text', 1); ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Tipo de Ação">
                        Tipo:
                    </td>
                    <td>
                        <select name="Tipo" id="incluir-alterar-funcao">
                            <option value="incluir">Inclusão</option>
                            <option value="alterar">Alteração</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <br/>
        <input name="incluirComissaoFuncao" type="button" id="incluir-comissao-funcao" value="Incluir">
    </form>
    <form>
        <fieldset>
            <legend>Manutenção de Funções</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <div id="container-funcao"></div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>

<!-- ************************************************************************************************************
     ********************************** Aba de Rubrica da comissão *************************************
     ************************************************************************************************************ -->
<div class="container" id='abaRubrica'>
    <form id="form-rubrica" name="form-rubrica" method="post" action="" >
        <input type="text" name="input-sequencial-rubrica" id="input-sequencial-rubrica" style="display: none">
        <fieldset class="container">
            <legend>Rubricas</legend>
            <table class="form-container">
                <tr>
                    <td nowrap title="Descrição da Rubrica">
                        <?php db_ancora("Rubrica",'pesquisaRubrica(true);',1);?>
                    </td>
                    <td>
                        <?php
                        db_input("rubrica-codigo", 8, "Código da Rubrica",true,'text',1,"onchange='pesquisaRubrica(false);'");
                        db_input("rubrica-descricao",40,"Descrição da Rubrica",true,'text',3);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Descrição do tipo de Sessão">
                        Tipo de Sessão:
                    </td>
                    <td>
                        <select name="tiposessao" id="rubrica-tipo-sessao">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Descrição da Função">
                        Função:
                    </td>
                    <td>
                        <select name="rubrica-funcao" id="rubrica-funcao">
                            <option value="">Selecione</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Valor da Sessão">
                        Valor da Sessão:
                    </td>
                    <td>
                        <?php db_input('rubrica-valor', 50, 4, true, 'text', 1); ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Tipo de Ação">
                        Tipo:
                    </td>
                    <td>
                        <select name="Tipo" id="incluir-alterar-rubrica">
                            <option value="incluir">Inclusão</option>
                            <option value="alterar">Alteração</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <br/>
        <input name="incluirComissaoRubrica" type="button" id="incluir-rubrica" value="Incluir">
    </form>
    <form>
        <fieldset>
            <legend>Manutenção das Rubricas</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <div id="container-rubrica"></div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
<!-- ************************************************************************************************************
     ********************************** Aba de Servidores da comissão *************************************
     ************************************************************************************************************ -->
<div class="container" id='abaServidor'>
    <form id="form4" name="form4" method="post" action="" >
        <input type="text" name="input-sequencial" id="input-sequencial-servidor" style="display: none">
        <fieldset class="container" style="width:400px;">
            <legend>Servidores</legend>
            <table class="form-container">
                <tr>
                    <td title="Matricula">
                        <?php db_ancora('Matricula','js_pesquisa(true);', 1);?>
                    </td>
                    <td nowrap>
                        <?php
                        db_input("input-matricula",8, 1, true ,'text',1,"onchange='js_pesquisa(false);'");
                        db_input("input-nome",40,true,'text',3);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        Mes Inicio:
                    <td>
                        <?php db_input("input-mes-inicio",8,'',1,true,'text',1); ?>
                        <strong>&nbsp;Ano Inicio:<strong/>
                            <?php db_input("input-ano-inicio",8, '',1,true,'text',1); ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        Mes Final:
                    <td>
                        <?php db_input("input-mes-final",8,'',1,true,'text',1); ?>
                        <strong>&nbsp;Ano Final:&nbsp;</strong>
                        <?php db_input("input-ano-final",8,'',1,true,'text',1); ?>
                    </td>
                </tr>
                <tr id="trAtivo" style="display: none">
                    <td nowrap>
                        Ativo
                    </td>

                    <td>
                        <select name="servidorAtivo" id="input-servidor-ativo"  />
                        <option value="">Selecione</option>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        Função do Servidor
                    </td>

                    <td>
                        <select name="funcaoServidor" id="input-funcao-servidor" />
                        </select>
                    </td>
                </tr>
                <tr>
                    <td nowrap>
                        Ata de Nomeação
                    </td>

                    <td>
                        <textarea rows="3" name="ataNomeacao" id="input-ata-nomeacao" style="min-height: 0"></textarea>
                    </td>
                </tr>
                <tr>
                    <td nowrap title="Tipo de Ação">
                        Tipo:
                    </td>

                    <td>
                        <select name="Tipo" id="incluir-alterar-servidor">
                            <option value="incluir">Inclusão</option>
                            <option value="alterar">Alteração</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <br/>
        <input name="incluirComissaoServidor" type="button" id="incluirComissaoServidor" value="Incluir">
    </form>

    <!-- Form da grid -->
    <form>
        <fieldset>
            <legend>Manutenção de Servidores</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <div id="container-servidores-comissao"></div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</div>
</body>
<?php
db_menu(
    db_getsession("DB_id_usuario"),
    db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),
    db_getsession("DB_instit")
);

?>
</html>
<script rel="script" type="text/javascript" src="scripts/session.js"></script>

<script type="text/javascript">
    /**
     * Variaveis
     * /
     /**
     * Cria abas
     */
    const oDBAba = new DBAbas($('ctnAbas'));
    const oAbaComissao = oDBAba.adicionarAba("Comissão", $('abaComissao'));
    const oAbaPermissao = oDBAba.adicionarAba("Permissão", $('abaPermissao'));
    const oAbaTipoSessao = oDBAba.adicionarAba("Tipo de Sessão", $('abaTipoSessao'));
    const oAbaFuncao = oDBAba.adicionarAba("Funções", $('abaFuncao'));
    const oAbaRubrica = oDBAba.adicionarAba("Rubricas", $('abaRubrica'));
    const oAbaServidor = oDBAba.adicionarAba("Servidores", $('abaServidor'));
    /**
     * Urls
     */
    const urlBase = "<?php echo ECIDADE_REQUEST_PATH;?>" + 'v4/api/recursos-humanos/pessoal/jetom';
    const url =  urlBase + "/comissao";

    const codigoInstituicao = "<?php echo db_getsession('DB_instit');?>";
    const btnComissao = document.getElementById('incluir-comissao');
    const btnIncluirComissaoFuncao = document.getElementById('incluir-comissao-funcao');
    const btnIncluirRubrica = document.getElementById('incluir-rubrica');
    const btnIncluirTipoSessao = document.getElementById('incluir-tiposessao');
    const checkBuscaTodasComissoes = document.getElementById('checkBuscaComissoes');


    // INPUT DOS SERVIDORES
    const inputSequencialServidor = document.getElementById('input-sequencial-servidor');
    const inputMatriculaServidor = document.getElementById('input-matricula');
    const inputNomeServidor = document.getElementById('input-nome');
    const inputMesInicio = document.getElementById('input-mes-inicio');
    const inputAnoInicio = document.getElementById('input-ano-inicio');
    const inputMesFinal = document.getElementById('input-mes-final');
    const inputAnoFim = document.getElementById('input-ano-final');
    const inputServidorAtivo = document.getElementById('input-servidor-ativo');
    const inputAtaNomeacao = document.getElementById('input-ata-nomeacao');
    const inputFuncaoServidor = document.getElementById('input-funcao-servidor');
    const inputIncluirAlterarServidor = document.getElementById('incluir-alterar-servidor');
    //BTN INCLUIR SERVIDOR COMISSAO
    const btnIncluirServidorComissao = document.getElementById('incluirComissaoServidor');


    /**
     * Inputs
     */
    const inputDescricao = document.getElementById("descricao");
    const inputDataInicioComissao = document.getElementById("datainicio");
    const inputDataFimComissao = document.getElementById("datafim");
    const inputSequencialComissao = document.getElementById('input-sequencial-comissao');
    const inputSequencialComissaoFuncao = document.getElementById('input-sequencial-comissao-funcao');
    const inputSequencialTipoSessao = document.getElementById('input-sequencial-tiposessao');
    const inputSequencialRubrica = document.getElementById('input-sequencial-rubrica');
    const inputOpcaoComissao = document.getElementById('incluir-alterar-comissao');
    const inputOpcaoComissaoFuncao = document.getElementById('incluir-alterar-funcao');
    const inputOpcaoRubrica = document.getElementById('incluir-alterar-rubrica');
    const inputFuncao = document.getElementById('funcoes');
    const inputComissaoFuncaoQuantidade = document.getElementById('comissao-funcao-quantidade');
    const inputRubricaCodigo = document.getElementById('rubrica-codigo');
    const inputRubricaDescricao = document.getElementById('rubrica-descricao');
    const inputOpcaoRubricaTipoSessao = document.getElementById('rubrica-tipo-sessao');
    const inputOpcaoComissaoTipoSessao = document.getElementById('comissao-tipo-sessao');
    const inputOpcaoComissaoTipoSessaoTipoInclusao = document.getElementById('incluir-alterar-tiposessao');
    const inputTipoSessaoQuantidade = document.getElementById('tiposessao-quantidade');

    const inputOpcaoRubricaFuncao = document.getElementById('rubrica-funcao');
    const inputRubricaValor = document.getElementById('rubrica-valor');
    const inputComissaoPermissao = document.querySelector('#comissoes-permissao');
    const btnIncluirComissaoPermissao = document.getElementById('incluir-permissao');
    const inputIncluirAlterarComissaoPermissao = document.getElementById('incluir-alterar-comissaoPermissao');
    const inputSequencialComissaoPermissao = document.getElementById('input-sequencial-permissao');

    /**
     * Containers
     */
    const containerComissao = document.getElementById('container-comissao');
    const containerComissaoFuncao = document.getElementById('container-funcao');
    const containerComissaoRubrica = document.getElementById('container-rubrica');
    const containerTipoSessao = document.getElementById('container-tiposessao');

    // CONTAINER SERVIDORES
    const containerServidoresComissao = document.getElementById('container-servidores-comissao');
    const containerComissaoPermissao = document.getElementById('container-permissao');

    /**
     * Collections
     */
    const collectionComissao = new Collection().setId('sequencial');
    const collectionComissaoFuncao = new Collection().setId('sequencial');
    const collectionRubrica = new Collection().setId('sequencial');
    const collectionTipoSessao = new Collection().setId('sequencial');
    // COLLECTION SERVIDORES
    const collectionServidoresComissao = new Collection().setId('sequencial');
    const collectionComissaoPermissao = new Collection().setId('sequencial');


    /**
     * Grids
     */
    const gridComissao = DatagridCollection.create(collectionComissao).configure({'order': false, height: 150});
    const gridComissaoFuncao = DatagridCollection.create(collectionComissaoFuncao).configure({'order': false, height: 150});
    const gridRubrica = DatagridCollection.create(collectionRubrica).configure({'order': false, height: 150});
    const gridTipoSessao = DatagridCollection.create(collectionTipoSessao).configure({'order': false, height: 150});
    const gridComissaoPermissao = DatagridCollection.create(collectionComissaoPermissao).configure({'order': false, height: 150});

    // GRID SERVIDORES
    const gridServidoresComissao = DatagridCollection.create(collectionServidoresComissao).configure({'order': false, height: 250});

    /**
     * Colunas das grids
     */


    /**
     * COLUNA SERVIDORES
     */
    gridServidoresComissao.addColumn('sequencial', {label: 'Código', align: 'center', width: '10%'});
    gridServidoresComissao.addColumn('matricula', {label: 'Matrícula', align: 'center', width: '15%'});
    gridServidoresComissao.addColumn('nome', {label: 'Nome', align: 'center', width: '30%'});
    gridServidoresComissao.addColumn('funcao', {label: 'Função', align: 'center', width: '30%'});
    gridServidoresComissao.addColumn('mesInicio', {label: 'Mes Inicio', align: 'center', width: '5%'});
    gridServidoresComissao.addColumn('mesFinal', {label: 'Mes Final', align: 'center', width: '5%'});
    gridServidoresComissao.addColumn('anoInicio', {label: 'Ano Inicio', align: 'center', width: '5%'});
    gridServidoresComissao.addColumn('anoFinal', {label: 'Ano Final', align: 'center', width: '5%'});
    gridServidoresComissao.addColumn('ativo', {label: 'Ativo', align: 'center', width: '5%'});
    gridServidoresComissao.addColumn('funcaocodigo', {label: 'Ativo', align: 'center', width: '5%'});
    gridServidoresComissao.hideColumns([0, 4, 5, 6, 7, 8, 9]);

    gridComissao.addColumn('sequencial', {label: 'Código', align: 'center', width: '1%'});
    gridComissao.addColumn('descricao', {label: 'Descrição', align: 'left', width: '40%'});
    gridComissao.addColumn('datainicio', {label: 'Data Inicio', align: 'center', width: '20%'});
    gridComissao.addColumn('datafim', {label: 'Data Fim', align: 'center', width: '20%'});
    gridComissao.hideColumns([0])

    gridComissaoFuncao.addColumn('sequencial', {label: 'Código', align: 'center', width: '11%'});
    gridComissaoFuncao.addColumn('funcao', {label: 'Código Função', display: 'center', width: '1%'});
    gridComissaoFuncao.addColumn('descricao', {label: 'Descrição', align: 'center', width: '55%'});
    gridComissaoFuncao.addColumn('quantidade', {label: 'Sessão/Comp.', align: 'center', width: '20%'});
    gridComissaoFuncao.hideColumns([0, 1]);

    gridRubrica.addColumn('sequencial', {label: 'Código', align: 'center', width: '10%'});
    gridRubrica.addColumn('funcao', {label: 'Código Função', display: 'center', width: '1%'});
    gridRubrica.addColumn('tiposessao', {label: 'Código Função', display: 'center', width: '1%'});
    gridRubrica.addColumn('rubrica', {label: 'Rubrica', align: 'center', width: '20%'});
    gridRubrica.addColumn('funcaodescricao', {label: 'Função', align: 'center', width: '20%'});
    gridRubrica.addColumn('rubricadescricao', {label: 'Rubrica', align: 'center', width: '1%'});
    gridRubrica.addColumn('tiposessaodescricao', {label: 'Tipo de Sessão', align: 'center', width: '20%'});
    gridRubrica.addColumn('valor', {label: 'Valor', align: 'center', width: '12%'});

    gridRubrica.hideColumns([0, 1, 2, 5]);


    gridTipoSessao.addColumn('sequencial', {label: 'Código', align: 'center', width: '10%'});
    gridTipoSessao.addColumn('tiposessao', {label: 'Código do Tipo de Sessão', align: 'center', width: '1%'});
    gridTipoSessao.addColumn('descricao', {label: 'Tipo de Sessão', align: 'center', width: '40%'});
    gridTipoSessao.addColumn('quantidade', {label: 'Máx. de Sessão Mensal', align: 'center', width: '25%'});
    gridTipoSessao.hideColumns([1]);

    gridComissaoPermissao.addColumn('matricula', {label: 'Matrícula', align: 'center', width: '25%'});
    gridComissaoPermissao.addColumn('nome', {label: 'Nome', align: 'center', width: '50%'});




    // ALTERAR SERVIDOR
    gridServidoresComissao.addAction('Alterar', 'Alterar', (event, linha) => {
        if (linha.ativo === false) {
            inputServidorAtivo.value = 0;
        } else {
            inputServidorAtivo.value = 1;
        }
        inputNomeServidor.value = linha.nome;
        inputSequencialServidor.value = linha.sequencial;
        inputMatriculaServidor.value = linha.matricula;
        inputMesInicio.value = linha.mesInicio;
        inputMesFinal.value = linha.mesFinal;
        inputAnoInicio.value = linha.anoInicio;
        inputAnoFim.value = linha.anoFinal;
        inputFuncaoServidor.value = linha.func;
        inputIncluirAlterarServidor.value = 'Alterar';
        btnIncluirServidorComissao.value = 'Alterar';
        inputMatriculaServidor.focus();
        labelServidorComissao('alterar');
    }, true, 'fa-edit');


    // Alterar Permissões
    gridComissaoPermissao.addAction('Alterar', 'Alterar', (event, linha) => {
        inputSequencialComissaoPermissao.value = linha.sequencial
        inputMatriculaPermissao.value = linha.matricula
        inputComissaoPermissao.value = linha.comissao
        inputNamePermissao.value = linha.nome
        btnIncluirComissaoPermissao.value = 'Alterar'
        inputIncluirAlterarComissaoPermissao.value = "Alterar"
        labelComissaoPermissao('alterar');
    }, true, 'fa-edit');

    //Alteracao das grids
    gridComissao.addAction('Alterar', 'Alterar', (event, linha) => {
        inputSequencialComissao.value = linha.sequencial;
        inputDescricao.value = linha.descricao;
        inputDataInicioComissao.value = linha.datainicio;
        inputDataFimComissao.value = linha.datafim;
        inputOpcaoComissao.value = 'alterar';
        btnComissao.value = 'Alterar';
        inputDescricao.focus();
        liberaAbas();
        resetaCampos();
        gridServidoresComissao.clear();
    }, true, 'fa-edit');


    gridComissaoFuncao.addAction('Alterar', 'Alterar', (event, linha) => {
        inputFuncao.value = linha.funcao;
        inputSequencialComissaoFuncao.value = linha.sequencial;
        inputComissaoFuncaoQuantidade.value = linha.quantidade;
        labelComissaoFuncao('alterar');
    }, true, 'fa-edit');

    gridRubrica.addAction("Alterar", "Alterar", (event, linha) => {
        inputRubricaCodigo.value = linha.rubrica;
        inputRubricaDescricao.value = linha.rubricadescricao;
        inputOpcaoRubricaTipoSessao.value = linha.tiposessao;
        inputOpcaoRubricaFuncao.value = linha.funcao;
        inputRubricaValor.value = linha.valor;
        inputSequencialRubrica.value = linha.sequencial;
        labelRubrica('alterar');
    }, true, 'fa-edit');

    gridTipoSessao.addAction("Alterar", "Alterar", (event, linha) => {
        inputTipoSessaoQuantidade.value = linha.quantidade;
        inputSequencialTipoSessao.value = linha.sequencial;
        inputOpcaoComissaoTipoSessao.value = linha.tiposessao;
        labelTipoSessao('alterar');
    }, true, 'fa-edit');

    //Exclusão das grids
    // EXCLUSÃO GRID SERVIDOR
    gridServidoresComissao.addAction('Excluir', 'Excluir', (event, linha) => {
        if (confirm(`Deseja excluir o servidor da comissao: ${linha.nome}?`)) {
            const data = new FormData();
            PHPSession.appendFormData(data);

            data.append('id', linha.sequencial);

            HttpClient.post(url + '/servidor/delete', {body: data}).then(response => {
                alert(response.message);
                if (response.error) {
                    return false;
                } else {
                    collectionServidoresComissao.remove(linha.sequencial);
                }

                gridServidoresComissao.reload();
            });
        }
    }, true, 'fa-trash');

    gridComissaoPermissao.addAction('Excluir', 'Excluir', (event, linha) => {

        if (confirm(`Deseja excluir a matricula ${linha.matricula} da permissão?`)) {
            const data = new FormData();
            PHPSession.appendFormData(data);
            data.append('id', linha.sequencial);
            HttpClient.post(url + '/permissao/delete', {body: data}).then(response => {
                alert(response.message);
                if (response.error) {
                    return false
                } else {
                    collectionComissaoPermissao.remove(linha.sequencial);
                }

                gridComissaoPermissao.reload()
            });
        }
    }, true, 'fa-trash');



    gridComissao.addAction('Excluir', 'Excluir', (event, linha) => {
        if (confirm(`Deseja excluir a comissão: ${linha.descricao}?`)) {
            const data = new FormData();
            PHPSession.appendFormData(data);
            data.append('id', linha.sequencial);

            HttpClient.post(url + '/delete', {body: data}).then(response => {
                alert(response.message);
                if (response.error) {
                    return false;
                } else {
                    collectionComissao.remove(linha.sequencial);
                }

                gridComissao.reload();
            });
        }
    }, true, 'fa-trash');

    gridComissaoFuncao.addAction('Excluir', 'Excluir', (event, linha) => {
        if (confirm(`Deseja excluir a função: ${linha.descricao}?`)) {
            const data = new FormData();
            PHPSession.appendFormData(data);
            data.append('id', linha.sequencial);

            HttpClient.post(urlBase + '/comissao/funcao/delete', {body: data}).then(response => {
                alert(response.message);
                if (response.error) {
                    return false;
                }
                buscaComissaoFuncao();
            });
        }
    }, true, 'fa-trash');

    gridRubrica.addAction('Excluir', 'Excluir', (event, linha) => {
        if (confirm(`Deseja excluir a Rubrica: ${linha.rubricadescricao} da função: ${linha.funcaodescricao} do tipo de sessão: ${linha.tiposessaodescricao}?`)) {
            const data = new FormData();
            PHPSession.appendFormData(data);
            data.append('id', linha.sequencial);

            HttpClient.post(urlBase + '/comissao/config/delete', {body: data}).then(response => {
                alert(response.message);
                if (response.error) {
                    return false;
                }
                buscaRubrica();
            });
        }
    }, true, 'fa-trash');

    gridTipoSessao.addAction('Excluir', 'Excluir', (event, linha) => {
        if (confirm(`Deseja excluir o Tipo de Sessão: ${linha.descricao} da comissão?`)) {
            const data = new FormData();
            PHPSession.appendFormData(data);
            data.append('id', linha.sequencial);

            HttpClient.post(url + '/tiposessao/delete', {body: data}).then(response => {
                alert(response.message);
                if (response.error) {
                    return false;
                } else {
                    collectionTipoSessao.remove(linha.sequencial);
                }
                gridTipoSessao.reload();
            });
        }
    }, true, 'fa-trash');


    /**
     * Controle de Abas
     */
    function liberaAbas () {
        if (inputSequencialComissao.value !== "") {
            buscaComissaoFuncao();
            buscaServidorComissao();
            buscaRubrica();
            buscaComissaoTipoSessao();
            buscaPermissaoComissao();
            oAbaFuncao.desbloquear();
            oAbaRubrica.desbloquear();
            oAbaServidor.desbloquear();
            oAbaTipoSessao.desbloquear();
            oAbaPermissao.desbloquear();
        } else {
            oAbaFuncao.bloquear();
            oAbaRubrica.bloquear();
            oAbaServidor.bloquear();
            oAbaTipoSessao.bloquear();
            oAbaPermissao.bloquear();
        }
    }


    // CHANGE SERVIDOR
    inputIncluirAlterarServidor.addEventListener('change', () => {
        if (inputIncluirAlterarServidor.value == 'alterar') {
            btnIncluirServidorComissao.value = "Alterar";
        } else {
            btnIncluirServidorComissao.value = "Incluir";
            inputSequencialServidor.value = "";
            resetaCamposServidor();
            servidorAtivo();
        }
    });

    inputIncluirAlterarComissaoPermissao.addEventListener('change', () => {
        if (inputIncluirAlterarComissaoPermissao.value == 'alterar') {
            btnIncluirComissaoPermissao.value = "Alterar";
        } else {
            btnIncluirComissaoPermissao.value = "Incluir";
            inputSequencialComissaoPermissao.value = "";
        }
    });

    inputOpcaoComissao.addEventListener('change', () => {
        if (inputOpcaoComissao.value == 'alterar') {
            btnComissao.value = "Alterar";
        } else {
            btnComissao.value = "Incluir";
            inputSequencialComissao.value = "";
        }
        liberaAbas();
    });

    inputOpcaoComissaoFuncao.addEventListener('change', () => {
        if (inputOpcaoComissaoFuncao.value == 'alterar') {
            labelComissaoFuncao('alterar');
        } else {
            labelComissaoFuncao('incluir');
        }
        liberaAbas();
    });

    inputOpcaoRubrica.addEventListener('change', () => {
        if (inputOpcaoRubrica.value == 'alterar') {
            labelRubrica('alterar');
        } else {
            labelRubrica('incluir');
        }
    });

    inputOpcaoComissaoTipoSessaoTipoInclusao.addEventListener('change', () => {
        if (inputOpcaoComissaoTipoSessaoTipoInclusao.value == 'alterar') {
            labelTipoSessao('alterar');
        } else {
            labelTipoSessao('incluir');
        }
    });


    btnIncluirComissaoPermissao.addEventListener('click', () => {
        const formulario = new FormData();
        PHPSession.appendFormData(formulario);
        formulario.append('id', inputSequencialComissaoPermissao.value)
        formulario.append('matricula', inputMatriculaPermissao.value);
        formulario.append('comissao', inputComissaoPermissao.value);

        if (inputIncluirAlterarComissaoPermissao.value == 'Incluir' || inputIncluirAlterarComissaoPermissao.value == 'incluir' )
        {
            HttpClient.post(urlBase + '/comissao/permissao', {body: formulario})
                .then(response => {
                    buscaPermissaoComissao();
                    alert(response.message);
                    js_removeObj("msgBox");
                    inputSequencialComissaoPermissao.value = response.data.id;
                    liberaAbas();
                    if (response.error) {
                        return false;
                    } else {
                        labelComissaoPermissao('alterar');

                    }
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        } else {
            HttpClient.post(
                urlBase + '/comissao/permissao/update',
                {body: formulario}
            )
                .then(response => {
                    buscaPermissaoComissao();
                    alert(response.message);
                    js_removeObj("msgBox");
                    return false;
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        }
    })
    // AJAX SERVIDORES
    btnIncluirServidorComissao.addEventListener('click', () => {
        const formulario = new FormData();
        PHPSession.appendFormData(formulario);
        formulario.append('matricula', inputMatriculaServidor.value);
        formulario.append('comissao', inputSequencialComissao.value);
        formulario.append('mesinicio', inputMesInicio.value);
        formulario.append('mesfim', inputMesFinal.value);
        formulario.append('anoinicio', inputAnoInicio.value);
        formulario.append('anofim', inputAnoFim.value);
        formulario.append('ativo', inputServidorAtivo.value);
        formulario.append('atonomeacao', inputAtaNomeacao.value);
        formulario.append('funcao', inputFuncaoServidor.value);
        js_divCarregando('Aguarde...', 'msgBox');

        if (!validate()) {
            alert("Matricula invalida.");
            js_removeObj('msgBox');
            return false;
        }
        function validate()
        {
            if (formulario.get('input-matricula') == '') {
                labelServidorComissao('incluir');
                return false;
            }

            inputMesInicio.addEventListener("change", function(){
                const num = Number(inputMesInicio.value);
                if (num < 1 || num > 12) {
                    labelServidorComissao('incluir');
                    alert("O mês inicial precisa ser entre 1 e 12.");
                    js_removeObj('msgBox');
                    return false;
                }
            } );

            inputMesFinal.addEventListener("change", function(){
                const num = Number(inputMesFinal.value);
                if (num < 1 || num > 12) {
                    labelServidorComissao('incluir');
                    alert("O mês final precisa ser entre 1 e 12.");
                    js_removeObj('msgBox');
                    return false;
                }
            } );

            return true;
        }

        if (inputIncluirAlterarServidor.value == 'Incluir' || inputIncluirAlterarServidor.value == 'incluir' )
        {
            HttpClient.post(urlBase + '/comissao/servidor', {body: formulario})
                .then(response => {
                    buscaServidorComissao();
                    alert(response.message);
                    js_removeObj("msgBox");
                    inputSequencialServidor.value = response.data.id;
                    liberaAbas();
                    if (response.error) {
                        return false;
                    } else {
                        labelServidorComissao('alterar');

                    }
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        } else {
            formulario.append('id', inputSequencialServidor.value);

            if (inputServidorAtivo.value === "") {
                alert('É necessário selecionar se o servidor está ativo ou inativo.');
                js_removeObj('msgBox');
                return false;
            }
            HttpClient.post(url + "/servidor/update", {body: formulario})
                .then(response => {
                    buscaServidorComissao();
                    alert(response.message);
                    js_removeObj("msgBox");
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                })
        }
    });

    btnComissao.addEventListener('click', () => {
        var form_element = document.getElementById('form-comissao');
        var formulario = new FormData(form_element);
        PHPSession.appendFormData(formulario);
        formulario.append('instituicao', codigoInstituicao);

        js_divCarregando('Aguarde...', 'msgBox');

        if (!validate()) {
            js_removeObj("msgBox");
            return false;
        }

        function validate() {
            if (formulario.get('descricao') === '') {
                alert("Descrição inválida.");
                return false;
            }

            if (formulario.get('input-data-inicio-comissao') === '') {
                alert("A data de inicio não pode ficar em branco.");
                return false;
            }

            if (formulario.get('input-data-fim-comissao') === '') {
                alert("A data de final não pode ficar em branco.");
                return false;
            }

            if (formulario.get('datainicio') === '' || formulario.get('datafim') === '') {
                alert("As datas não podem ficar em branco.")
                return false;
            }


            var dataInicio = formulario.get('datainicio');
            dataInicio = dataInicio.split("/");
            dataInicio = dataInicio[1]+"/"+dataInicio[0]+"/"+dataInicio[2];
            dataInicio = new Date(dataInicio).getTime();

            var dataFim = formulario.get('datafim');
            dataFim = dataFim.split("/");
            dataFim = dataFim[1]+"/"+dataFim[0]+"/"+dataFim[2];
            dataFim = new Date(dataFim).getTime();

            const result = dataFim < dataInicio;


            if (result) {
                alert("A data fim não pode ser menor que a data inicio.");
                return false;
            }

            return true;
        }

        if (inputOpcaoComissao.value === 'incluir') {
            HttpClient.post(url + '/save', {body: formulario})
                .then(response => {
                    buscaComissao();
                    alert(response.message);
                    js_removeObj("msgBox");
                    inputSequencialComissao.value = response.data.id;
                    liberaAbas();
                    inputOpcaoComissao.value = 'alterar';
                    btnComissao.value = 'Alterar';
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        } else {
            formulario.append('id', inputSequencialComissao.value);
            HttpClient.post(
                url + '/update',
                {body: formulario}
            )
                .then(response => {
                    buscaComissao();
                    alert(response.message);
                    js_removeObj("msgBox");
                    return false;
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        }
    });

    btnIncluirComissaoFuncao.addEventListener('click', () => {
        var formulario = new FormData();
        PHPSession.appendFormData(formulario);
        formulario.append("quantidade", inputComissaoFuncaoQuantidade.value);
        formulario.append("comissao", inputSequencialComissao.value);
        formulario.append("funcao", inputFuncao.value);

        js_divCarregando('Aguarde...', 'msgBox');

        if (!validate()) {
            js_removeObj("msgBox");
            return false;
        }

        function validate() {
            if (formulario.get('quantidade') === '') {
                alert("Quantidade não informada.");
                return false;
            }
            if (formulario.get('comissao') === '') {
                alert("Comissão não informada.");
                return false;
            }
            if (formulario.get('funcao') === '') {
                alert("Função não informada.");
                return false;
            }
            return true;
        }

        if (inputOpcaoComissaoFuncao.value == 'incluir') {
            HttpClient.post(urlBase + '/comissao/funcao', {body: formulario})
                .then(response => {
                    buscaComissaoFuncao();
                    alert(response.message);
                    js_removeObj("msgBox");
                    if (response.error) {
                        return false;
                    }
                    inputSequencialComissaoFuncao.value = response.data.id;
                    liberaAbas();
                    labelComissaoFuncao('alterar');
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        } else {
            formulario.append('id', inputSequencialComissaoFuncao.value);
            HttpClient.post(
                urlBase + '/comissao/funcao/update',
                {body: formulario}
            )
                .then(response => {
                    buscaComissaoFuncao();
                    alert(response.message);
                    js_removeObj("msgBox");
                    return false;
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        }
    });

    btnIncluirRubrica.addEventListener('click', () => {
        js_divCarregando('Aguarde...', 'msgBox');

        if (!validate()) {
            js_removeObj("msgBox");
            return false;
        }

        var formulario = new FormData();
        PHPSession.appendFormData(formulario);
        formulario.append("comissao", inputSequencialComissao.value);
        formulario.append("funcao", inputOpcaoRubricaFuncao.value);
        formulario.append('rubrica', inputRubricaCodigo.value);
        formulario.append('tiposessao', inputOpcaoRubricaTipoSessao.value);
        formulario.append('valor', inputRubricaValor.value);


        function validate() {
            if (inputRubricaCodigo.value === "") {
                alert("Rubrica não informada.");
                return false;
            }
            if (inputOpcaoRubricaTipoSessao.value === "") {
                alert("É necessário selecionar o tipo de sessão.");
                return false;
            }
            if (inputOpcaoRubricaFuncao.value === "") {
                alert("É necessário selecionar a função.");
                return false;
            }
            if (inputRubricaValor.value === "") {
                alert("É necessário o valor da Rubrica.");
                return false;
            }
            return true;
        }

        if (inputOpcaoRubrica.value === 'incluir') {
            HttpClient.post(urlBase + '/comissao/config', {body: formulario})
                .then(response => {
                    buscaComissaoFuncao();
                    alert(response.message);
                    js_removeObj("msgBox");
                    if (response.error) {
                        return false;
                    }
                    inputSequencialRubrica.value = response.data.id;
                    liberaAbas();
                    labelComissaoFuncao('alterar');
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        } else {
            formulario.append('id', inputSequencialRubrica.value);
            HttpClient.post(
                urlBase + '/comissao/config/update',
                {body: formulario}
            )
                .then(response => {
                    alert(response.message);
                    js_removeObj("msgBox");
                    if (response.error) {
                        return false;
                    }
                    buscaRubrica();
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        }
    });

    btnIncluirTipoSessao.addEventListener('click', () => {
        js_divCarregando('Aguarde...', 'msgBox');


        if (!validate()) {
            js_removeObj("msgBox");
            return false;
        }

        var formulario = new FormData();
        PHPSession.appendFormData(formulario);
        formulario.append("comissao", inputSequencialComissao.value);
        formulario.append("tiposessao", inputOpcaoComissaoTipoSessao.value);
        formulario.append('quantidade', inputTipoSessaoQuantidade.value);

        function validate() {
            if (inputOpcaoComissaoTipoSessao.value === "") {
                alert("Tipo de Sessão não informado.");
                return false;
            }
            if (inputTipoSessaoQuantidade.value === "") {
                alert("É necessário informar a quantidade máxima de sessões.");
                return false;
            }
            return true;
        }

        if (inputOpcaoComissaoTipoSessaoTipoInclusao.value === 'incluir') {
            HttpClient.post(urlBase + '/comissao/tiposessao', {body: formulario})
                .then(response => {
                    buscaComissaoTipoSessao();
                    alert(response.message);
                    js_removeObj("msgBox");
                    if (response.error) {
                        return false;
                    }
                    inputSequencialTipoSessao.value = response.data.id;
                    liberaAbas();
                    labelTipoSessao('alterar');
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        } else {
            formulario.append('id', inputSequencialTipoSessao.value);
            HttpClient.post(
                urlBase + '/comissao/tiposessao/update',
                {body: formulario}
            )
                .then(response => {
                    alert(response.message);
                    js_removeObj("msgBox");
                    if (response.error) {
                        return false;
                    }
                    buscaComissaoTipoSessao();
                })
                .catch(error => {
                    alert(error.message);
                    js_removeObj("msgBox");
                    return false;
                });
        }
    });

    const adicionaTipoSessaoCollection = (tiposessao) => {
        collectionTipoSessao.add({
            sequencial : tiposessao.codigo,
            descricao : tiposessao.descricao,
            tiposessao : tiposessao.tiposessao,
            quantidade : tiposessao.quantidade
        });
    };

    const adicionaComissaoCollection = (comissao) => {

        dataItemComissaoInicio = new Date(`${comissao.rh242_datainicio} 12:00`).getDateBR()
        dataItemComissaofim = new Date(`${comissao.rh242_datafim} 12:00`).getDateBR()

        collectionComissao.add({
            sequencial: comissao.rh242_sequencial,
            descricao: comissao.rh242_descricao,
            datainicio: dataItemComissaoInicio,
            datafim: dataItemComissaofim
        })
    };

    const adicionaFuncaoCollection = (funcao) => {
        collectionFuncao.add({
            sequencial: funcao.codigo,
            funcao: funcao.funcao,
            descricao: funcao.descricao,
            quantidade: funcao.quantidade
        })
    };

    const adicionaComissaoFuncaoCollection = (funcao) => {
        collectionComissaoFuncao.add({
            sequencial: funcao.codigo,
            funcao: funcao.funcao,
            descricao: funcao.descricao,
            quantidade: funcao.quantidade
        });
    };

    const adicionaRubricaCollection = (rubrica) => {
        collectionRubrica.add({
            sequencial : rubrica.codigo,
            funcao : rubrica.funcao,
            tiposessao : rubrica.tiposessao,
            rubrica : rubrica.rubrica,
            funcaodescricao : rubrica.funcaodescricao,
            rubricadescricao : rubrica.rubricadescricao,
            tiposessaodescricao : rubrica.tiposessaodescricao,
            valor : rubrica.valor
        });
    };

    const adicionaComissaoPermissaoCollection = (permissao) => {
        collectionComissaoPermissao.add({
            sequencial: permissao.rh251_sequencial,
            matricula: permissao.rh251_matricula,
            comissao: permissao.comissao,
            nome: permissao.nome
        });
    };

    //ADICIONA SERVIDOR COMISSAO COLLECTION
    const adicionaServidorComissaoCollection = (servidorComissao) => {
        collectionServidoresComissao.add({
            sequencial: servidorComissao.codigo,
            nome: servidorComissao.nome,
            comissaoseq: servidorComissao.comissao,
            matricula: servidorComissao.matricula,
            funcao: servidorComissao.funcao,
            mesInicio: servidorComissao.mesinicio,
            mesFinal: servidorComissao.mesfim,
            anoInicio: servidorComissao.anoinicio,
            anoFinal: servidorComissao.anofim,
            ativo: servidorComissao.ativo,
            func: servidorComissao.funcaocodigo,
        });
    };

    // BUSCA SERVIDOR COMISSAO
    const buscaServidorComissao = () => {
        if (inputSequencialComissao.value !== "") {
            HttpClient.get(url + '/servidor/?comissao=' + inputSequencialComissao.value).then(response => {
                response.data.map(servidorComissao => adicionaServidorComissaoCollection(servidorComissao));
                gridServidoresComissao.reload();
            });
        }
    };

    const buscaComissao = () => {

        HttpClient.get(url + '/?instituicao='+codigoInstituicao + "&buscaTodasComissoes=false").then(response => {

            response.data.map(comissao => {
                const opcao = document.createElement("option");
                opcao.value = comissao.rh242_sequencial;
                opcao.text = comissao.rh242_descricao;
                adicionaComissaoCollection(comissao)
                adicionaComissaoCollection(comissao)
            });
            gridComissao.reload();
        });

        checkBuscaTodasComissoes.addEventListener('click', function() {
            if (this.checked) {
                gridComissao.clear()
                HttpClient.get(url + '/?instituicao='+codigoInstituicao + "&buscaTodasComissoes=true").then(response => {

                    response.data.map(comissao => {
                        const opcao = document.createElement("option");
                        opcao.value = comissao.rh242_sequencial;
                        opcao.text = comissao.rh242_descricao;
                        adicionaComissaoCollection(comissao)
                        adicionaComissaoCollection(comissao)
                    });
                    gridComissao.reload();
                });
            } else {
                gridComissao.clear()
                HttpClient.get(url + '/?instituicao='+codigoInstituicao + "&buscaTodasComissoes=false").then(response => {

                    response.data.map(comissao => {
                        const opcao = document.createElement("option");
                        opcao.value = comissao.rh242_sequencial;
                        opcao.text = comissao.rh242_descricao;
                        adicionaComissaoCollection(comissao)
                        adicionaComissaoCollection(comissao)
                    });
                    gridComissao.reload();
                });
            }
        })

    };

    const buscaFuncao = () => {
        HttpClient.get(urlBase + '/funcao/?instituicao='+codigoInstituicao).then(response => {
            response.data.map(funcao => adicionaFuncaoOption(funcao));
        });
    };

    const buscaComissaoFuncao = () => {
        if (inputSequencialComissao.value !== "") {
            HttpClient.get(urlBase + '/comissao/funcao/?comissao=' + inputSequencialComissao.value).then(response => {
                gridComissaoFuncao.clear();
                response.data.map(funcao => adicionaComissaoFuncaoCollection(funcao));
                gridComissaoFuncao.reload();
                selectRubricaFuncao(response);
                //SELECT SERVIDOR FUNCAO
                selectServidorFuncao(response);
            });
        }
    };

    // SERVIDOR ATIVO
    const servidorAtivo = () => {
        inputServidorAtivo.style.display = '';
    };

    const selectServidorFuncao = (retorno) => {
        var opcao = document.createElement('option');
        inputFuncaoServidor.innerHTML = "";

        opcao.value = "";
        opcao.text = "Selecione";
        inputFuncaoServidor.append(opcao);

        if (retorno.error) {
            return false;
        }
        retorno.data.map((servidorFuncao) => {
            var opcao = document.createElement('option');
            opcao.value = servidorFuncao.funcao;
            opcao.text = servidorFuncao.descricao;
            inputFuncaoServidor.append(opcao);
        })
    };

    const selectRubricaFuncao = (retorno) => {

        var opcao = document.createElement("option");

        inputOpcaoRubricaFuncao.innerHTML = "";

        opcao.value = "";
        opcao.text = "Selecione";
        inputOpcaoRubricaFuncao.append(opcao);

        if (retorno.error) {
            return false;
        }
        retorno.data.map((rubricaFuncao) => {
            var opcao = document.createElement("option");
            opcao.value = rubricaFuncao.funcao;
            opcao.text = rubricaFuncao.descricao;
            inputOpcaoRubricaFuncao.append(opcao);
        });
    };


    const adicionaFuncaoOption = (funcao) => {
        opcao = document.createElement("option");
        opcao.value = funcao.codigo;
        opcao.text = funcao.descricao;
        inputFuncao.append(opcao);
    };

    const buscaRubrica = () => {
        if (inputSequencialComissao.value !== "") {
            HttpClient.get(urlBase + '/comissao/config/?comissao=' + inputSequencialComissao.value).then(response => {
                gridRubrica.clear();
                response.data.map(rubrica => adicionaRubricaCollection(rubrica));
                gridRubrica.reload();
            });
        }
    };

    const buscaPermissaoComissao = () => {
        if (inputSequencialComissao.value !== "") {
            HttpClient.get(urlBase + '/comissao/permissao/?comissao=' + inputSequencialComissao.value).then(response => {

                // console.log('buscaPermissaoComissao', response.data[0]);

                const opcao = document.createElement("option");
                opcao.value = response.data[0].comissao;
                opcao.text = response.data[0].descricao;
                inputComissaoPermissao.remove(inputComissaoPermissao.child)
                inputComissaoPermissao.append(opcao)

                gridComissaoPermissao.clear();
                response.data.map(permissao => adicionaComissaoPermissaoCollection(permissao))
                gridComissaoPermissao.reload();
            });
        }
    };

    const buscaComissaoTipoSessao = () => {
        if (inputSequencialComissao.value !== "") {
            HttpClient.get(urlBase + '/comissao/tiposessao/?comissao=' + inputSequencialComissao.value).then(response => {
                gridTipoSessao.clear();
                response.data.map(tiposessao => adicionaTipoSessaoCollection(tiposessao));
                gridTipoSessao.reload();
            });
        }
    };

    /**
     * Load das grids
     */
    gridComissao.show(containerComissao);
    gridComissaoFuncao.show(containerComissaoFuncao);
    gridRubrica.show(containerComissaoRubrica);
    gridTipoSessao.show(containerTipoSessao);
    gridComissaoPermissao.show(containerComissaoPermissao);
    // SHOW GRID SERVIDORES
    gridServidoresComissao.show(containerServidoresComissao);

    /**
     * Load das funcoes
     */
    buscaComissao();
    buscaPermissaoComissao();
    buscaFuncao();
    liberaAbas();
    buscaTipoSessao();
    buscaServidorComissao();

    function resetaCampos() {
        resetaFuncao();
        resetaPermissaoComissao();
    }

    //SERVIDOR RESETA CAMPOS
    function resetaCamposServidor()
    {
        resetaFuncaoServidor();
    }

    const labelComissaoFuncao = (tipo) => {
        if (tipo === 'incluir') {
            inputOpcaoComissaoFuncao.value = "incluir";
            btnIncluirComissaoFuncao.value = "Incluir";
        } else {
            inputOpcaoComissaoFuncao.value = "alterar";
            btnIncluirComissaoFuncao.value = "Alterar";
        }
    };

    // LABEL SERVIDOR
    const labelServidorComissao = (tipo) => {
        const inputServidorAtivo = document.getElementById('trAtivo');

        if (tipo === 'incluir') {
            inputIncluirAlterarServidor.value = "incluir";
            btnIncluirServidorComissao.value = "Incluir";
            inputServidorAtivo.style.display = 'none';
        } else {
            inputIncluirAlterarServidor.value = "alterar";
            btnIncluirServidorComissao.value = "Alterar";
            inputServidorAtivo.style.display = '';
        }
    };

    function pesquisaRubrica(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rubrica','func_rhrubricas.php?funcao_js=parent.mostraRubrica1|rh27_rubric|rh27_descr','Pesquisa',true);
        } else {
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rubrica','func_rhrubricas.php?pesquisa_chave='+inputRubricaCodigo.value+'&funcao_js=parent.mostraRubrica','Pesquisa',false);
        }
    }

    function mostraRubrica(chave, erro) {
        inputRubricaDescricao.value = chave;
        if (erro == true) {
            inputRubricaCodigo.focus();
            inputRubricaCodigo.value = '';
        }
    }

    function mostraRubrica1(chave1, chave2) {
        inputRubricaCodigo.value = chave1;
        inputRubricaDescricao.value = chave2;
        db_iframe_rubrica.hide();
    }

    function buscaTipoSessao() {
        HttpClient.get(urlBase + '/tiposessao/all').then(response => {
            response.data.map((tiposessao) => {
                const opcao = document.createElement("option");
                opcao.value = tiposessao.rh240_sequencial;
                opcao.text = tiposessao.rh240_descricao;
                const opcao2 = document.createElement("option");
                opcao2.value = tiposessao.rh240_sequencial;
                opcao2.text = tiposessao.rh240_descricao;
                inputOpcaoRubricaTipoSessao.append(opcao);
                inputOpcaoComissaoTipoSessao.append(opcao2);
            });
        });
    }
    const resetaFuncao = () => {
        inputComissaoFuncaoQuantidade.value = 1;
        inputFuncao.value = "";
        labelComissaoFuncao("incluir");
    };

    const resetaPermissaoComissao = () => {
        inputComissaoPermissao.value = "";
        inputMatriculaPermissao.value = "";
        inputNamePermissao.value = "";
        labelComissaoPermissao("incluir");
    };

    // RESETA FUNCAO SERVIDOR

    const resetaFuncaoServidor = () => {
        inputNomeServidor.value = '';
        inputMatriculaServidor.value = '';
        inputMesInicio.value = '';
        inputMesFinal.value = '';
        inputAnoInicio.value = '';
        inputAnoFim.value = '';
        inputServidorAtivo.value = '';
        inputFuncaoServidor.value = '';
        inputAtaNomeacao.value = '';
        labelServidorComissao('incluir');
    };

    const resetaRubrica = () => {
        inputSequencialRubrica.value = "";
        inputRubricaCodigo.value = "";
        inputRubricaDescricao.value = "";
        inputOpcaoRubricaTipoSessao.value = "";
        inputOpcaoRubricaFuncao.value = "";
        inputRubricaValor.value = "";
        labelRubrica("incluir");
    };

    const labelRubrica = (tipo) => {
        if (tipo === 'incluir') {
            inputOpcaoRubrica.value = "incluir";
            btnIncluirRubrica.value = "Incluir";
        } else {
            inputOpcaoRubrica.value = "alterar";
            btnIncluirRubrica.value = "Alterar";
        }
    };

    const labelComissaoPermissao = (tipo) => {
        if (tipo === 'incluir') {
            inputIncluirAlterarComissaoPermissao.value = "incluir";
        } else {
            inputIncluirAlterarComissaoPermissao.value = "alterar";
        }
    }

    const labelTipoSessao = (tipo) => {
        if (tipo === 'incluir') {
            inputOpcaoComissaoTipoSessaoTipoInclusao.value = "incluir";
            btnIncluirTipoSessao.value = "Incluir";
        } else {
            inputOpcaoComissaoTipoSessaoTipoInclusao.value = "alterar";
            btnIncluirTipoSessao.value = "Alterar";
        }
    };

    // FUNÇÃO ANCORA ABA PERMISSAO
    var inputMatriculaPermissao = document.getElementById("input-matricula-permissao");
    var inputNamePermissao = document.getElementById("input-nome-permissao");

    function js_pesquisaPermi(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostraregistPermi1|rh01_regist|z01_nome','Pesquisa',true);
        }else{
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+inputMatriculaPermissao.value+'&funcao_js=parent.js_mostraregistPermi','Pesquisa',false);
        }
    }

    function js_mostraregistPermi(chave,erro) {
        inputNamePermissao.value = chave;
        if (erro==true)
        {
            inputMatriculaPermissao.focus();
            inputMatriculaPermissao.value = '';
        }
    }

    function js_mostraregistPermi1(chave1,chave2){
        inputMatriculaPermissao.value = chave1;
        inputNamePermissao.value   = chave2;
        db_iframe_rhpessoal.hide();
    }

    // FUNÇÃO ANCORA ABA SERVIDOR
    var inputMatricula = document.getElementById("input-matricula");
    var inputName = document.getElementById("input-nome");

    function js_pesquisa(mostra){
        if(mostra==true){
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostraregist1|rh01_regist|z01_nome','Pesquisa',true);
        }else{
            js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+inputMatricula.value+'&funcao_js=parent.js_mostraregist','Pesquisa',false);
        }
    }

    function js_mostraregist(chave,erro) {
        inputName.value = chave;
        if (erro==true)
        {
            inputMatricula.focus();
            inputMatricula.value = '';
        }
    }

    function js_mostraregist1(chave1,chave2){
        inputMatricula.value = chave1;
        inputNomeServidor.value   = chave2;
        db_iframe_rhpessoal.hide();
    }
</script>
