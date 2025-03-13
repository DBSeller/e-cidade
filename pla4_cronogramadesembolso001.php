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
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <script rel="script" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
<div class="alert alert-primary text-left" role="alert">
    Selecione o plano e o exercício que deseja projetar o cronograma de desembolso.
</div>
<div class="container">
    <fieldset>
        <legend>Cronograma de desembolso da receita</legend>
        <table class="form-container">
            <tr>
                <td><label class="bold" for="planejamento">Planejamento:</label></td>
                <td>
                    <select id="planejamento" class="field-size8">
                        <option value="">Selecione um plano</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label class="bold" for="exercicio">Exercício:</label></td>
                <td>
                    <select id="exercicio" class="field-size8">
                    </select>
                </td>
            </tr>
        </table>
    </fieldset>
    <button type="button" id="calcular" class="btn btn-light">
        <i class="fas fa-calculator"></i>
        Calcular
    </button>
</div>

</body>
<script type="text/javascript" src="scripts/session.js"></script>
<script type="text/javascript" src="scripts/classes/planejamento/planejamento.js"></script>
<script type="text/javascript">

    const cboExercicio = document.getElementById('exercicio');
    const planejamento = new Planejamento(document.getElementById('planejamento'));

    const get = planejamento._getURLParameters(window.location.search);
    let url = 'pla4_cronogramareceita002.php';
    if (get.cronograma === 'despesa') {
        url = 'pla4_cronogramadespesa002.php';
    }

    planejamento.load();
    planejamento.getElement().addEventListener('change', () => {
        cboExercicio.options.length = 0
        if (planejamento.getValue() === '') {
            return;
        }

        const plano = planejamento.getPlano();
        for (let anoInicial = plano.pl2_ano_inicial; anoInicial <= plano.pl2_ano_final; anoInicial++) {
            cboExercicio.add(new Option(anoInicial, anoInicial));
        }
    });

    const validarForm = () => {
        try {
            if (planejamento.getValue() === '') {
                throw 'Selecione o plano';
            }

            if (cboExercicio.value === '') {
                throw 'Selecione o exercício';
            }
        } catch (e) {
            alert(e);
            return false;
        }
        return true;
    }
    document.getElementById('calcular').addEventListener('click', () => {
        if (!validarForm()) {
            return;
        }
        location.href = `${url}?plano=${planejamento.getValue()}&exercicio=${cboExercicio.value}`;
    });
</script>
</html>
