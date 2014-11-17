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

<form name="form1" method="post" action="">
  <input type="hidden" value="<?= isset($DB_COMPLEMENTAR) ? "1" : "0"; ?>" id="db_complementar" name = 'db_complementar' >

	<fieldset style="width: 350px; margin: 20px auto 5px auto">
		<legend align="center">
			<strong>Geração de Empenhos</strong>
		</legend>

	<table align="center">
		<tr>
			<td>
			  <strong>Ano / Mês :</strong>
			</td>
			<td>
				<?php
					db_input('anofolha',4,$IDBtxt23,true,'text',2,"onChange='js_validaTipoPonto(true);js_periodoFolha();'");
				?>
				&nbsp;/&nbsp;
				<?php
					db_input('mesfolha',2,$IDBtxt25,true,'text',2,"onChange='js_validaTipoPonto(true);js_periodoFolha();'");
				?>
			</td>
		</tr>

		<tr>
			<td>
				<strong>Tipo:</strong>
			</td>
			<td>
				<?
					$aTipos = array(
												  "1" => "Salário        ",
                          "2" => "Previdência    ",
                          "3" => "FGTS           ");

					db_select('tipo',$aTipos,true,4, " style='width: 150px;' onChange='js_validaTipoPonto(true)'");
				?>
			</td>
		</tr>

    <tr>
    	<td>
    		<strong>Ponto:</strong>
    	</td>
    	<td>
    		<?

    			$aSigla = array();

    			db_select('ponto',$aSigla,true,4," style='width: 150px;' onChange='js_validaTipoPonto(false)'");

    		?>
    	</td>
    </tr>

		<tr id='linhaComplementar' style='display:none'>

		</tr>
	</table>
	<table align="center">
		<tr id='tabelasPrevidencia' style='display:none'>
			<td align="center" colspan="2" >
				<input type="hidden" id="empenhosAnoFolha" value="<?php echo $anofolha; ?>" />
				<input type="hidden" id="empenhosMesFolha" value="<?php echo $mesfolha; ?>" />
				<?
		      $sql  = "select distinct r33_codtab,              ";
		      $sql .= "                r33_nome                 ";
		      $sql .= "           from inssirf                  ";
		      $sql .= "          where r33_anousu = {$anofolha} ";
		      $sql .= "            and r33_mesusu = {$mesfolha} ";
		      $sql .= "            and r33_codtab > 2           ";
		      $sql .= "            and r33_instit = ".db_getsession('DB_instit') ;

		      $rsPrev = db_query($sql);

		      db_multiploselect("r33_codtab", "r33_nome", "nselecionados", "selecionados", $rsPrev, array(), 4, 250);
				?>
			</td>
		</tr>

	</table>

  </fieldset>

  <fieldset id="filtroRescisao" style="display: none; width: 350px; margin: 10px auto">

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


	<div style="display:none; text-align: center;" id='linhaRescisoes'>
		<fieldset style="margin: 0 auto; width: 650px;">
			<legend>
				<strong>Rescisões</strong>
			</legend>
			<div id='ctnGridRescisoes'></div>
		</fieldset>
	</div>
  <div style="text-align: center;">
		<input name="gera" id="gera" type="button" value="Processar" onClick="js_verifica();">
	</div>

</form>

<script>
$('mesfolha').maxLength = 2;
$('anofolha').maxLength = 4;

var sUrl = 'pes1_rhempenhofolhaRPC.php';

js_periodoFolha();

js_montaCombo();

function js_montaCombo() {

	var aPonto = new Array();

	var oComboPonto = document.getElementById('ponto');

	oComboPonto.addClassName('DBSelectMultiplo');

	oComboPonto.options.length = 0;

	if ($F('tipo') == '1') {

		aPonto = new Array({chave: 'r14' , valor: 'Salário'},
		   								 {chave: 'r48' , valor: 'Complementar'},
		   							   {chave: 'r35' , valor: '13o. Salário'},
		   							   {chave: 'r20' , valor: 'Rescisão'},
		   							   {chave: 'r22' , valor: 'Adiantamento'});

	}	else {

		aPonto = new Array({chave: 'r14,r48,r20' , valor: 'Mensal'},
		                   {chave: 'r35'         , valor: '13o Salário'});

	}

	for (var iIndice = 0; iIndice < aPonto.length; iIndice++) {

		var oValor    = aPonto[iIndice];
		var oOption   = document.createElement("option");

		oOption.value = oValor.chave;
		oOption.text  = oValor.valor;

		oComboPonto.add(oOption);

	}
}

