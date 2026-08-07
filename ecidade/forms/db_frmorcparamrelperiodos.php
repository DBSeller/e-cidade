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

//MODULO: orcamento
$clorcparamrelperiodos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");
?>

<div class="container">
    <form name="form" id="form">
        <fieldset>
            <legend>Períodos do Relatório</legend>
            <table class="form-container">
                <tr>
                    <td>
                        <label for="o113_orcparamrel">Código do relátorio:</label>
                    </td>
                    <td>
                        <?php
                        db_input(
    'o113_orcparamrel',
    8,
    $Io113_orcparamrel,
    true,
    'text',
    3,
    " onchange='js_pesquisao113_orcparamrel(false);'"
                        );
                        db_input('o42_descrrel', 50, $Io42_descrrel, true, 'text', 3, '')
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <fieldset class="separator">
                            <legend>Períodos</legend>
                            <div id='ctnPeriodos'></div>
                        </fieldset>
                    </td>
                </tr>
            </table>
        </fieldset>
        <input type="button" name="btnSalvar" id="btnSalvar" value="Salvar">
    </form>
</div>
<script type="text/javascript">

    var urlRPC = 'con4_relatorioslegais.RPC.php';
    const urlParams = new URLSearchParams(window.location.search);
    var opcao = urlParams.has('db_opcaoal') ? urlParams.get('db_opcaoal') : null;
    var periodosCollection = new Collection().setId('codigo');
    var gridPeriodos = DatagridCollection.create(periodosCollection).configure({'order': false});
    gridPeriodos.addColumn('codigo', {label: 'Código', 'width': '95%'}).setOption('align', 'center');
    gridPeriodos.addColumn('descricao', {label: 'Descrição', 'width': '95%'}).setOption('align', 'left');
    gridPeriodos.getGrid().setCheckbox(0);
    gridPeriodos.hideColumns([1]);
    gridPeriodos.show($('ctnPeriodos'));

    (function() {
        new AjaxRequest(
            urlRPC,
            {
                'exec': 'buscarPeriodosRelatorio',
                'codigoRelatorio': $F('o113_orcparamrel')
            },
            function(retorno, erro) {

                if (erro) {

                    alert(retorno.message.urlDecode());
                    return false;
                }

                gridPeriodos.clear();

                for (var periodo of retorno.periodos) {
                    periodosCollection.add(periodo);
                    if (periodo.periodo_relatorio == 1) {
                        gridPeriodos.addSelectedItens(periodo.codigo);
                    }
                }
                gridPeriodos.reload();
            }).setMessage('Aguarde, buscando períodos...').execute();

        if (opcao == '33') {
            $('btnSalvar').disabled = true;
        }

    })();

    $('btnSalvar').onclick = function() {

        if (empty($F('o113_orcparamrel'))) {
            alert('Código do relatório não informado.');
            return false;
        }

        var parametros = {
            'exec': 'salvarPeriodosRelatorio',
            'codigoRelatorio': $F('o113_orcparamrel'),
            'periodos': []
        };

        var periodosSelecionados = gridPeriodos.getGrid().getSelection();
        for (periodo of periodosSelecionados) {
            parametros.periodos.push(periodo[0]);
        }

        new AjaxRequest(
            urlRPC,
            parametros,
            function(retorno, erro) {
                alert(retorno.message.urlDecode());
            }).setMessage('Aguarde, salvando períodos...').execute();
    };

</script>