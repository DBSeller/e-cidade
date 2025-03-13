<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$get = db_utils::postMemory($_GET);
?>

<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="grid.style.css" rel="stylesheet" type="text/css">
  <style>
    .valores {
      background-color: #FFFFFF
    }

    .tabela-cabecalho {
      width: 100%;
      border-spacing: 1px;
    }
  </style>
</head>
<body class="body-default">
<div class="container">
  <fieldset>
    <legend>Detalhes da Ouvidoria Externa</legend>

    <table>
      <tr>
        <td class="bold">Sequencial:</td>
        <td class='valores'>
          <span id='preProcesso'></span>
        </td>
      </tr>
      <tr>
        <td class="bold">Data:</td>
        <td class='valores'>
          <span id='data'></span>
        </td>
      </tr>
      <tr>
        <td class="bold">Hora:</td>
        <td class='valores'>
          <span id='hora'></span>
        </td>
      </tr>
      <tr>
        <td class="bold">Requerente:</td>
        <td class='valores'>
          <span id='requerente'></span>
        </td>
      </tr>

      <tr>
        <td class="bold">Tipo de Processo:</td>
        <td class='valores'>
          <span id='tipoProcesso'></span>
        </td>
      </tr>
    </table>

    <div>
      <fieldset>
        <legend>Mais Informações</legend>
        <table>
          <tr>
            <td class='valores' colspan="2">
              <span id='maisInformacoes'></span>
            </td>
          </tr>
        </table>
      </fieldset>
    </div>
  </fieldset>
</div>
<input id="sequencial" type="hidden" value="<?= $get->sequencial ?>"/>
</body>
<script>
  const RPC = 'ouv4_gerarprocesso.RPC.php';

  function dadosPreProcesso() {
    new AjaxRequest(RPC, {
      'executa': 'buscarDadosPreProcesso',
      'sequencial': $F('sequencial')
    }, function(retorno, erro) {

      if(erro === true) {

        alert(retorno.mensagem);
        return false;
      }

      $('preProcesso').innerHTML = retorno.sequencial;
      $('data').innerHTML = retorno.data;
      $('hora').innerHTML = retorno.hora;
      $('requerente').innerHTML = retorno.requerente;
      $('tipoProcesso').innerHTML = retorno.tipoProcesso;
      $('maisInformacoes').innerHTML = retorno.maisInformacoes;
    }).execute();
  }

  dadosPreProcesso();
</script>
</html>
