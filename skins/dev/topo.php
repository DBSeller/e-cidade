<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
$iInstit = @$HTTP_SESSION_VARS["DB_instit"];
if(!isset($iInstit)){
  $iInstit = 1;
}

$sCompetencia = '';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>

</script>
<style type="text/css">
<!--
.arial {
  font-family: Arial, Helvetica, sans-serif;
    text-decoration: none;
}
a:hover {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 13px;
  font-weight: bold;
  color: #FF0000;
    text-decoration: underline;
}

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script>

function js_abrir(obj) {
  var jan = window.open('con3_usuonline113.php?id_usuario='+document.getElementById('Hid_usuario').value+'&usuario='+document.getElementById('Husuario').value+'&hora='+document.getElementById('Hhora').value+'&verfusuario=1','','height=500,width=400,scrollbars=0');document.getElementById('sol').style.visibility = 'hidden';
}

function js_criaDIV() {

  var camada = top.topo.document.createElement("DIV");
  camada.setAttribute("id","info");
  camada.setAttribute("align","center");
  camada.style.position   = "absolute";
  camada.style.left       = "550px";
  camada.style.top        = "2px";
  camada.style.zIndex     = "1000";
  camada.style.visibility = 'visible';
  camada.style.width      = "800px";
  camada.style.height     = "20px";

  var inner  = '<table border="0" cellspacing="1" width="100%" cellpadding="1" style="font-size:10px; border-collapse:collapse;" bgcolor="#5786B2">             ';
      inner += '   <tr>                                                                                                                                         ';
      inner += '     <td><strong>DB Versão:</strong></td>                                                                                                       ';
      inner += '     <td style="color:#FFF;"><?=@pg_result($result,0,3)?></td>                                                                                  ';
      inner += '     <td><strong>Cliente/CodCli:</strong></td>                                                                                                  ';
      inner += '     <td style="color:#FFF;"><?=@pg_result($result,0,5)?></td>                                                                                  ';
      inner += '   </tr>                                                                                                                                        ';
      inner += '   <tr>                                                                                                                                         ';
      inner += '     <td><strong>Base de Dados:</strong></td>                                                                                                   ';
      inner += '     <td style="color:#FFF;">' + document.getElementById('auxAcesso').value + '</td>                                                            ';
      inner += '     <td><strong>Servidor:</strong></td>                                                                                                        ';
      inner += '     <td style="color:#FFF;"><?=$DB_SERVIDOR?> <?=(isset($_SERVER["HTTP_X_FORWARDED_FOR"])?$_SERVER["HTTP_X_FORWARDED_FOR"]:$HTTP_SERVER_VARS['REMOTE_ADDR'])?></td>';
      inner += '   </tr>                                                                                                                                        ';
      inner += '   <tr>                                                                                                                                         ';
      inner += '     <td><strong>Competência Folha:</strong></td>                                                                                               ';
      inner += '     <td style="color:#FFF;" id="sCompetenciaFolha"><?=$sCompetencia?></td>                                                                     ';
      inner += '     <td style="font-size:11px;color:#FFF; font-weight:bold;" colspan="2"><input onClick="this.select();" type="text" style="color:#FFF;border:0;background-color:transparent;width:100%;font-weight:bold; text-align:left !important;" value="';
      inner += 'psql '+ document.getElementById('auxAcesso').value + '  -h <?=$DB_SERVIDOR?> -p <?=$DB_PORTA?> "/>         ';
      inner += '   </tr>                                                                                                                                        ';
      inner += '</table>                                                                                                                                        ';

  camada.innerHTML = inner;

  top.topo.document.body.appendChild(camada);
}

function js_remDIV() {
  if(top.topo.document.getElementById("info"))
    top.topo.document.body.removeChild(top.topo.document.getElementById("info"));
}
</script>
  <?php
    $oSkin = new SkinService();

   // include(modification( $oSkin->getPathFile("topo.php")) );
  ?>
