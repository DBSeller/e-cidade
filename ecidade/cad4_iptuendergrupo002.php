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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
/**
* Carregamos as variáveis passadas por GET para o objeto $oGet.
*/
$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

/**
 * Carregamos as DAOs necessárias e chamamos os métodos que carregam os labels.
 */
$oDaoIptuBase  = db_utils::getDao('iptubase');
$oDaoCgm       = db_utils::getDao('cgm');
$oDaoIptuEnder = db_utils::getDao('iptuender');
$oDaoCgm->rotulo->label();
$oDaoIptuEnder->rotulo->label();
$oDaoIptuBase->rotulo->label();

/**
 * Se foi passado matricula então prucura numgm dela
 */
if (!empty($oPost->j43_matric)) {
	
	$sSqlIptuBase = $oDaoIptuBase->sql_query_file($oPost->j43_matric);
	$rsIptuBase   = $oDaoIptuBase->sql_record($sSqlIptuBase);

	if ($oDaoIptuBase->numrows > 0) {

		$oIptuBase = db_utils::fieldsMemory($rsIptuBase, 0);
		$iCodigoCgm = $oIptuBase->j01_numcgm;
	}
}

/**
 * Se não foi passado matricula então procura o nome pelo numcgm
 */
if ( !isset($iCodigoCgm) || !empty($oPost->z01_numcgm) ) {
	$iCodigoCgm = $oPost->z01_numcgm;
}
$sSqlCgm    = $oDaoCgm->sql_query_file($iCodigoCgm);
$rsCgm      = $oDaoCgm->sql_record($sSqlCgm);

if ($oDaoCgm->numrows > 0) {

	$oCgm = db_utils::fieldsMemory($rsCgm, 0);

	/**
	 * Setamos o nome do CGM por padrão para o destinatário.
	 */
	$j43_dest   = $oCgm->z01_nome;
	$z01_nome   = $oCgm->z01_nome;
	$z01_numcgm = $iCodigoCgm;
}

/**
 * Definimos qual será a função do formulário.
 * Por padrão a função é a js_submitForm, se a dbopcao for igual a 3 utilizamos a js_excluirEnderecos.
 */
$sFuncaoFormulario = "js_submitForm();";

