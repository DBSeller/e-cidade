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
    <link rel="stylesheet" type="text/css" href="estilos.css"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.core.css"
          rel="stylesheet"/>
    <link type="text/css" href="extension/package/Desktop/assets/vendors/alertify/themes/alertify.bootstrap.css"
          rel="stylesheet"/>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
</head>
<body>
<div class="alert alert-primary text-left" role="alert">
    Os valores do PIB são atrelados a <b>LDO</b>. Apenas após cadastrar uma LDO será possível informar o PIB.
</div>

<div class="container">
    <fieldset>
        <legend>Manutenção da projeção do PIB</legend>
        <table class="form-container">
            <tr class="text-left">
                <td><label class="bold" for="planejamento">LDO:</label></td>
                <td colspan="3">
                    <select id="planejamento" class="field-size8">
                        <option value="">Selecione uma LDO</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label class="bold" for="ano_inicial">Ano inicial:</label></td>
                <td>
                    <input type="text" name="ano_inicial" id="ano_inicial" class="field-size2 readonly" readonly>
                </td>
                <td><label class="bold" for="ano_final">Ano final:</label></td>
                <td>
                    <input type="text" name="ano_final" id="ano_final" class="field-size2 readonly" readonly>
                </td>
            </tr>
        </table>

        <fieldset class="separator ">
            <legend>Valores previstos</legend>
            <div id="containerValores"></div>
        </fieldset>
    </fieldset>
    <button id="salvar" type="button">
        <i class="far fa-save"></i>
        Salvar
    </button>
</body>

<?php db_menu() ?>

<script type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>
<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
<script type="text/javascript" src="scripts/classes/planejamento/valores.js"></script>
<script type="text/javascript">
    const routs = {
        plano: 'financeiro/planejamento/consulta/plano',
        show: 'financeiro/planejamento/pib',
        store: 'financeiro/planejamento/pib'
    };

    var plano = {};
    const planejamento = new Planejamento(document.getElementById('planejamento'));
    const anoInicial = document.getElementById('ano_inicial');
    const anoFinal = document.getElementById('ano_final');
    const containerValores = document.getElementById('containerValores');
    const btnSalvar = document.getElementById('salvar');

    valoresPib = new Valores();
    planejamento.load();

    planejamento.getElement().addEventListener('change', () => {
        valoresPib.reset();
        plano = {};
        anoInicial.value = '';
        anoFinal.value = '';
        if (planejamento.getValue() === '') {
            return;
        }

        HttpClient.get(`${PHPSession.requestApi}/${routs.plano}/${planejamento.getValue()}`).then(response => {
            plano = response.data;
            anoInicial.value = plano.pl2_ano_inicial;
            anoFinal.value = plano.pl2_ano_final;

            // Os relatórios da LDO necessitam do PIB de até três anos anteriores.
            plano.pl2_ano_inicial = plano.pl2_ano_inicial-3
            valoresPib.criaInputValores(containerValores, plano);
            HttpClient.get(`${PHPSession.requestApi}/${routs.show}/${plano.pl2_codigo}`).then(response => {
                for (let valor of response.data) {
                    valoresPib.set(valor.pl10_ano, valor.pl10_valor);
                }
            });
        })
    });


    const valida = () => {
        try {
            if (planejamento.getValue() === '') {
                throw 'Selecione uma LDO.';
            }

            if (valoresPib.existeValoresNaoInformados()) {
                throw 'Você deve informar todos valores';
            }
        } catch (e) {
            alert(e);
            return false;
        }

        return true;
    };

    btnSalvar.addEventListener('click', () => {

        if (!valida()) {
            return;
        }

        const formData = new FormData();
        formData.append('planejamento_id', plano.pl2_codigo)
        formData.append('valores', JSON.stringify(valoresPib.getValores()));
        PHPSession.appendFormData(formData);

        HttpClient.post(`${PHPSession.requestApi}/${routs.store}`, {body: formData}).then(response => {
            alert(response.message);
            if (response.error) {
                return;
            }
        });
    });
</script>