</head>
<body bgcolor=#CCCCCC style='margin-top:0px' leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<input type="hidden" id="auxAcesso" value="<?=$DB_BASE?>">
<table width="100%" height="60" border="0" cellpadding="0" cellspacing="0">
  <tr align="left" valign="bottom" bgcolor="#5786B2" class="arial">
    <td align="center" valign="top"> <table width="100%" height="60" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td height="45" valign="top" id="infoConfig" style="color:white;font-size:10px">&nbsp;</td>
        </tr>
        <tr>
          <td valign="bottom" id='menuTopo' nowrap style="color:white;font-size:13px;font-weight: bold;">
            <a href="instit.php" style="text-decoration:none;color:white" target="corpo">Instituições</a> &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="area.php" style="text-decoration:none;color:white" target="corpo">Áreas</a> &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="corpo.php?link='modulos'" style="text-decoration:none;color:white" target="corpo">M&oacute;dulos</a> &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="acesso.php" onMouseOut="js_remDIV()" onMouseOver="js_criaDIV()" style="text-decoration:none;color:white" target="corpo">Preferências</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="debug.php" onMouseOut="js_remDIV()" onMouseOver="js_criaDIV()" style="text-decoration:none;color:white" target="corpo">Debug</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="sqlformater.php" onMouseOut="js_remDIV()" onMouseOver="js_criaDIV()" style="text-decoration:none;color:white" target="corpo">SQL Formatter</a>&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="#" onClick="if(!confirm('Quer realmente sair do sistema?')){ return false ; }else{ parent.window.close(); }" style="text-decoration:none;color:white" target="_top">Fechar</a>
          </td>
        </tr>
      </table></td>
    <td width="38"><img src="imagens/3_O.gif" width="38" height="60"> </td>

    <td style="display:none;" width="241"><a href="" id="linkprefa"><img src="imagens/4_O.gif" width="241" height="60" border="0"></a> </td>
    <td style="display:none;" width="51"><img src="imagens/5_O.gif" width="51" height="60"> </td>
    
    <td width="186" bgcolor="#272645">
      <img src="imagens/6_O.jpg" width="146" height="60" ondblclick="<?php if ($lPermiteRotinaEspecial === true) { echo "js_direcionarUsuarioRotinaEspecial();"; } else { echo ""; } ?>" />
    </td>
  </tr>
</table>
<!--input type="hidden" name="Hporta" id="Hporta" value="<?=$HTTP_SERVER_VARS['REMOTE_PORT']?>"-->
<input type="hidden" id="Hid_usuario">
<input type="hidden" id="Husuario">
  <input type="hidden" id="Hhora">
<div align="center" id="sol" style="position:absolute; left:450px; top:11px; width:180px; height:45px; z-index:1; background-color: #00FFFF; border: 1px none #000000; visibility: hidden;">
  <br><a href='' id="msg_sol" class="arial" onclick="js_abrir();return false">
  Solicita conversa
  </a>
</div>
<iframe frameborder="0" src="topo2.php" style="position:absolute; left:1px; top:1px; width:0px; height:0px; z-index:0; visibility: hidden;"></iframe>

</body>
</html>
<script>

(function(){
  js_criaDIV();
})();

window.document.captureEvents(Event.KEYDOWN);
window.document.onkeydown  = function (event) {
  switch (event.which) {

   case 116:

    return false;
    break;

  };
}

function js_montarJanelaMensagens() {

  var iWidthParent  = top.corpo.document.body.clientWidth;
  var iHeightParent = top.corpo.document.body.clientHeight;

  var iWidthJanela  = 900;
  var iHeightJanela = 900;

  if ( iWidthParent < iWidthJanela ) {
    iWidthJanela = iWidthParent;
  }

  if ( iHeightParent < iHeightJanela ) {
    iHeightJanela = iHeightParent;
  }

  var iMarginLeft = (iWidthParent - iWidthJanela) / 2;
  var iMarginTop  = 25;
  iHeightJanela  -= iMarginTop;

  var sNomeIframePai       = 'top.corpo';
  var sNomeIframeMensagens = 'db_iframe_mensagens_sistema';
  var sNomeArquivo         = 'con4_mensagens002.php';
  var sTituloJanela        = 'Mensagens';

  js_OpenJanelaIframe(sNomeIframePai, sNomeIframeMensagens, sNomeArquivo, sTituloJanela, true,
                      iMarginTop, iMarginLeft, iWidthJanela, iHeightJanela);
  top.corpo.document.getElementById('Jandb_iframe_mensagens_sistema').style.zIndex = '999999';
  return false;
}

function js_direcionarUsuarioRotinaEspecial() {
  parent.document.getElementById("corpo").src = "con1_acessorapido001.php";
}
</script>