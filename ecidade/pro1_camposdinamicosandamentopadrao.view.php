<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2020  DBSeller Servicos de Informatica
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
require_once modification("libs/JSON.php");

$cl_dbsyscampo = new cl_db_syscampo;
$clrotulo = new rotulocampo;
$clrotulo->label('codcam');

$opcoesObrigatorio = [
  1 => 'Sim',
  0 => 'Não'
];
$obrigatorio = 0;

?>
<!DOCTYPE html>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
    <?php db_app::load("estilos.css"); ?>
  <style type="text/css">
    #gridCampos {
      min-width: 550px;
    }
  </style>
</head>
<body>
<div class="container">
  <form id="formCampo">
    <fieldset>
      <legend>Campo dinâmico</legend>

      <table class="form-container">

        <tr title="<?= @$Tcodcam; ?>">
          <td>
            <label id="lbl_campo" for="campo"><?= $Lcodcam ?></label>
          </td>
          <td>
            <input type="hidden" name="codigo" id="codigo">
            <?php db_input('codcam', $dbsize, 1, true, 'text', 1, 'class="field-size2"') ?>
          </td>
          <td>
            <?php db_input('nomecam', $dbsize, 2, true, 'text', 3, 'class="field-size8"') ?>
          </td>
        </tr>

        <tr>
          <td>
            <label for="obrigatorio">Obrigatório:</label>
          </td>
          <td colspan="2">
            <?php db_select('obrigatorio', $opcoesObrigatorio, true, 1, 'class="field-size-max"') ?>
          </td>
        </tr>

      </table>
    </fieldset>

    <input type="button" name="btnSalvarCampo"  id="btnSalvarCampo"  value="Salvar" />
    <input type="button" name="btnExcluirCampo" id="btnExcluirCampo" value="Excluir" />
    <input type="button" name="btnNovoCampo"    id="btnNovoCampo"    value="Novo"   />
  </form>
</div>
    
<div class="container">
  <fieldset>
    <legend>Campos</legend>
    <div id="gridCampos"></div>
  </fieldset>
</div>

</body>
</html>
