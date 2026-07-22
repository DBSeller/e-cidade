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

use App\Domain\Patrimonial\Ouvidoria\Model\TipoprocPersona;
use App\Domain\Patrimonial\Protocolo\Model\Persona;
use App\Domain\Patrimonial\Protocolo\Model\Processo\TipoProc;

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_utils.php"));
include(modification("dbforms/db_funcoes.php"));


$oPost = db_utils::postMemory($_POST);
$oGet = db_utils::postMemory($_GET);

$iCod = null;

if (isset($oGet->p51_codigo)) {
    $iCod = $oGet->p51_codigo;
} elseif (isset($oPost->p51_codigo)) {
    $iCod = $oPost->p51_codigo;
}

$tipoproc = TipoProc::find($iCod);

if ($oPost->acao == "Salvar") {
    $tipoprocPersona = TipoprocPersona::updateOrCreate([
        'ov34_tipoproc' => $oPost->p51_codigo,
        'ov34_persona' => $oPost->persona
    ]);
}

if ($oPost->acao == "Excluir") {
    $tipoprocPersona = TipoprocPersona::find($oPost->ov34_sequencial);
    if ($tipoprocPersona) {
        $tipoprocPersona->delete();
    }
}


?>
<html>
<head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        table {
            border: 1px solid #ccc;
            width: 100%;
            text-align: center;
            border-spacing: unset;
        }

        table th, td {
            border: 1px solid #ccc;
        }

        table td {
            background: #fff;
        }

        table form{
            margin:0;
            padding:0;
        }

        table input{
            margin-bottom: 5px;
        }

        .area-form {
            width: 500px;
            margin: 40px auto auto auto;
            padding:10px;
        }

        input{
            margin-top: 10px;
            height: 15px;
        }

        .area-table {
            width: 800px;
            margin: 30px auto auto auto;
        }

    </style>
</head>
<body style="background: #CCCCCC;margin:0;" onLoad="a=1">

<fieldset class="area-form">
    <legend>PERSONA</legend>
    <form method="post">
        <input type="hidden" name="p51_codigo" value="<?php echo $iCod ?>">
        <select style="width: 100%" name="persona">
            <?php

            $personasIds = array();

            if ($tipoproc->personas) {
                $personasIds = $tipoproc->personas->pluck("p120_sequencial");
            }

            $personas = Persona::whereNotIn(
                "p120_sequencial",
                $personasIds
            )->get();

            foreach ($personas as $persona) : ?>
                <option value="<?php echo $persona->getSequecial() ?>">
                    <?php echo $persona->getDescricao() ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="acao" value="Salvar">
    </form>
</fieldset>

<fieldset class="area-table">
    <legend>PERSONAS HABILITADAS</legend>
    <table>
        <thead>
        <tr>
            <th>PERSONA</th>
            <th>OBJETIVO</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tipoproc->personas as $persona) : ?>
            <tr>
                <td><?php echo $persona->p120_descricao ?></td>
                <td><?php echo $persona->p120_objetivo ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="p51_codigo" value="<?php echo $iCod ?>">
                        <input type="hidden" name="ov34_sequencial"
                               value="<?php echo $persona->pivot->ov34_sequencial ?>">
                        <input type="submit" name="acao" value="Excluir">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</fieldset>

</body>
</html>