function js_consultaPontoComplementar(){

  js_divCarregando('Consultando ponto complementar...','msgBox');
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

function js_validaTipoPonto(lCarregaCombo) {

 $('sDataFinal').value   = '';
 $('sDataInicial').value = '';

 if ( $F('ponto') == 'r48') {

	 js_consultaPontoComplementar();
	 $('linhaRescisoes').style.display = 'none';
	 $('filtroRescisao').style.display = 'none';
 } else if ($F('ponto') == 'r20' && $F('tipo') == 1) {

	 $('linhaComplementar').style.display = 'none';
	 $('filtroRescisao').style.display    = '';
	 js_getRescisoes();
 } else {

   $('filtroRescisao').style.display    = 'none';
	 $('linhaRescisoes').style.display    = 'none';
	 $('linhaComplementar').style.display = 'none';
 }

 if ($F('tipo') == 2) {

	 $('tabelasPrevidencia').style.display = '';
	 js_periodoFolha()
 } else {
	 $('tabelasPrevidencia').style.display = 'none';
 }

	if (lCarregaCombo)
		js_montaCombo();

}

function js_periodoFolha() {

	var iTipo     = $F('tipo');
  var iAno      = parseFloat($F('anofolha'), 10);
  var iMes      = parseFloat($F('mesfolha'), 10);
	var iAnoFolha = $F('empenhosAnoFolha');
	var iMesFolha = $F('empenhosMesFolha');

	if (iTipo != 2) {
		return false;
	}

	if($F('anofolha').length != 4 || $F('mesfolha').length < 1 || $F('mesfolha').length > 2) {
		return false;
	}

	if ( iAno == iAnoFolha && iMes == iMesFolha) {

		if (iMes < 10) {
			$('mesfolha').value = '0'+iMes;
		}
		$('tabelasPrevidencia').style.display = '';
		return false;
	}

	js_divCarregando('Pesquisando previdências','msgBox');
	location.href = 'rh4_gerarempenhosfolha001.php?iAno=' + iAno + '&iMes=' + iMes + '&iTipo=' + iTipo;
}

function js_verifica(){

 if ( $F('anofolha') == '' || $F('mesfolha') == '' ) {
	 alert('Ano / Mês não informado!');
	 return false;
 }

 if ( $F('mesfolha') > 12 ) {

    alert('Mês incorreto. Informe corretamente.');
    return false;
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

 js_mostraEmpenhosAGerar();

}

function js_mostraEmpenhosAGerar() {

 if ($F('ponto') == 'r20' && $F('tipo') == 1) {

	 if (oGridrescisoes.getSelection().length== 0) {

		 alert('selecione alguma rescisão para continuar.');
		 return false;
	 }
 }
 var sPrograma = 'pes4_gerarempenhosfolha002.php';
 if ($F('ponto') == 'r20' && $F('tipo') == 1) {
	 sPrograma = 'pes4_gerarempenhosfolharescisao002.php';
 }
 js_OpenJanelaIframe('top.corpo',
										 'db_iframe_gerarempenho',
										 sPrograma+'?json='+js_getQueryTela(),
										 'Gerar Empenhos - '+$F('mesfolha')+'/'+$F('anofolha'),
										 true,
										 25,
										 0,
										 document.body.getWidth()-12,
										 document.body.scrollHeight-25);

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

function js_getQueryTela(sMethod) {

 var oParam       = new Object();
 oParam.exec      = sMethod;
 oParam.iAnoFolha = $F('anofolha');
 oParam.iMesFolha = $F('mesfolha');
 oParam.sSigla    = $F('ponto');
 oParam.iTipo     = $F('tipo');
 oParam.sSemestre = "0";

 if ( $F('ponto') == 'r48' ) {
	 if ($('semestre')) {
		 oParam.sSemestre = $F('semestre');
	 }
 }

 if ($F('ponto') == 'r20' && $F('tipo') == 1) {

	 var aListarescisoes = new Array();
	 var aRescisoes = oGridrescisoes.getSelection("object")
	 if (oGridrescisoes.getSelection().length == 0) {

		 alert('selecione alguma rescisão para continuar.');
		 return false;
	 } else {
		 aRescisoes.each(function(oRescisao, id) {
			 aListarescisoes.push(oRescisao.aCells[0].getValue());
		 });
	 }
	 oParam.aRescisoes = aListarescisoes;
 }

 if ( $F('tipo') == 2) {

	 var sSelecionados = "";
	 var sVirg         = "";

	 for(var i=0; i<document.form1.selecionados.length; i++){
		 sSelecionados += sVirg + (document.form1.selecionados.options[i].value - 2);
		 sVirg          = ",";
	 }

	 oParam.sPrevidencia = sSelecionados;
 }

 return Object.toJSON(oParam);

}
 function js_getRescisoes() {

 var sDataInicial = $F('sDataInicial'),
      sDataFinal  = $F('sDataFinal');

  if (js_comparadata(sDataInicial, sDataFinal, '>')) {

    alert ("A data final deve ser menor que a data inicial.");
    return false;
  }

  if ((sDataInicial || sDataFinal) && (!sDataInicial || !sDataFinal)) {
    alert( "Campo Data " + (sDataInicial ? 'Final' : 'Inicial') + " é de preenchimento obrigatório." );
    return false;
  }

 $('linhaRescisoes').style.display = '';
 js_divCarregando('Pesquisando Rescisões','msgBox');
 js_bloqueiaTela(true);

 var sQuery  = 'sMethod=getRescisoesNaoEmpenhadas';
		 sQuery += '&iAnoFolha='	  + $F('anofolha');
		 sQuery += '&iMesFolha='	  + $F('mesfolha');
		 sQuery += '&sDataInicial=' + $F('sDataInicial');
     sQuery += '&sDataFinal='   + $F('sDataFinal');
		 sQuery += '&sSigla='			  + $F('ponto');

 var oAjax   = new Ajax.Request( sUrl, {
																					method: 'post',
																					parameters: sQuery,
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

 oGridrescisoes     = new DBGrid('gridRescisoes');
 oGridrescisoes.nameInstance = "oGridrescisoes";
 oGridrescisoes.setCheckbox(0);
 oGridrescisoes.setCellAlign(new Array("right","right","Left","center"));
 oGridrescisoes.setCellWidth(new Array("4%","20%","66%","20%"));
 oGridrescisoes.setHeader(new Array("Seq","Matrícula","Nome","Data"));
 oGridrescisoes.show($('ctnGridRescisoes'));
}
js_montaGrid();
</script>