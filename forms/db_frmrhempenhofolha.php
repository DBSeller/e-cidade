<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

$clrotulo  = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<center>
	<form name="form1" method="post" action="">
  <input type="hidden" value="<?= isset($DB_COMPLEMENTAR) ? "1" : "0"; ?>" id="db_complementar" name = 'db_complementar' >
	<table>
	  <tr>
	  <td>
	  <fieldset style="width: 350px">
	    <legend align="center">Geração de Empenhos</legend>
	    <table align="center" width="300">
			  <tr>
			    <td align="left" nowrap>
			      <strong>Ano / Mês:</strong>
			    </td>
			    <td>
			      <?
			        $anofolha = db_anofolha();
			        db_input('anofolha',4,$IDBtxt23,true,'text',2,"onChange='js_validaTipoPonto()'");
			      ?>
			      &nbsp;/&nbsp;
			      <?
			        $mesfolha = db_mesfolha();
			        db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onChange='js_validaTipoPonto()'");
			      ?>
			    </td>
			  </tr>
			  <tr>
			    <td>
			      <strong>Ponto:</strong>
			    </td>
			    <td>
			     <?

			       $aSigla = array( "r14"=>"Salário",
					                    "r48"=>"Complementar",
					                    "r35"=>"13o. Salário",
					                    "r20"=>"Rescisão",
					                    "r22"=>"Adiantamento",
                              "sup"=>"Suplementar"
                              );
          try {
            $oCompetencia         = new DBCompetencia($anofolha, $mesfolha);
  
            /**
            * Valida se a variável está ativa no db_conn, estando ativa, valida-se se a folha está aberta, caso esteja aberta, 
            * não é possível fazer a geração para aquela folha, entao retiramos ela do select. E verifica-se também se
            * existe uma folha, caso não exista, ele retira também do select a folha.
            */

            if (isset($DB_COMPLEMENTAR)) {
      
              if( (FolhaPagamentoSalario::hasFolhaAberta(DBPessoal::getCompetenciaFolha()) && FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_SALARIO)) 
                   || !FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_SALARIO) ) {
                unset($aSigla['r14']);
              }
              if ( (FolhaPagamentoComplementar::hasFolhaAberta(DBPessoal::getCompetenciaFolha()) && FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR)) 
                   || !FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR) ) {
                unset($aSigla['r48']);
              }
              if ( (FolhaPagamentoSuplementar::hasFolhaAberta(DBPessoal::getCompetenciaFolha()) && FolhaPagamento::getFolhaCompetenciaTipo( $oCompetencia, FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR)) 
                   || !FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR) ) {
                unset($aSigla['sup']);
              }
      
            } else if ( !isset($DB_COMPLEMENTAR) ) {
               unset($aSigla['sup']);
            }
         
          } catch (Exception $eException) {
            db_msgbox($eException->getMessage());
          }
			       db_select('ponto',$aSigla,true,4,"onChange='js_validaTipoPonto()'");
			     ?>
			    </td>
        <tr style="display: none;" id="ComboContainer">
          <td align='left' title='Nro. Complementar'>
            <strong>Número:</strong>
          </td>
          <td id="ComboContent">
          </td>
        </tr>
		    </tr>
        <tr id='linhaComplementar' style='display:none'>
		    </tr>
		  </table>
	  </fieldset>

    <fieldset id="filtroRescisao" style="display: none;">

      <legend align="center">Filtrar por data de Rescisão</legend>
      <table border="0" width="300px" align="center">
        <tr>
          <td>
            <strong>Data Inicial:</strong>
          </td>
          <td>
            <?php
              db_inputdata("sDataInicial", null, null, null, true, 'text', 1);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <strong>Data Final:</strong>
          </td>
          <td>
            <?php
              db_inputdata("sDataFinal", null, null, null, true, 'text', 1);
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input type="button" name="filtrar" value="Filtrar" onclick="js_getRescisoes()" />
          </td>
        </tr>
      </table>
    </fieldset>

	  </table>
	  <div style='width:50%; display: none;' id='linhaRescisoes'>
	    <fieldset>
	      <legend>Rescisões</legend>
	      <div id='ctnGridRescisoes'>
	    </fieldset>
	  </div>
    <table>
      <tr>
        <td align = "center">
          <input name="gera" id="gera" type="button" value="Processar" onClick="js_verifica();">
        </td>
      </tr>
    </table>
	</form>
