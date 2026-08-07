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
require_once modification('libs/db_app.utils.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_usuariosonline.php');
require_once modification('dbforms/db_funcoes.php');

$lDbOpcao      = 1;
$codigo_escola = db_getsession('DB_coddepto');
?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load('scripts.js, prototype.js');
    db_app::load('estilos.css, grid.style.css');
    ?>
</head>
<body class="body-container">
<div class="container">
    <form name="form1" id='frmDiarioClasse' method="post">
        <div style='display:table;' id='ctnForm'>
            <fieldset>
                <legend style="font-weight: bold">Livro de Matrícula</legend>
                <label for="cmb_calendario">Ano:</label>
                <select class="field-size6" id="cmb_calendario" name="cmb_calendario">
                    <?php
//                    $sql = "select calendario.ed52_i_codigo,
//                                calendario.ed52_c_descr,
//                                duracaocal.ed55_c_descr,
//                                calendario.ed52_i_ano,
//                                calendario.ed52_i_periodo,
//                                calendario.ed52_d_inicio,
//                                calendario.ed52_d_fim,
//                                calendario.ed52_c_passivo
//                            from calendario
//                            inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal
//                            inner join calendarioescola  on  calendarioescola.ed38_i_calendario = calendario.ed52_i_codigo
//                            where  ed38_i_escola = $codigo_escola
//                            AND ed52_i_codigo not in (0)
//                            order by ed52_c_descr limit 15 offset 0";

                    $sql = "select calendario.ed52_i_ano
                            from calendario
                            inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal
                            inner join calendarioescola  on  calendarioescola.ed38_i_calendario = calendario.ed52_i_codigo
                            where  ed38_i_escola = $codigo_escola
                            group by ed52_i_ano order by ed52_i_ano desc";

                    $result_calendario = pg_query($conn, $sql);

                    while ($row_calendario = pg_fetch_array($result_calendario)) {
                        $codigo    = $row_calendario['ed52_i_codigo'];
                        $ano       = $row_calendario['ed52_i_ano'];
                        $descricao = $row_calendario['ed52_c_descr'];

                        echo "<option value='$ano'>$ano</option>";
                    }

                    ?>

                </select>
            </fieldset>
        </div>

        <button type="button" id="btnImprimir">
            <i class="fas fa-print"></i> Imprimir
        </button>
    </form>
</div>
<?php
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), db_getsession('DB_anousu'), db_getsession('DB_instit'));
?>
</body>

<script type="text/javascript">

    $('btnImprimir').observe('click', function () {

        var calendario = document.getElementById("cmb_calendario").value;

        var sUrlRelatorio = 'edu2_livromatricula002.php?calendario=' + calendario;

        oWindow = window.open(sUrlRelatorio, '',
            'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0');
        oWindow.moveTo(0, 0);
    })

</script>
</html>
