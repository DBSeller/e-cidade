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

$clrotulo = new rotulocampo;
$clrotulo->label("rh196_arquivo");

$listaSobrescreverArquivo = array(0=>'Não',1=>'Sim');
?>
<html>
<head>
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load(array(
    "strings.js",
    "scripts.js",
    "dates.js",
    "prototype.js",
    "strings.js",
    "AjaxRequest.js",
    "widgets/DBLookUp.widget.js",
    "widgets/Input/DBInput.widget.js",
    "widgets/Input/DBInputDate.widget.js",
    "estilos.css",
    "grid.style.css",
    "classes/recursoshumanos/Efetividade/PeriodoEfetividade.js"
  ));
  ?>
  <style type="text/css">
  </style>
</head>
<body>
<div class="container">
  <form method="POST" id="importarArquivo" class="form-container" action="rec4_pontoeletronicoimportacaoarquivo002.php" enctype="multipart/form-data" onsubmit="return importarArquivo()">
    <fieldset>
      <legend>Arquivo do Ponto Eletrônico</legend>
      <table class="form-container">
        <tr style="display:none">
          <td nowrap title="Informa se as marcações já importadas irão ser sobrescritas">
            <label id="lbl_sobrescreverArquivo" for="sobrescreverArquivo">Sobrescrever Arquivos:</label>
          </td>
          <td>
            <?php db_select('sobrescreverArquivo', $listaSobrescreverArquivo, true, 1) ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Trh196_arquivo; ?>">
            <label id="lbl_rh196_arquivo" for="rh196_arquivo">Arquivos:</label>
          </td>
          <td>
            <input class="" type="file" name="rh196_arquivos[]" id="rh196_arquivos" multiple />
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="submit" value="Importar" />
  </form>
</div>
<script type="text/javascript">

function importarArquivo () {

  return true;

  if(!!parseInt($F('sobrescreverArquivo'))) {    

    var mensagemAlertaSobreescrita = "Atenção\n\nAo selecionar 'Sobrescrever Arquivos' como 'Sim'\nas marcações do arquivo anteriormente importado serão perdidas.";

    if(!confirm(mensagemAlertaSobreescrita)) {
      return false;
    }
  }
}

</script>
</body>
</html>