if (isset($oGet->db_opcao) && $oGet->db_opcao == "3") {
	$sFuncaoFormulario = "js_excluirEnderecos();";
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

	<center>
		<form>
			<fieldset style="margin-top: 30px; width: 625px;">
				<legend><strong>Endereço de Entrega</strong></legend>

				<table border="0" width="625">
					<tr>
					  <td width="82">
					    <?php echo $Lz01_numcgm; ?>
					  </td>
					  <td>
					    <?php 
					    	db_input('z01_numcgm', 5, $Iz01_numcgm, true, 'text', 3);
					    	db_input('z01_nome', 53, $Iz01_nome, true, 'text', 3);
					    ?>
					  </td>
					</tr>
				</table>

				<input type="hidden" id="iCgmPesquisa" value="<?php echo $oPost->z01_numcgm; ?>">
				<input type="hidden" id="iMatriculaPesquisa" value="<?php echo $oPost->j43_matric; ?>">

				<fieldset>
					<legend><strong>Matrículas</strong></legend>

					<fieldset>
							<legend><strong>Lançar matrícula:</strong></legend>

							<table border="0" width="600">
								<tr>

									<td width="70">
										<?php db_ancora('<strong>Matrícula:</strong>', 'js_pesquisaMatricula(true)', 1); ?>
									</td>

									<td>
										<input type="text" id="iMatriculaLancar"     title="<?php echo $Tj01_matric; ?>" size="5"  value="" onChange="js_pesquisaMatricula(false)"	/>
										<input type="text" id="sNomeMatriculaLancar" title="<?php echo $Tz01_nome; ?>"   size="53" value="" readonly style="background:#DEB887;" />
									</td>

									<td>
										<input type="button" id="lancarMatricula" onClick="js_lancarMatricula()" value="Lançar">
									</td>
									
								</tr>
							</table>
							
					</fieldset>

					<br />

					<div id="gridContainer"></div>
				</fieldset>

				<?php	if (isset($oGet->db_opcao) && $oGet->db_opcao != 3) { ?>
					<fieldset>
						<legend><strong>Endereço de Entrega</strong></legend>
						<table>
							<tr>
								<td nowrap="nowrap">
								  <?php echo $Lj43_dest; ?>
								</td>
								<td nowrap="nowrap">
									<?php db_input('j43_dest', 40, $Ij43_dest, true, 'text', 1); ?>
								</td>
								<td colspan="2"></td>
							</tr>
							<tr>
							  <td nowrap="nowrap">
							    <?php echo $Lj43_ender; ?>
							  </td>
							  <td nowrap="nowrap">
							  	<?php db_input('j43_ender', 40, $Ij43_ender, true, 'text', 1); ?>
							  </td>
							  <td nowrap="nowrap">
							    <?php echo $Lj43_numimo; ?>
							  </td>
							  <td nowrap="nowrap">
							  	<?php db_input('j43_numimo', 10, $Ij43_numimo, true, 'text', 1); ?>
							  </td>
							</tr>
							<tr>
							  <td nowrap="nowrap">
							  	<?php echo $Lj43_comple; ?>
							  </td>
							  <td nowrap="nowrap">
							  	<?php db_input('j43_comple', 40, $Ij43_comple, true, 'text', 1); ?>
							  </td>
							  <td colspan="2"></td>
							</tr>
							<tr>
								<td nowrap="nowrap">
									<?php echo $Lj43_bairro; ?>
								</td>
								<td nowrap="nowrap">
									<?php db_input('j43_bairro', 40, $Ij43_bairro, true, 'text', 1); ?>
								</td>
								<td colspan="2"></td>
							</tr>
							<tr>
								<td nowrap="nowrap">
									<?php echo $Lj43_munic; ?>
								</td>
								<td nowrap="nowrap">
									<?php db_input('j43_munic', 40, $Ij43_munic, true, 'text', 1); ?>
								</td>
								<td nowrap="nowrap">
									<?php echo $Lj43_uf; ?>
								</td>
								<td nowrap="nowrap">
									<?php db_input('j43_uf', 10, $Ij43_uf, true, 'text', 1); ?>
								</td>
							</tr>
							<tr>
								<td nowrap="nowrap">
									<?php echo $Lj43_cep; ?>
								</td>
								<td nowrap="nowrap">
									<?php db_input('j43_cep', 40, $Ij43_cep, true, 'text', 1); ?>
								</td>
								<td nowrap="nowrap">
									<?php echo $Lj43_cxpost; ?>
								</td>
								<td nowrap="nowrap">
									<?php db_input('j43_cxpost', 10, $Ij43_cxpost, true, 'text', 1); ?>
								</td>
							</tr>
						</table>
				  </fieldset>
			<?php } ?>
			</fieldset>
			<input type="button" id="btnProcessar" name="btnProcessar" value="Processar" 
			       style="margin-top: 5px;" onclick="<?php echo $sFuncaoFormulario; ?>" />
			<input type="button" value="Voltar" onclick="js_voltar();" />
		</form>
	</center>
  <?php 
    db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
            db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
</body>
</html>
<script>

/**
 * Definimos a flag de tipo de busca que foi utilizada para buscar as matrículas e a URL do RPC.
 */
var lBuscaPorCgm = true;
var db_opcao     = 1;
<?php
	if (!empty($oPost->j43_matric)) {
  	echo "lBuscaPorCgm = false;";
  }
	if ( isset($oGet->db_opcao) && $oGet->db_opcao == 3) {
		echo 'db_opcao = 3';
	}
?>

var sUrlRpc = "cad4_iptuendergrupo.RPC.php";

/**
	$('iMatriculaLancar').value     = '';
	$('sNomeMatriculaLancar').value = '';
 * Ao carregarmos o formulário criamos a grid de matrículas.
 */
js_renderizaGrid();

/**
 * Função que renderiza a grid e ao renderiza-la chama a função que popula a mesma
 * de acordo com o filtro passado pela função de pesquisa.
 */
function js_renderizaGrid() {

	oGrid = new DBGrid('oGrid');
	oGrid.nameInstance = 'oGrid';
	oGrid.setCheckbox(0);
	var aAlign = new Array('left', 
                         'left', 
                         'left');
	oGrid.setCellAlign(aAlign);
	var aWidths = new Array('10%', '65%', '25%');
	oGrid.setCellWidth(aWidths);
	var aHeader = new Array('Matrícula', 
                          'Endereço Atual', 
                          'Tipo de Vínculo');
	oGrid.setHeader(aHeader);
	oGrid.setHeight(100);
	oGrid.show($('gridContainer'));
	js_getMatriculasGrid();
}

/**
 * Função que manda uma requisição para o RPC responder com as matrículas que irão popular a grid de matrículas.
 */
function js_getMatriculasGrid() {

	js_divCarregando('Carregando matricula(s)','divCarregando');

	var oParam                  = new Object();
	    oParam.sExec            = "buscarMatriculas";
	    oParam.buscaPorCgm      = lBuscaPorCgm;
	    oParam.iNumeroCgm       = $F('iCgmPesquisa');
			oParam.iDbOpcao         = db_opcao;
	    oParam.iNumeroMatricula = $F('iMatriculaPesquisa');

	var oAjax = new Ajax.Request(sUrlRpc,
			                         {method : 'post',
                                parameters : 'json='+Object.toJSON(oParam),
                                onComplete : js_retornoGetMatriculasGrid});
}

/**
 * Função que processa o retorno da função js_getMatriculasGrid e popula a grid de matrículas.
 */
function js_retornoGetMatriculasGrid(oAjax) {

	js_removeObj('divCarregando');

	var oRetorno = JSON.parse(oAjax.responseText);
	if (oRetorno.iStatus == 2) {

		alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n'));
		return false;
	}

	if ( oRetorno.iStatus == 3 ) {
		
		alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n'));
		js_voltar();
		return false;
	}

	oGrid.clearAll(true);
	for (var iMatriculas = 0; iMatriculas < oRetorno.aMatriculas.length; iMatriculas++) {

		with (oRetorno.aMatriculas[iMatriculas]) {

			var sEnderecoPronto = sEndereco+", "+iNumero+", ";
			if (sComplemento != "") {
				sEnderecoPronto += sComplemento+", ";
			}
			sEnderecoPronto += sMunicipio;
			if (sEndereco == "") {
				sEnderecoPronto = "Matrícula sem endereço de entrega registrado.";
			}
			var aLinha = new Array();
			    aLinha[0] = iMatricula;
			    aLinha[1] = sEnderecoPronto.urlDecode().replace(/\\n/g,'\n'); 
					aLinha[2] = sTipoVinculo.urlDecode().replace(/\\n/g,'\n');  

			var lMarcado = true;

			/**
			 * Sem matriculas para a consulta  
			 */
			if (oRetorno.iStatus == 3) {

				alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,"\n"));
				window.location = "cad4_iptuendergrupo001.php?db_opcao=3";
				return false;
			}

			oGrid.addRow(aLinha, null, null, lMarcado);
		}
	}
	oGrid.renderRows();
}


