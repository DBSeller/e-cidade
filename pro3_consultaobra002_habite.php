<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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
require_once(modification("dbforms/db_funcoes.php"));

$clobrasconstr = new cl_obrasconstr;
$clobrashabite = new cl_obrashabite;
$oDaoObrasEnvioRegHab = new cl_obrasenvioreghab;

$oGet = db_utils::postMemory($_GET);

$oDaoParProjetos = new cl_parprojetos();
$sSqlParametros = $oDaoParProjetos->sql_query_pesquisaParametros(db_getsession('DB_anousu'));
$rsParametros = $oDaoParProjetos->sql_record($sSqlParametros);

if ($oDaoParProjetos->erro_status != "0") {
    $oParametros = db_utils::fieldsMemory($rsParametros, 0);
    $db_opcao = 3;
} else {
    db_msgbox(_M('tributario.projetos.pro3_consultaobra002_habite.parametros_nao_configurados'));
}

$iTipoRelatorio = $oParametros->ob21_tipocartahabite;

/**
 * Sql tabela obrascontr
 */
$sqlObrasConstr = $clobrasconstr->sql_query(null, "*", "", "ob08_codobra = $parametro");
$rsObrasConstr = $clobrasconstr->sql_record($sqlObrasConstr);

if ($clobrasconstr->numrows > 0) {
    $oObrasConstr = db_utils::fieldsMemory($rsObrasConstr, 0);

    /**
     * Sql tabela obrashabite
     */
    $sqlObrasHabite = $clobrashabite->sql_query_file(null, "*", "", "ob09_codconstr = $oObrasConstr->ob08_codconstr");
    $rsObrasHabite = $clobrashabite->sql_record($sqlObrasHabite);

    /**
     * Verifica se existe dados na tabela obrashabite
     */
    if ($clobrashabite->numrows > 0) {
        $oObrasHabite = db_utils::fieldsMemory($rsObrasHabite, 0);
    }
}

if ($clobrashabite->numrows > 0) {
    if ($oObrasHabite->ob09_parcial == "t") {
        $tipo = "Parcial";
    } else {
        $tipo = "Total";
    }

    $rsObrasEnvioRegHab = $oDaoObrasEnvioRegHab->sql_record($oDaoObrasEnvioRegHab->sql_query(null, "*", "", "ob18_codhabite = {$oObrasHabite->ob09_codhab}"));
    if($oDaoObrasEnvioRegHab->numrows > 0){
      $oObrasEnvioRegHab = db_utils::fieldsMemory($rsObrasEnvioRegHab, 0, true);
    }

    ?>
    <html>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <link href="estilos.css" rel="stylesheet" type="text/css">
      <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
      <style>
        #elemento_principal {
          width: 100%;
        }

        #elemento_principal tr td:first-child {
          width: 150px;
        }
      </style>
    </head>
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <div class="container">
    <fieldset>
      <legend>Dados do Habite-se:</legend>

      <table id="elemento_principal" class="form-container">
        <tr>
          <td nowrap>Habite-se:</td>
          <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasHabite->ob09_habite; ?></td>
        </tr>
        <tr>
          <td nowrap>Data do habite-se:</td>
          <td nowrap bgcolor="#FFFFFF"><?php echo db_formatar($oObrasHabite->ob09_data, "d") ?></td>
        </tr>
        <tr>
          <td nowrap>Área:</td>
          <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasHabite->ob09_area; ?></td>
        </tr>
        <tr>
          <td nowrap>Tipo de habite-se:</td>
          <td nowrap bgcolor="#FFFFFF"><?php echo $tipo; ?></td>
        </tr>
        <tr>
          <td nowrap>Observações:</td>
          <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasHabite->ob09_obs ?></td>
        </tr>
        <tr>
          <td nowrap>Situação Atual:</td>
          <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasHabite->ob09_ativo === 't' ? 'Ativo' : 'Cancelado'; ?></td>
        </tr>
        <tr>
          <td nowrap>Data da Situação:</td>
          <td nowrap bgcolor="#FFFFFF">
              <?php
              $mensagem = "Sem Alteração";

              if(!empty($oObrasHabite->ob09_datacancelamentoreativacao)) {
                  $data = new DBDate($oObrasHabite->ob09_datacancelamentoreativacao);
                  $mensagem = "{$data->getDate(DBDate::DATA_PTBR)}";
              }

              echo $mensagem;
              ?>
          </td>
        </tr>
        <tr>
          <td nowrap>Protocolo Sisobra:</td>
          <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasEnvioRegHab->ob18_protocolo?></td>
        </tr>
      </table>
    </fieldset>
      <input name="emite2" id="emite2" type="button" value="Emitir Carta de Habite-se"
             onclick="js_emite(<?= $iTipoRelatorio ?>);">
    </div>
    <?php

    /**
     * Se não existir habite-se
     */
} else {

    echo "<br />                                              ";
    echo "<br />                                              ";
    echo "<center>                                            ";
    echo "  <strong>Construção não possui habite-se.</strong> ";
    echo "</center>                                           ";
}
?>
</body>
</html>
<script>
  function js_emite(iTipoRelatorio) {

    let sTipoArquivoRelatorio = iTipoRelatorio == 0 ? "pro2_cartahabite002.php" : "pro2_cartahabite003.php";
    let jan = window.open(sTipoArquivoRelatorio + '?codigo=<?=$oObrasHabite->ob09_codhab?>',
      '',
      'width=' + (screen.availWidth - 5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
    jan.moveTo(0, 0);
  }
</script>
