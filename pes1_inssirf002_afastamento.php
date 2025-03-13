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

use ECidade\RecursosHumanos\Pessoal\Repository\AfastamentoSituacaoRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        select#afastamento {
            width: 250px;
        }
    </style>
</head>
<body data-codigo-tabela="<?php echo !empty($codtab) ? $codtab : '' ?>">

<div class="container">
    <table class="form-container" style="width: 300px; margin: auto auto 5px;">
        <tr>
            <td nowrap title="Afastamentos">
                <label style="font-weight: bold" for="r45_situac">Afastamentos:</label>
            </td>
            <td>
                <?php
                $dao = new cl_situacaoafastamento();
                $repository = new AfastamentoSituacaoRepository($dao);
                $repository->scopeSequencial('NOT IN (2, 4, 7)', '');
                $situacoes = array();

                foreach ($repository->get() as $situacao) {
                    $situacoes[$situacao->getSequencial()] = "{$situacao->getSequencial()} - {$situacao->getDescricao()}";
                }
                db_select('afastamento', $situacoes, false, 1);
                ?>
            </td>
        </tr>
    </table>
    <form class="form-container">
        <fieldset>
            <legend>Rubricas para afastamento</legend>
            <div id="toggleRubricas"></div>
        </fieldset>
        <input id="btnSalvarVinculo" type="button" value="Salvar">
    </form>
</div>

<script>
    const afastamentoSituacao = document.getElementById('afastamento');
    const btnSalvarVinculo = document.getElementById('btnSalvarVinculo');
    const bodyAfastamento = document.getElementById('body-previdencia');
    const codigoTabelaPrevidenciaAfastamento = bodyAfastamento.getAttribute('data-codigo-tabela');

    const toggleRubricas = new DBToggleList([
        {sId: 'rubrica', sLabel: 'Rubrica', sWidth: '50px', sAlign: 'center'},
        {sId: 'descricao', sLabel: 'Descrição', sWidth: '300px', sAlign: 'center'},
        {sId: 'tipo', sLabel: 'Tipo', sWidth: '80px', sAlign: 'center'}
    ], null, '');
    toggleRubricas.closeOrderButtons();
    toggleRubricas.show(document.querySelector('#toggleRubricas'));
    toggleRubricas.oGridSelect.setPesquisa(0, true);
    toggleRubricas.oGridSelected.setPesquisa(0, true);

    afastamentoSituacao.addEventListener('change', () => {
        toggleRubricas.clearAll();
        toggleRubricas.renderRows();
        buscarRubricas();
    });

    btnSalvarVinculo.addEventListener('click', () => {

        const data = new FormData();
        data.append('acao', 'salvarVinculo');
        data.append('afastamento', afastamentoSituacao.value);
        data.append('rubricas', JSON.stringify(toggleRubricas.getSelected()));
        data.append('codigoTabela', codigoTabelaPrevidenciaAfastamento);

        HttpClient.post('pes1_inssirf002.RPC.php', {body: data}).then(response => {

            if (response.erro) {
                return alert(response.mensagem);
            }

        });

    });

    const buscarRubricas = () => {
        const data = new FormData();
        data.append('acao', 'buscarRubricas');
        data.append('codigoTabela', codigoTabelaPrevidenciaAfastamento);
        data.append('tipoAfastamento', afastamentoSituacao.value);

        HttpClient.post('pes1_inssirf002.RPC.php', {body: data}).then(response => {

            if (response.erro) {
                return alert(response.mensagem);
            }

            response.rubricas.map(rubrica => {
                toggleRubricas.addSelect({
                    'rubrica': rubrica.codigo,
                    'descricao': rubrica.descricao,
                    'tipo': rubrica.tipoDescricao
                });
            });

            response.rubricasSelecionadas.map(controle => {
                toggleRubricas.addSelected({
                    'rubrica': controle.rubrica.codigo,
                    'descricao': controle.rubrica.descricao,
                    'tipo': controle.rubrica.tipoDescricao
                });
            });

            toggleRubricas.renderRows();
        });
    };

    buscarRubricas();

</script>

</body>
</html>