/**
 * Função que valida o preenchimento do formulário e envia os dados para o processamento no RPC.
 * Ao final da operação chamamos a função js_retornoSubmitForm para tratar o retorno do RPC.
 */
function js_submitForm(lDesvinculaMatriculas) {

	var aSelecionadosGrid = oGrid.getSelection();
	if (aSelecionadosGrid.length == 0) {

		alert('Ao menos uma matrícula deve ser selecionada na tabela acima. Favor verificar.');
		return false;
	}

  var aMatriculasSelecionadas = new Array();
  aSelecionadosGrid.each(function (aSelecionado, iIndice){
    aMatriculasSelecionadas[iIndice] = aSelecionado[0];
  });

	var oParam                         = new Object();
	    oParam.sExec                   = 'salvarEndereco';
	    oParam.aMatriculasSelecionadas = aMatriculasSelecionadas;
	    oParam.lDesvinculaMatriculas   = lDesvinculaMatriculas || false;

	    oParam.sNomeDestinatario = $F('j43_dest').urlEncode();
	    oParam.sLogradouro       = $F('j43_ender').urlEncode();
	    oParam.iNumero           = $F('j43_numimo').urlEncode();
	    oParam.sComplemento      = $F('j43_comple').urlEncode();
	    oParam.sBairro           = $F('j43_bairro').urlEncode();
	    oParam.sMunicipio        = $F('j43_munic').urlEncode();
	    oParam.sUF               = $F('j43_uf').urlEncode();
	    oParam.sCEP              = $F('j43_cep').urlEncode();
	    oParam.sCaixaPostal      = $F('j43_cxpost').urlEncode();
	    
	js_divCarregando('Salvando matricula(s)','divCarregando');

	var oAjax = new Ajax.Request(sUrlRpc,
                               {method : 'post',
                                parameters : 'json='+Object.toJSON(oParam),
                                onComplete : js_retornoSubmitForm});
}

