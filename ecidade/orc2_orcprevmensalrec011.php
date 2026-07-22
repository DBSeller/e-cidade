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
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
$oGet = db_utils::postMemory($_GET);
$codrel = $oGet->iCodRel;

$aux_recursos = new cl_arquivo_auxiliar;
$clrotulo = new rotulocampo();
$clrotulo->label("o05_ppaversao");
$clrotulo->label("o124_sequencial");
$clrotulo->label("o124_descricao");

$db_opcao = 1;
?>

<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/ppaUserInterface.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table align="center" width="550">
    <form name="form1" method="post" action="">
        <tr>
            <td align="center" colspan="3">
                <fieldset>
                    <legend>
                        <b>Filtros</b>
                    </legend>
                    <table>
                        <tr>
                            <td nowrap title="<?= @$To05_ppalei ?>">
                                <?php
                                db_ancora("<b>Perspectiva</b>", "js_pesquisao124_sequencial(true);", $db_opcao);
                                ?>
                            </td>
                            <td nowrap>
                                <?php
                                db_input('o124_sequencial', 10, $Io124_sequencial, true, 'text', $db_opcao, " onchange='js_pesquisao124_sequencial(false);'");
                                db_input('o124_descricao', 40, $Io124_descricao, true, 'text', 3, '');
                                db_input('codrel', 40, '', true, 'hidden', 3, '');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <fieldset>
                                    <legend>Recursos</legend>
                                    <table class="form-container">
                                        <tr>
                                            <td title="<?= @$To15_recurso ?>">
                                                <a id="ancoraFonteRecurso" href="#">Fonte de Recursos:</a>
                                            </td>
                                            <td >
                                                <input type="text" id="o15_recurso" name="o15_recurso">
                                                <input type="text" id="o15_descr" name="o15_descr" class="readonly field-size8" readonly>
                                            </td>
                                        </tr>
                                    </table>
                                    <div id="ctnLancadorRecursos"></div>
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" colspan="2">
                                <fieldset style="width: 500px;">
                                    <legend><b>Opções</b></legend>
                                    <table>
                                        <tr>
                                            <td><b>Forma de Impressão:</b></td>
                                            <td>
                                                <?php
                                                $x = array('1' => 'Por Receita', '2' => 'Por Recurso');
                                                db_select('iFormaImpr', $x, 1, 1);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><b>Periodicidade:</b></td>
                                            <td>
                                                <?php
                                                $x = array('1' => 'Mensal', '2' => 'Bimestral');
                                                db_select('iPeriodoImpr', $x, 1, 1);
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <?php db_selinstit('', 300, 100); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td align="center">
                <input name="emite" id="emite" type="button" value="Visualizar" onclick="js_emite();">
            </td>
        </tr>
    </form>
</table>
</body>
</html>
<script>
    const collectionRecurso = new Collection().setId('codigo');
    var gridRecursos = new DatagridCollection(collectionRecurso).configure({
        order: false,
        height: 200
    });

    const lookUpRecurso = new DBLookUp($('ancoraFonteRecurso'), $('o15_recurso'), $('o15_descr'), {
        'sArquivo': 'func_fonterecursocomplemento.php',
        'sLabel': 'Pesquisar Fonte de Recurso',
        'sObjetoLookUp': "db_iframe_orctiporec",
        'aCamposAdicionais': ['db_codigo', 'o200_descricao']
    });

    lookUpRecurso.setCallBack('onClick', (retorno) => {
        preencheCollection(retorno[0], retorno[1], retorno[2], retorno[3]);
    });

    lookUpRecurso.setCallBack('onChange', (erro, retorno) => {
        if (erro) {
            return;
        }

        preencheCollection(retorno[3], retorno[0], retorno[2], retorno[4]);
    });

    gridRecursos.addColumn('recurso', {label: "Fonte", width: '10%', align: 'center'});
    gridRecursos.addColumn('descricao', {label: "Recurso", width: '45%'});
    gridRecursos.addColumn('complemento', {label: "Complemento", width: '30%'});
    gridRecursos.addAction('Remover', 'Remover', (event, linha) => {
        collectionRecurso.remove(linha.codigo);
        gridRecursos.reload();
    }, true, 'fa-trash');
    gridRecursos.show($('ctnLancadorRecursos'));

    const preencheCollection = (recurso, descricao, id, complemento) => {
        collectionRecurso.add({
            "codigo" : id,
            "recurso" : recurso,
            "descricao" : descricao,
            "complemento" : complemento
        });

        gridRecursos.reload();
        $('o15_recurso').value = '';
        $('o15_descr').value = '';
    };

    function js_pesquisao124_sequencial(mostra) {
        if (mostra == true) {
            js_OpenJanelaIframe('',
                'db_iframe_cronogramaperspectiva',
                'func_cronogramaperspectiva.php?funcao_js=parent.js_mostrappalei1|o124_sequencial|o124_descricao',
                'Pesquisa',
                true);
        } else {
            if (document.form1.o124_sequencial.value != '') {
                js_OpenJanelaIframe('',
                    'db_iframe_cronogramaperspectiva',
                    'func_cronogramaperspectiva.php?pesquisa_chave='
                    + document.form1.o124_sequencial.value + '&funcao_js=parent.js_mostrappalei',
                    'Pesquisa',
                    false);
            } else {
                document.form1.o124_descricao.value = '';
            }
        }
    }

    function js_mostrappalei(chave, erro) {

        document.form1.o124_descricao.value = chave;
        if (erro == true) {
            document.form1.o124_sequencial.focus();
            document.form1.o124_sequencial.value = '';
        } else {
            js_getVersoesPPA($F('o124_sequencial'));
        }
    }

    function js_mostrappalei1(chave1, chave2) {
        document.form1.o124_sequencial.value = chave1;
        document.form1.o124_descricao.value = chave2;
        db_iframe_cronogramaperspectiva.hide();
    }

    function js_emite() {

        var doc = document.form1;

        if (doc.db_selinstit.value == '') {
            alert('Nenhum instituição selecionada');
            return false;
        }

        if (doc.o124_sequencial.value == '') {
            alert('Nenhum Perspectiva selecionado!');
            return false;
        }

        if (doc.codrel.value == '') {
            alert('Código do relatório não informado!');
            return false;
        }

        let recursos = collectionRecurso.build().map((recurso) => {
            return recurso.codigo;
        });

        var sQuery = '?iRec=' + doc.o124_sequencial.value;
        sQuery += '&sListaInstit=' + doc.db_selinstit.value;
        sQuery += '&iCodRel=' + doc.codrel.value;
        sQuery += '&slistaRecursos=' + recursos.toString();
        sQuery += '&iPeriodoImpr=' + $F('iPeriodoImpr');
        sQuery += '&iFormaImpr=' + $F('iFormaImpr');

        var jan = window.open('orc2_orcprevmensalrec002.php' + sQuery, '', 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
        jan.moveTo(0, 0);
    }

</script>
