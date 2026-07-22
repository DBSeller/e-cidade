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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta charset="iso-8859-1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link type="text/css" href="grid.style.css" rel="styleshet">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script rel="script" type="text/javascript" src="scripts/prototype.js"></script>
    <script rel="script" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
</head>

<body>
    <div class="container">
        <fieldset>
            <legend>Lista de Pacientes por Ação Programática</legend>
            <div id="lancadorProgramas" style="width: 600px;"></div>
            <table>
                <tr>
                    <td style="text-align: right;">
                        <b>Somente Totalizadores:</b>
                    </td>
                    <td>
                        <select name='totalizador' id='totalizador' style="width: 90px;">
                            <option value='0' selected>Não</option>
                            <option value='1'>Sim</option>
                        </select>
                    </td>
                </tr>
                <tr style="text-align: left;">
                    <td style="text-align: right;">
                        <b>Ordenar por:</b>
                    </td>
                    <td>
                        <select name='ordem' id='ordem'>
                            <option value='1' selected>Nome</option>
                            <option value='2'>CGS</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>
        <button name="emite2" id="emite2" type="button" onclick="gerarRelatorio();">
            <i class="fas fa-print"></i>
            Imprimir
        </button>
    </div>
    <?php db_menu(); ?>
</body>

</html>
<script>
    const selectOrdem = document.getElementById('ordem');
    const selectTotalizador = document.getElementById('totalizador');
    

    var lancadorProgramas = new DBLancador('lancadorProgramas');
    lancadorProgramas.iGridHeight = 100;
    lancadorProgramas.selecionarAposPesquisar = true;
    lancadorProgramas.setNomeInstancia('lancadorProgramas');
    lancadorProgramas.setLabelAncora('Ação Programática:');
    lancadorProgramas.setTextoFieldset('Filtrar Programas');
    lancadorProgramas.setParametrosPesquisa('func_far_programa.php', ['fa12_i_codigo', 'fa12_c_descricao']);
    lancadorProgramas.show(document.getElementById('lancadorProgramas'));

    function gerarRelatorio() {
        if (lancadorProgramas.getRegistros().length == 0) {
            alert('Selecione ao menos um programa.');
            return false;
        }

        let programas = lancadorProgramas.getRegistros().map(programa => {
            return programa.sCodigo;
        });

        let ordem = '&ordem=' + selectOrdem.value;
        let totalizador = "";

        if (selectTotalizador.value == 1) {
            totalizador = "&somenteTotalizador";
        }

        programas = 'programas=' + programas.join(',');

        let features = 'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0';
        window.open('far2_pacienteprogramatica002.php?' + programas + ordem + totalizador, '', features);
    }
</script>