/**
 * Função que trata o retorno da função js_submitForm.
 * Validamos a variável iStatus do retorno e definimos o coportamento adequado para cada situação.
 */
function js_retornoSubmitForm(oAjax) {

	js_removeObj('divCarregando');

	var oRetorno = JSON.parse(oAjax.responseText);
	switch (oRetorno.iStatus) {

		/**
		 * CASE que exibe que a operação foi realizada com sucesso.
		 */
	  case 1:
			
			alert('Operação concluída com sucesso.');
			window.location = "cad4_iptuendergrupo001.php";
		break;

		/**
		 * CASE que exibe que houve um erro na operação e descreve o mesmo.
		 */
		case 2:

			alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n'));
			return false;
		break;

		/**
		 * CASE que exibe quais das matrículas informadas pertencem a outros grupos de endereço de entrega.
		 * Exibimos um confirm para o usuário decidir se quer desvincular essas matrículas dos grupos de endereço.
		 */
		case 3:

			if (confirm(oRetorno.sMessage.urlDecode().replace(/\\n/g,"\n"))) {
				js_submitForm(true);
			} else {
				return false;
			}
		break;
	}
}

/**
 * Função que envia a solicitação de deleção dos endereços de entrega das matrículas marcadas na grid para o RPC.
 * Primeiramente validamos se há alguma matrícula marcada na grid e após isso, caso haja alguma matrícula selecionada,
 * passamos um array com as matrículas selecionadas para o RPC.
 */
function js_excluirEnderecos() {

	var aSelecionadosGrid = oGrid.getSelection();
	if (aSelecionadosGrid.length == 0) {

		alert('Ao menos uma matrícula deve ser selecionada na tabela acima. Favor verificar.');
		return false;
	}

	var aMatriculasSelecionadas = new Array();
	aSelecionadosGrid.each(function(aSelecionado, iIndice) {
		aMatriculasSelecionadas[iIndice] = aSelecionado[0];
	});

	var oParam                         = new Object();
	    oParam.sExec                   = 'excluirEnderecos';
	    oParam.aMatriculasSelecionadas = aMatriculasSelecionadas;

	js_divCarregando('Excluindo matricula(s)','divCarregando');

	var oAjax = new Ajax.Request(sUrlRpc,
			                         {method     : 'post',
                                parameters : 'json='+Object.toJSON(oParam),
                                onComplete : js_retornoExcluirEnderecos});
}

/**
 * Função que trata o retorno da função js_excluirEnderecos.
 */
function js_retornoExcluirEnderecos(oAjax) {

	js_removeObj('divCarregando');

	var oRetorno = JSON.parse(oAjax.responseText);
	if (oRetorno.iStatus == 1) {

		alert("Endereços excluídos com sucesso.");
		window.location = "cad4_iptuendergrupo001.php?db_opcao=3";
	}

	/**
	 * Erro no procedimento 
	 */
	if (oRetorno.iStatus == 2) {

		alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,"\n"));
		return false;
	}
}

function js_voltar() {

	var sDbOpcao = '';

	if (db_opcao) {
		sDbOpcao = '?db_opcao=' + db_opcao;
	}

	window.location = 'cad4_iptuendergrupo001.php' + sDbOpcao;
}

/**
 * Função que efetua a pesquisa de matrícula.
 */
function js_pesquisaMatricula(lOpen) {

	if (lOpen == true) {
		js_OpenJanelaIframe('CurrentWindow.corpo', 
				                'db_iframe_matricula', 
				                'func_iptubase.php?funcao_js=parent.js_retornoPesquisaMatricula|j01_matric|z01_nome',
				                'Pesquisa Matrículas',
				                true);
		return;
	} 

	var iMatriculaLancar = $F('iMatriculaLancar');

	if (iMatriculaLancar == '') {

		$('sNomeMatriculaLancar').value = '';
		return;
	} 

	js_OpenJanelaIframe(
		'CurrentWindow.corpo',
		'db_iframe_matricula',
		'func_iptubase.php?pesquisa_chave='+iMatriculaLancar+
		'&funcao_js=parent.js_retornoPesquisaMatricula',
		'Pesquisa Matrículas',
		false
	);

}

