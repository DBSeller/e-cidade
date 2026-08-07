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

use ECidade\Lib\File\FileEstorage;

$parametros      = db_utils::postMemory($_GET);
$processo        = new processoProtocolo($parametros->iCodProcesso);
$documentos      = $processo->getDocumentos();


$anexos          = array();
$item            = 0;
$nroLinhas       = 0;
$maximoPorLinha  = 5;
$erro            = null;


if(!empty($documentos)) {

    try {

        $nroLinhas    = count($documentos) < $maximoPorLinha ? 1 : (round(count($documentos) / $maximoPorLinha)) + 1;
        $fileEstorage = new FileEstorage();

        foreach ($documentos as $documento) {

            $referenciaDocumento = '';

            $descricaoDocumento  = $documento->getDescricao();
            $nomeDocumento       = $documento->getNomeDocumento();
            $pathDocumento       = '';

            if($documento->estorage()) {

                $referenciaDocumento = preg_replace('/\D/', '', $nomeDocumento);
                $referenciaDocumento = trim($referenciaDocumento);
                $descricaoDocumento  = '';
                $nomeDocumento       = $documento->getDescricao();

                $pathDocumento       = $fileEstorage->getPath($referenciaDocumento);
            }


            $anexos[] = (object) array(
                'referencia' => $referenciaDocumento,
                'descricao'  => $descricaoDocumento,
                'nome'       => $nomeDocumento,
                'path'       => $pathDocumento
            );
        }

    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("scripts.js");
    db_app::load("strings.js");
    db_app::load("prototype.js");
    db_app::load("estilos.css");
    db_app::load("AjaxRequest.js");
    db_app::load("widgets/DBLookUp.widget.js");
    db_app::load("datagrid.widget.js");
    db_app::load("widgets/Collection.widget.js");
    db_app::load("widgets/DatagridCollection.widget.js");
    db_app::load("widgets/DBInputHora.widget.js");
    db_app::load("widgets/Input/DBInputDate.widget.js");
    db_app::load("widgets/datagrid/plugins/DBHint.plugin.js");
    db_app::load("classes/recursoshumanos/Efetividade/PeriodoEfetividade.js");
    db_app::load("classes/recursoshumanos/PontoEletronico/Justificativas.js");
    db_app::load("classes/recursoshumanos/PontoEletronico/DiaPonto.js");
    db_app::load("classes/recursoshumanos/PontoEletronico/MarcacaoPonto.js");
    db_app::load("classes/recursoshumanos/Efetividade/DBViewJornadaServidor.js");
    db_app::load("EmissaoRelatorio.js");
    db_app::load("widgets/DBToogle.widget.js");
  db_app::load("widgets/DBAncora.widget.js");
    ?>
    <style type="text/css">
        div.documentos
        {
            display: block;
            width: 120px;
            height: 90px;
            margin: 0 10px;
        }
        div.documentos > span
        {
            display: table-cell;
            margin: auto;
            width: inherit;
            height: 70px;
            white-space: normal;
            text-align: center;
            vertical-align: middle;
            background: #eee;
            border-radius: 4px;
            cursor: pointer;
            background-color: white;
            box-shadow: 0 3px 1px -2px rgba(0,0,0,.2), 0 2px 2px 0 rgba(0,0,0,.14), 0 1px 5px 0 rgba(0,0,0,.12);
            border: 0;
            transition: all 0.2s;
        }
        div.documentos > span:hover
        {
            opacity: 0.8;
        }
        div.documentos > input.btn-anexo
        {
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="container">
  <form>
    <fieldset id="filtros">
      <legend>Documentos</legend>

      <table class="form-container">

        <?php for ($i=1; $i <= $nroLinhas; $i++): ?>
        <tr>

          <?php do { ?>
          <?php $anexo = current($anexos); ?>
          <?php if (is_object($anexo)): ?>


          <td>
            <div class="documentos">
                <span  id="documento_<?=$item ?>" class="btn-anexo" data-url="<?=$anexo->path ?>" ><?=$anexo->nome?></span>
            </div>
          </td>
          <td id="linhaPeriodoEfetividade" colspan="2" class="field-size-max"></td>
          <?php
            $item++;
            if(($item % $maximoPorLinha) == 0 ) {
                next($anexos);
                continue 2;
            }
          ?>
          <?php endif; ?>
          <?php } while (next($anexos)); ?>

        </tr>
        <?php endfor;?>

      </table>
    </fieldset>
  </form>
</div>
<?= !empty($erro) ? db_msgbox($erro) : '' ?>

<script type="text/javascript">

    $$('.btn-anexo').forEach((btn) => {
        btn.observe('click', function(e) {
            js_OpenJanelaIframe('CurrentWindow.corpo',
                'db_iframe_download',
                'db_download.php?arquivo='+this.getAttribute('data-url'),
                'Download de arquivos',
                false
            );
        })
    })
</script>
</body>
</html>
