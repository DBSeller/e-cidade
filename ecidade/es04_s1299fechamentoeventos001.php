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
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");

?>

<html lang="pt-BR">
<head>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DBSeller Informática Ltda</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script src="scripts/scripts.js" rel="script" type="text/javascript"></script>
    <script src="scripts/strings.js" rel="script" type="text/javascript"></script>
    <script src="scripts/prototype.js" rel="script" type="text/javascript"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script src="scripts/object.js" rel="script" type="text/javascript"></script>
    <script src="scripts/widgets/DBLookUp.widget.js" rel="script" type="text/javascript"></script>
</head>
<body>
<form action="sped02_preenchimento.php" class="container" id="form1">
    <input type="hidden" value="2" name="integracao" id="integracao">
    <input type="hidden" value="31" name="formularioTipo" id="formularioTipo">
    <fieldset>
        <legend>Indicativo de Período</legend>
        <table class="form-container">
            <tr id="tr_empregador" class="d-none">
                <td><label for="empregador">Empregador:</label></td>
                <td><select name="empregador" id="empregador"></select></td>
            </tr>
            <tr>
                <td><label>Indicativo de Período:</label></td>
                <td>
                    <select name="indicativoPeriodo" id="indicativoPeriodo">
                        <option value="1">Mensal (AAAA-MM)</option>
                        <option value="2">Anual (AAAA)</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label>Período:</label></td>
                <td><input type="text" name="periodo" id="periodo" maxlength="7" class="field-size2"></td>
            </tr>
        </table>
    </fieldset>
    <input type="button" value="Salvar" id="proximo" name="proximo" onclick="return validaPeriodos()">
</form>
</body>
<?php db_menu(); ?>
<script rel="script" type="text/javascript">

    const INTEGRACAO = 2;
    const trEmpregador = document.getElementById('tr_empregador');
    const selectEmpregador = document.getElementById('empregador');

    const inicializar = () => {
        const formData = new FormData();
        formData.append('acao', 'inicializar');
        formData.append('integracao', INTEGRACAO);

        HttpClient.post('sped02_preenchimento.RPC.php', {
            body: formData
        }).then(response => {
            if (response.erro) {
                throw response.mensagem;
            }

            response.empregadores.map((empregadorOption, chave) => {
                const selecionado = chave === 0;

                selectEmpregador.add(
                    new Option(empregadorOption.nome, empregadorOption.cgm),
                    selecionado,
                    selecionado
                );
            });
            trEmpregador.classList.remove('d-none');
        }).catch(mensagem => alert(mensagem));
    };

    const validaPeriodos = () => {

        try {
            const periodo = $F('periodo');
            if (empty(periodo)) {
                throw 'Informe o período.';
            }
            if ($F('indicativoPeriodo') == 1 && periodo.match(/^(19[0-9][0-9]|2[0-9][0-9][0-9])[-/](0?[1-9]|1[0-2])/) == null) {
                throw 'Período informado é incompatível com Indicativo de Período selecionado.';
            }

            if ($F('indicativoPeriodo') == 2 && periodo.length > 4) {
                throw 'Período informado é incompatível com Indicativo de Período selecionado.';
            }

            if ($F('indicativoPeriodo') == 2 && periodo.match(/^(19[0-9][0-9]|2[0-9][0-9][0-9])/) == null) {
                throw 'Período informado é incompatível com Indicativo de Período selecionado.';
            }
        } catch (e) {
            alert(e);
            return false;
        }


        $('form1').submit();
        return true;
    };


    (function () {
        inicializar();
    })();
</script>