/**
 * Função que recebe o retorno da pesquisa de matrícula e trata o resultado.
 */
function js_retornoPesquisaMatricula() {

	db_iframe_matricula.hide();

	/**
	 * Não encontrou matricula
	 */
	if (arguments[1] == true) {

		$('iMatriculaLancar').value     = '';
		$('sNomeMatriculaLancar').value = arguments[0];
		return;
	} 

	/**
	 * Retorno da função quando é feito pesquisa pelo input da matricula
	 * Só é alterado o nome - z01_numcgm
	 */
	if (arguments[1] == false) {

		$('sNomeMatriculaLancar').value = arguments[0];
		return;
	} 

	/**
	 * Retorno pela grid, cliquando em alguma matricula 
	 */
	$('iMatriculaLancar').value     = arguments[0];
	$('sNomeMatriculaLancar').value = arguments[1];
}

/**
 * Lançar matricula para alteração do endereço 
 * 
 * @access public
 * @return void
 */
function js_lancarMatricula() {

	if ($F('iMatriculaLancar') == '') {
		return;
	}

	js_divCarregando('Carregando matricula(s)','divCarregando');

	var oParam                  = new Object();
      oParam.sExec            = "buscarMatriculas";
      oParam.buscaPorCgm      = false;
      oParam.iNumeroCgm       = $F('iCgmPesquisa');
      oParam.iDbOpcao         = db_opcao;
      oParam.iNumeroMatricula = $F('iMatriculaLancar');
			oParam.lLancarMatricula = true;

	var oAjax = new Ajax.Request(
		sUrlRpc, 
		{
			method : 'post',
			parameters : 'json='+Object.toJSON(oParam),
			onComplete : js_retornoLancarMatriculas
		}
	);
}

/**
 * Retorno lançar grid
 */
function js_retornoLancarMatriculas(oAjax) {

	js_removeObj('divCarregando');

	var oRetorno = JSON.parse(oAjax.responseText);
	if (oRetorno.iStatus == 2) {

		alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n'));
		return false;
	}

	if ( oRetorno.iStatus == 3 ) {
		
		alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n'));
		js_voltar();
		return false;
	}

	for (var iIndice = 0; iIndice < oRetorno.aMatriculas.length; iIndice++) {

		var oDados = oRetorno.aMatriculas[iIndice];

		/**
		 * Percorre a grid para verificar se matricula já foi lançada
		 */
		if (oGrid.aRows.length > 0) {

			for ( var iIndiceMatricula = 0; iIndiceMatricula < oGrid.aRows.length; iIndiceMatricula++) {
				
				if ( oDados.iMatricula == oGrid.aRows[iIndiceMatricula].aCells[1].getValue() ) {

					alert('Matrícula ' + oDados.iMatricula + ' já lançada');
					$('iMatriculaLancar').value     = '';
					$('sNomeMatriculaLancar').value = '';
					return;
				}	
			}
		}

		var sEnderecoPronto = oDados.sEndereco + ", " + oDados.iNumero+", ";

		if (oDados.sComplemento != "") {
			sEnderecoPronto += oDados.sComplemento+", ";
		}
		
		sEnderecoPronto += oDados.sMunicipio;

		if (oDados.sEndereco == "") {
			sEnderecoPronto = "Matrícula sem endereço de entrega registrado.";
		}

		var aLinha    = new Array();
	      aLinha[0] = oDados.iMatricula;
	      aLinha[1] = sEnderecoPronto.urlDecode().replace(/\\n/g,'\n'); 
	      aLinha[2] = oDados.sTipoVinculo.urlDecode().replace(/\\n/g,'\n');  

		oGrid.addRow(aLinha, null, null, true);
	}

	oGrid.renderRows();

	$('iMatriculaLancar').value     = '';
	$('sNomeMatriculaLancar').value = '';
}

String.prototype.urlEncode = function() {

	var sString = this;
	
	encodeURIComponent( tagString( sString ) );
	
	return sString;
}
</script>