</center>
<script src="scripts/classes/DBViewFormularioFolha/ComboListaFolha.js"></script>
<script>

 var sUrl     = 'pes1_rhempenhofolhaRPC.php';
 var MENSAGEM = 'recursoshumanos/pessoal/db_frmrhempenhofolha.';

 function js_consultaPontoSuplementar(){

  // Força tipo da folha ser suplementar.
  var iTipoFolha = 6;

  // Remove todo conteudo da DIV
  $('linhaRescisoes').style.display       = 'none';
  $('linhaComplementar').style.display    = 'none';
  $('filtroRescisao').style.display       = 'none';
  $('ComboContainer').style.display       = 'none';
  $('ComboContent').innerHTML             = '';

  // Cria uma objeto ComboListaFolha
  var oComboLista = new DBViewFormularioFolha.ComboListaFolha();

  // Pega o valor dos meses
  var iMes = $F('mesfolha');
  var iAno = $F('anofolha');
  var oComboBox = oComboLista.pesquisarFolhas(iTipoFolha, iAno, iMes, false);

    if (oComboBox.aItens.length > 0) {

      oComboBox.sStyle = "width: 106px;";
      oComboBox.show($('ComboContent'));
      $('ComboContainer').style.display = '';
    }
 }



 function js_consultaPontoComplementar(){

   js_divCarregando( _M( MENSAGEM + 'consultando_complementar'),'msgBox');
   js_bloqueiaTela(true);

   if ($F("db_complementar") == "1"){
    var sQuery  = 'sMethod=consultaComplementaresFechadas';
   } else {
    var sQuery  = 'sMethod=consultaPontoComplementar';
        sQuery += '&sSigla='+$F('ponto');
   }
   
   sQuery += '&iAnoFolha='+$F('anofolha');
   sQuery += '&iMesFolha='+$F('mesfolha');

	 var oAjax   = new Ajax.Request( sUrl, {
	                                          method: 'post',
	                                          parameters: sQuery,
	                                          onComplete: js_retornoPontoComplementar
	                                        }
	                                );

 }

 function js_retornoPontoComplementar(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);

   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');


   if ( aRetorno.lErro ) {
     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   }

   var sLinha          = "";
   var iLinhasSemestre = aRetorno.aSemestre.length;

  if ( iLinhasSemestre > 0 ) {

    sLinha += " <td align='left' title='Nro. Complementar'> ";
    sLinha += "   <strong>Nro. Complementar:</strong>       ";
    sLinha += " </td>                                       ";
    
    sLinha += " <td>                                        ";
    sLinha += "   <select id='semestre' name='semestre'>    ";

    for ( var iInd=0; iInd < iLinhasSemestre; iInd++ ) {

      var oSemestre = aRetorno.aSemestre[iInd];
      
      if ($F("db_complementar") == "1"){   
        sLinha += " <option value = '"+oSemestre.rh141_codigo+"'>"+oSemestre.rh141_codigo+"</option>";
      } else {
        sLinha += " <option value = '"+oSemestre.semestre+"'>"+oSemestre.semestre+"</option>";
      }
    }
    sLinha += " </td>                                       ";
  } else {

     sLinha += " <td colspan='2' align='center'>                                ";
     sLinha += "   <font color='red'>Sem complementar para este período.</font> ";
     sLinha += " </td>                                                          ";

   }

   $('linhaComplementar').innerHTML     = sLinha;
   $('linhaComplementar').style.display = '';

 }

 function js_validaTipoPonto(){

  $('ComboContent').innerHTML = '';
  $('ComboContainer').style.display = 'none';
  
   if ( $F('ponto') == 'r48') {

     js_consultaPontoComplementar();
     $('linhaRescisoes').style.display    = 'none';
     $('filtroRescisao').style.display    = 'none';
   } else if ($F('ponto') == 'r20') {

	   $('linhaComplementar').style.display = 'none';
     $('filtroRescisao').style.display    = '';
     js_getRescisoes();
   } else if ( $F('ponto') == 'sup') {

     js_consultaPontoSuplementar();
     $('linhaRescisoes').style.display    = 'none';
     $('filtroRescisao').style.display    = 'none';
    } else {

     $('linhaRescisoes').style.display    = 'none';
     $('linhaComplementar').style.display = 'none';
     $('filtroRescisao').style.display    = 'none';
   }

 }

 function js_verifica(){

   if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {

     alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: 'Ano / Mês'} ) );
     return false;
   }
    if ($F('ponto') == 'r20') {

     if (oGridrescisoes.getSelection().length == 0) {

       alert( _M( MENSAGEM + 'selecione_rescisao' ) );
       return false;
     }
   }
   
   if ($F('ponto') == 'r48') {
     if (!$('semestre') || $F('semestre') == "0") {
       
       alert('Complementar em aberto. Execute o fechamento');
		   return false;
	    }
   }

   if ($F('ponto') == 'r14' && $F("db_complementar") == "1"){
     
     require_once('scripts/classes/DBViewFormularioFolha/ValidarFolhaPagamento.js');
    
     var iMesFolha = $F('mesfolha'); 
     var iAnoFolha = $F('anofolha');
      
     var oFolhaComplementar = new DBViewFormularioFolha.ValidarFolhaPagamento();
     var lFolhaComplementar = oFolhaComplementar.verificarFolhaPagamentoAberta(oFolhaComplementar.TIPO_FOLHA_SALARIO, iAnoFolha, iMesFolha);
      
     if (lFolhaComplementar == true){
       
       alert("A folha de salário esta fechada. Execute o fechamento.");
       return false;
     }
   }
   
   js_consultaEmpenhos();

 }


 function js_consultaEmpenhos(){

   js_divCarregando(  _M( MENSAGEM + 'verificando_empenhos' ) ,'msgBox');
   js_bloqueiaTela(true);

   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: js_getQueryTela('consultarEmpenhos'),
                                            onComplete: js_retornoConsultaEmpenhos
                                          }
                                  );

 }

 function js_retornoConsultaEmpenhos(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);

   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');

   if ( aRetorno.lErro ) {

     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   } else {

     if ( aRetorno.lExiste ) {

       if (confirm( _M( MENSAGEM + 'reprocessa_empenhos' ) )) {
         js_geraEmpenhos();
       }

     } else {
       js_geraEmpenhos();
     }

   }

 }

 function js_geraEmpenhos(){

   js_divCarregando( _M( MENSAGEM + 'gerando_empenhos' ), 'msgBox' );
   js_bloqueiaTela(true);
   if ($F('ponto') == 'r20') {

     if (oGridrescisoes.getSelection().length == 0) {

       alert( _M( MENSAGEM + 'selecione_rescisao' ) );
       return false;
     }
   }
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: js_getQueryTela('gerarEmpenhos'),
                                            onComplete: js_retornoGeraEmpenhos
                                          }
                                  );

 }

 function js_retornoGeraEmpenhos(oAjax){

   js_removeObj("msgBox");
   js_bloqueiaTela(false);

   var aRetorno = eval("("+oAjax.responseText+")");
   var sExpReg  = new RegExp('\\\\n','g');


   if ( aRetorno.lErro ) {

     alert(aRetorno.sMsg.urlDecode().replace(sExpReg,'\n'));
     return false;
   } else {
     alert( _M( MENSAGEM + 'empenhos_gerados' ) );
   }

 }

 function js_bloqueiaTela(lBloq){

   if ( lBloq ) {

     $('anofolha').disabled = true;
     $('mesfolha').disabled = true;
     $('ponto').disabled    = true;
     $('gera').disabled     = true;

     if ($F('ponto') == 'r48') {

       if ($('semestre')) {
         $('semestre').disabled = true;
       }
     }

   } else {

     $('anofolha').disabled = false;
     $('mesfolha').disabled = false;
     $('ponto').disabled    = false;
     $('gera').disabled     = false;

     if ($F('ponto') == 'r48') {

       if ($('semestre')) {
         $('semestre').disabled = false;
       }
     }

   }

 }

 function js_getQueryTela(sMethod){

   var sQuery  = 'sMethod='       + sMethod;
       sQuery += '&iAnoFolha='    + $F('anofolha');
       sQuery += '&iMesFolha='    + $F('mesfolha');
       sQuery += '&sSigla='       + $F('ponto');
       sQuery += '&sDataInicial=' + $F('sDataInicial');
       sQuery += '&sDataFinal='   + $F('sDataFinal');

   if ( $F('ponto') == 'r48' ) {

     if ($('semestre')) {
       sQuery += '&sSemestre='+$F('semestre');
     }
   }

   if ($F('ponto') == 'r20') {

     var aRescisoes = oGridrescisoes.getSelection("object");
     var sVirgula   = "";
     var sRescisoes = "";
     aRescisoes.each(function(oRescisao, id) {

       sRescisoes += sVirgula+oRescisao.aCells[0].getValue();
       sVirgula  = ", ";
     });

     sQuery += '&sRescisoes='+sRescisoes;
     sQuery += '&iTipo=1';
   }

   return sQuery;
 }


 function js_getRescisoes() {

   var sDataInicial = $F('sDataInicial'),
       sDataFinal   = $F('sDataFinal');

  if (js_comparadata(sDataInicial, sDataFinal, '>')) {

    alert ( _M( MENSAGEM + 'data_final_menor_que_inicial' ) );
    return false;
  }

  if ((sDataInicial || sDataFinal) && (!sDataInicial || !sDataFinal)) {

    alert( _M( MENSAGEM + 'campo_obrigatorio', {sCampo: "Data " + (sDataInicial ? 'Final' : 'Inicial') } ) );
    return false;
  }

   $('linhaRescisoes').style.display = '';
   js_divCarregando(_M( MENSAGEM + 'pesquisando_rescisoes' ),'msgBox');
   js_bloqueiaTela(true);

   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post',
                                            parameters: js_getQueryTela('getRescisoesNaoEmpenhadas'),
                                            onComplete: js_retornoGetRescisoes
                                          }
                                  );

 }

 function js_retornoGetRescisoes(oAjax) {

   js_removeObj('msgBox');
   js_bloqueiaTela(false);
   oGridrescisoes.clearAll(true);
   var oRetorno = eval("("+oAjax.responseText+")");
   oRetorno.sListaRescisoes.each(function (oRescisao, id) {

      var aLinha = new Array();
      aLinha[0]  = oRescisao.seqpes;
      aLinha[1]  = oRescisao.matricula;
      aLinha[2]  = oRescisao.nome.urlDecode();
      aLinha[3]  = js_formatar(oRescisao.datarescisao,'d');
      oGridrescisoes.addRow(aLinha);
   });
   oGridrescisoes.renderRows();
 }
 function js_montaGrid() {

   oGridrescisoes              = new DBGrid('gridRescisoes');
   oGridrescisoes.nameInstance = "oGridrescisoes";
   oGridrescisoes.setCheckbox(0);
   oGridrescisoes.setCellAlign(new Array("center","center","Left","center"));
   oGridrescisoes.setCellWidth(new Array("10%","10%","70%","10%"));
   oGridrescisoes.setHeader(new Array("Seqpes","Matrícula","Nome","Data"));
   oGridrescisoes.show($('ctnGridRescisoes'));
 }
 js_montaGrid();
</script>