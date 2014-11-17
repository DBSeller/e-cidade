<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  require_once("libs/db_stdlib.php");
  require_once("libs/db_stdlibwebseller.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
  require_once("libs/db_usuariosonline.php");
  require_once("dbforms/db_funcoes.php");
  
  $clcalendarioescola = new cl_calendarioescola;
  $iEscola             = db_getsession("DB_coddepto");
  $db_opcao           = 1;
  $db_botao           = true;
  
  if (!isset($ed52_i_ano)) {
  
    $ed52_i_ano = date("Y") - 1;
  
    for ($x = 1; $x <= 31; $x++) {
  
      if (date("w",mktime(0, 0, 0, 5, $x ,$ed52_i_ano)) == 3) {
  
        $data_censo_dia = strlen($x) == 1 ? "0" . $x : $x;
        $data_censo_mes = "05";
        $data_censo_ano = $ed52_i_ano;
      }
    }
    $data_censo = $data_censo_dia."/".$data_censo_mes."/".$data_censo_ano;
  }
  
  $lVerificaDados = false;
  
  if (isset($ed52_i_ano) && $ed52_i_ano != "") {
    
    $sWhere         = "ed52_i_ano = {$ed52_i_ano} AND ed38_i_escola = {$iEscola}";
    $sCampos        = "ed52_d_inicio, ed52_d_fim";
    $sOrdenacao     = "ed52_d_inicio asc, ed52_d_fim desc";
    $sSqlCalendario = $clcalendarioescola->sql_query( "", $sCampos, $sOrdenacao, $sWhere);
    $rsCalendario   = $clcalendarioescola->sql_record( $sSqlCalendario );
  
    if ($clcalendarioescola->numrows > 0) {
      db_fieldsmemory($rsCalendario, 0);
    } else {
  
      $lVerificaDados    = true;
      $db_opcao          = 3;
      $ed52_d_inicio     = "";
      $ed52_d_inicio_dia = "";
      $ed52_d_inicio_mes = "";
      $ed52_d_inicio_ano = "";
      $ed52_d_fim        = "";
      $ed52_d_fim_dia    = "";
      $ed52_d_fim_mes    = "";
      $ed52_d_fim_ano    = "";
    }
  }
  
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/json2.js"></script>
  <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC>

  <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
  <form class="container" name="form1" method="post" action="">
    <fieldset>
      <legend>Gerar Arquivo de Exportação - CENSO ESCOLAR - SITUAÇÃO DO ALUNO</legend>
    <table class="form-container">
      <tr>
        <td colspan="2">
          <b>Data do Censo:</b>
          <?db_inputdata('data_censo', @$data_censo_dia, @$data_censo_mes, @$data_censo_ano, true, 'text', 3, " onchange=\"js_ano();\"", "", "", "parent.js_ano();")?>
          <b>Ano do Censo:</b>
          <?db_input('ed52_i_ano', 4, @$Ied52_i_ano, true, 'text', 3, "");?>
        </td>
      </tr>
      <tr>
        <td>
          <table>
            <tr>
              <td title="<?=@$Ted52_d_inicio?>" >
                <?
                  if ($lVerificaDados) {
                    echo "<font color='red'><b>*Sem informações para o ano informado.<b></font><br>";
                  }
                ?>
                <fieldset class="separator">
                  <legend>Calendário</legend>
                  <b>Data Inicial:</b>
                  <? db_inputdata('ed52_d_inicio', $ed52_d_inicio_dia, $ed52_d_inicio_mes, $ed52_d_inicio_ano, true, 'text', $db_opcao, "");?>
                  <b>Data Final:</b>
                  <? db_inputdata('ed52_d_fim', $ed52_d_fim_dia, $ed52_d_fim_mes, $ed52_d_fim_ano, true, 'text', $db_opcao, "");?>
                </fieldset>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    </fieldset>
    <input type="button" name="gerarArquivo" id="gerarArquivo" value="Gerar Arquivo" <?=$lVerificaDados == true ? "disabled" : ""?> onclick="js_gerarArquivo();">
  </form>
  
</body>
</html>
<script>

function js_ano() {
  
  dtCenso = $F("data_censo");
  
  if (dtCenso != "" && dtCenso.length == 10) {
    
   dtCenso                         = dtCenso.split("/");
   document.form1.ed52_i_ano.value = dtCenso[2];
   document.form1.submit();
  } else {
    
   document.form1.ed52_i_ano.value    = "";
   document.form1.ed52_d_inicio.value = "";
   document.form1.ed52_d_fim.value    = "";
  }
}

var sUrl = "edu4_exportarsituacaoaluno.RPC.php";


/**
 * Envia os parâmetros para o RPC e executa o gerarArquivo
 */
function js_gerarArquivo() {

  js_divCarregando("Aguarde, gerando arquivo...", "msg");
  
  var oParametros                    = new Object();
      oParametros.exec               = 'exportar';
      oParametros.method             = 'post';
      oParametros.dtCenso            = $F("data_censo");
      oParametros.iAnoCenso          = $F("ed52_i_ano");
      oParametros.dtCalendarioInicio = $F("ed52_d_inicio");
      oParametros.dtCalendarioFim    = $F("ed52_d_fim");
      oParametros.parameters         = 'json='+Object.toJSON(oParametros);
      oParametros.onComplete         = js_retornoGerarArquivo; 
      
  new Ajax.Request(sUrl, oParametros);
}

/**
 * Pega o retorno da requisição de gerarArquivo e abre arquivo de log
 */
function js_retornoGerarArquivo(oAjax){

  js_removeObj("msg");
  
	var oRetorno = JSON.parse(oAjax.responseText);

  alert( oRetorno.sMessage.urlDecode() );
  
  if ( oRetorno.iStatus != 1 ) {

    var oGet = 'sCaminhoArquivo=' + oRetorno.sArquivoLog.urlDecode() + '&iAno=' + $F("ed52_i_ano");
    var jan  = window.open(
                            'edu4_logexportarsituacaoaluno002.php?' + oGet,
                            'Erros Geração de Arquivo de Situação do Aluno do Censo escolar',
                            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
                          );
    jan.moveTo(0,0);
  } else {
    
    var oDownload = new DBDownload(); 
    oDownload.addFile(oRetorno.sArquivoCenso.urlDecode(), "Arquivo situação do censo escolar."); 
    oDownload.show();
  }
}

</script>