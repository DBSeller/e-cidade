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

//use ECidade\Tributario\Cadastro\Iptu\CalculoRetroativo\Repository\CalculoRetroativoIptuRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

/*
$calculoRetroativoIptuRepository = CalculoRetroativoIptuRepository::getInstance();

$calculoRetroativoIptuRepository->setAnousu(db_getsession("DB_anousu"))
	->setAnoRetroativoMatricula(db_getsession("DB_anoRetroativoMatricula", false));
$calculoRetroativoIptuRepository->getAlteraSearchPath();
*/

$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");

$cllote = new cl_lote;
$cllote->rotulo->label();
$cltesinter = new cl_tesinter;
$cltesinter->rotulo->label();
$cltesinterlote = new cl_tesinterlote;
$cltesinterlote->rotulo->label();

db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$oGet = db_utils::postMemory($_GET);

$intNumLinhasVolta = 0;

if (isset($enviar)) {

	$rsLotes = $cllote->sql_record($cllote->sql_query('',
                '*',
                '',
                " j34_setor = '" .
                str_pad($j34_setor, 4, "0", STR_PAD_LEFT) .
                "' and j34_quadra = '" .
                str_pad($j34_quadra, 4, "0", STR_PAD_LEFT) . "'"));
  $matriz = "";
  $car = "";

	for ($i = 0; $i < $cllote->numrows; $i++) {
      db_fieldsmemory($rsLotes, $i);
      $idbql = "idbql" . $i;
      $orientacao = "origem" . $i;
      $outro = "outro" . $i;
      $j84_observacao = "j84_observacao" . $i;
      $testad = "j39_testad" . $i;
      $testle = "j39_testle" . $i;
      $tesinter_excluida = "tesinter_excluida" . $i;
      $j39_sequencial = "j39_sequencial" . $i;

      if (((isset($$idbql) && $$idbql != "0") || (isset($$outro) && $$outro != "0"))) {
         $matriz .= $car . $$idbql . "-" . $$orientacao . "-" . $$testad . "-" . $$testle . "-" . (isset($$outro) ? $$outro : "0") . "-" . (isset($$j84_observacao) ? $$j84_observacao : "") . "-" . $$tesinter_excluida . "-" . $$j39_sequencial;
      }

      $car = "X";
	}

  echo "<script> parent.document.form1.testadainter.value = '".$matriz."'; </script>";
	echo "<script> parent.db_iframe.hide(); </script>";
}

if (isset($idbql) && $idbql != '' && empty($car)) {
  $rsDadosLote = $cllote->sql_record($cllote->sql_query(null,'j34_lote',null," j34_idbql = {$idbql} "));
  if ($cllote->numrows > 0) {
     db_fieldsmemory($rsDadosLote,0);
  }
}

if (isset($$idbql) && $$idbql != '') {
  $rsDadosLote = $cllote->sql_record($cllote->sql_query(null,'j34_lote',null," j34_idbql = {$$idbql} "));
  if ($cllote->numrows > 0) {
     db_fieldsmemory($rsDadosLote,0);
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
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("AjaxRequest.js");
?>

<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script>
function js_controlaIdbql(obj,idlinha){

  if ( obj.value != '0' ){
     document.getElementById('outro'+idlinha).disabled = true;
     document.getElementById('j84_observacao'+idlinha).disabled = true;
     document.getElementById('j84_observacao'+idlinha).value = "";
  } else {
     document.getElementById('outro'+idlinha).disabled = false;
     document.getElementById('j84_observacao'+idlinha).disabled = false;
     document.getElementById('j84_observacao'+idlinha).value = "";
  }

  selects = document.getElementsByTagName('select');
	for(i=0;i<selects.length;i++){
	 	if(selects[i] != obj && selects[i].value != 0 && selects[i].name.substr(0,5)=='idbql'){

		  if(selects[i].value == obj.value){

        return;
        alert('Código do lote já selecionado !');
        obj.value = 0;
        document.getElementById('outro'+idlinha).disabled = false;
        document.getElementById('j84_observacao'+idlinha).disabled = false;
		  }
		}
	}
}

function js_controlaOutros(obj,idlinha){

  if ( obj.value != '0' ){
     document.getElementById('idbql'+idlinha).disabled = true;
     document.getElementById('j84_observacao'+idlinha).disabled = false;
  } else {
     document.getElementById('idbql'+idlinha).disabled = false;
     document.getElementById('j84_observacao'+idlinha).disabled = true;
     document.getElementById('j84_observacao'+idlinha).value = "";
  }

  selects = document.getElementsByTagName('select');

	for(i=0;i<selects.length;i++){

	 	if(selects[i] != obj && selects[i].value != 0 && selects[i].name.substr(0,5)=='idbql'){

		  if(selects[i].value == obj.value){

		    alert('Codigo do lote ja selecionado !');
		    obj.value = 0;
		  }
		}
	}
}

function js_controlaOrigem(obj,idlinha){
	selects = document.getElementsByTagName('select');
	for(i=0;i<selects.length;i++){
	 	if(selects[i] != obj && selects[i].value != 0 && selects[i].name.substr(0,6)=='origem'){
		  if(selects[i].value == obj.value){

			return;
		    alert('Não pode ser cadastrado mais de um lote para a mesma orientação !');
		    obj.value = 0;
		  }
		}
	}
}

function js_addLinhaZero(){
  document.getElementById("linha0").style.display = "";
}
function js_addLinha(idlinha){
	if(idlinha > 0){
    eval('document.getElementById("nlinha'+(idlinha-1)+'").disabled = true;');
    eval('document.getElementById("j84_observacao'+(idlinha)+'").disabled = true;');
    eval('document.getElementById("j84_observacao'+(idlinha)+'").value = "";');
	}else{
    document.getElementById("mostrarlinhas").disabled = true;
	}

    if ( !(document.getElementById('linha'+idlinha)) ) {
       return true;
    }

	// seta "" para o display para mostrar a linha
  eval('document.getElementById("linha'+idlinha+'").style.display = "";');
}
function js_delLinha(idlinha){
	if(idlinha > 0){
    eval('document.getElementById("nlinha'+(idlinha-1)+'").disabled = false;');
	}else{
    document.getElementById("mostrarlinhas").disabled = false;
	}
  eval('document.getElementById("linha'+idlinha+'").style.display = "none";');
	js_limpaCamposLinha(idlinha);
}
function js_limpaCamposLinha(idlinha){
  eval('document.getElementById("j39_testad'+(idlinha)+'").value = 0;');
  eval('document.getElementById("j39_testle'+(idlinha)+'").value = 0;');
  eval('document.getElementById("idbql'+(idlinha)+'").value = 0;');
  eval('document.getElementById("origem'+(idlinha)+'").value = 0;');
  eval('document.getElementById("outro'+(idlinha)+'").value = 0;');
  eval('document.getElementById("j84_observacao'+(idlinha)+'").value = "";');
  eval('document.getElementById("tesinter_excluida'+(idlinha)+'").value = 1;');
}
function js_checaNum(obj){
	if (obj.value == ''){
		obj.value = 0;
	}
  var valor = new Number(obj.value);
  if (isNaN(valor)) {
		alert('Valor invalido para Medida');
		obj.value = '';
    obj.focus();
  }
}
function js_checa3(){
	var objForm = document.form1;
	var bValidacao = false;
	var rowCount = document.getElementById('tesinter_table').rows.length;
	
	for(i=0;i<objForm.length;i++){
    	bValidacao = js_validaLinha(i);
	 	if(!bValidacao){
			  return false;
	  }
	}
}
function js_validaLinha(idLinha){
	var testad = 0;
	var testle = 0;
	var idbql  = 0;
	var origem = 0;
  var outro  = 0;
  var j84_observacao = '';

  if (idLinha < 20) {
     testad = document.getElementById(['j39_testad'+idLinha]).value;
     testle = document.getElementById(['j39_testle'+idLinha]).value;
     idbql  = document.getElementById(['idbql'+idLinha]).value;
     origem = document.getElementById(['origem'+idLinha]).value;
     outro  = document.getElementById(['outro'+idLinha]).value;
     j84_observacao = document.getElementById(['j84_observacao'+idLinha]).value;
  }

	if (( idbql != 0 || outro !=0 ) && (origem == 0)) {
		alert('Campo orientação é obrigatorio !');
		return false;

	} else if ( ( testad != 0 || testle !=0 ) && (( idbql == 0 && outro == 0 ) ) ){
		alert('Informe Campo Lote ou Campo Outro !');
		return false;

	} else if ( ( origem != 0 ) && (( idbql == 0 && outro == 0 ) ) ){
		alert('Informe Campo Lote ou Campo Outro !');
		return false;

	} else {

		return true;
	}

}

</script>
</head>
<body class="body-default">

<div class="container">

<table style="width: 900px" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" method="post" >
  <tr>
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
	<?php
        $sSqlLotes = $cllote->sql_query(null,
            ' * ',
            null,
            " j34_setor = '" . str_pad($j34_setor, 4, "0", STR_PAD_LEFT) .
            "' and j34_quadra = '" .
            str_pad($j34_quadra, 4, "0", STR_PAD_LEFT) . "' ");
        
        $rsLotes = $cllote->sql_record($sSqlLotes);

        if ($cllote->numrows > 0) {

            echo "<table width='30%' border='0' cellspacing='0' align='left'>";
            echo "  <tr align='left' width='100%'>" . "\n";
            echo "    <td align='left' >$Lj34_setor</td>";
            echo "    <td align='left' >$j34_setor</td>";
            echo "    <td align='left' >$Lj34_quadra</td>";
            echo "    <td align='left' >$j34_quadra</td>";
            echo "  </tr>\n";
            echo "</table> <br><br>\n";

            echo "<table id='tesinter_table' width='100%' cellspacing='0' style='border: 1px solid #000000;'>";
            echo "<tr align='center' width='100%' style='border: 1px solid #000000;'>" . "\n";
            echo "  <td class='table_header' align='center' width='10%'>$Lj34_idbql</td>";
            echo "  <td class='table_header' align='center' width='40%'>Proprietário</td>";
            echo "  <td class='table_header' align='center' width='10%'><b>Outro:</b></td>";
            echo "  <td class='table_header' align='center' width='10%'><b>Outro Observação:</b></td>";
            echo "  <td class='table_header' align='center' width='10%'><b>Orientação:</b></td>";
            echo "  <td class='table_header' align='center' width='10%'><b>Testada MI:</b></td>";
            echo "  <td class='table_header' align='center' width='10%' ><b>Testada Medida:</b></td>";
            echo "  <td class='table_header' align='center' width='10%'><b>Ação</b>";
            echo "    <input type='button'
                             id='mostrarlinhas'
                             name='mostrarlinhas'
                             value='incluir Novo'
                             disabled onClick='js_addLinhaZero();'> "; // botao nova linha
            echo "  </td>";
            echo "</tr>\n";

            $sqlOutros = " select 0 as j92_sequencial, 'Nenhum' as j92_descr  ";
            $sqlOutros .= " union ";
            $sqlOutros .= " select j92_sequencial,j92_descr from tesintertipo ";
            $rsOutros = db_query($sqlOutros);
            $intOutros = pg_numrows($rsOutros);

            for ($iOutros = 0; $iOutros < $intOutros; $iOutros++) {
                db_fieldsmemory($rsOutros, $iOutros);
                $arrayOutros[$j92_sequencial] = $j92_descr;
            }

            $sqlIdbql = " select 0 as j34_idbql, 'Nenhum' as descr from lote union ";
            $sqlIdbql .= " select j34_idbql, j34_lote::text as descr ";
            $sqlIdbql .= "   from lote ";
            $sqlIdbql .= " where j34_setor  = '" . @str_pad($j34_setor, 4, "0", STR_PAD_LEFT) . "'";
            $sqlIdbql .= "   and j34_quadra = '" . @str_pad($j34_quadra, 4, "0", STR_PAD_LEFT) . "'";
            $sqlIdbql .= "   and j34_lote  != '" . @str_pad($j34_lote, 4, "0", STR_PAD_LEFT) . "'";

            $rsIdbql = db_query($sqlIdbql);
            $intIdbql = pg_numrows($rsIdbql);

            for ($iIdbql = 0; $iIdbql < $intIdbql; $iIdbql++) {
                db_fieldsmemory($rsIdbql, $iIdbql);
                $arrayIdbql[$j34_idbql] = $descr;
            }

            $sqlOri = "select 0 as j64_sequencial, 'Nenhum' as j64_descricao ";
            $sqlOri .= " union ";
            $sqlOri .= "select j64_sequencial, j64_descricao from orientacao ";

            $rsOri = db_query($sqlOri);
            $intOri = pg_numrows($rsOri);

            for ($iOri = 0; $iOri < $intOri; $iOri++) {
                db_fieldsmemory($rsOri, $iOri);
                $arrayOri[$j64_sequencial] = $j64_descricao;
            }

            if (isset($matrizvolta)) {
                $matrizvolta = explode("X", $matrizvolta);
                $intNumLinhasVolta = sizeof($matrizvolta);
            }

            echo "<tbody style='background-color:#FFFFFF'>";

            $aDesabilitar = array();

            for ($iTes = 0; $iTes < $intNumLinhasVolta; $iTes++) {
            
                if (count($matrizvolta) > 0) {
                   $matrizdados = explode("-", $matrizvolta[$iTes]);
                   if (empty($matrizdados[0]) && empty($matrizdados[4]) && empty($matrizdados[7])) {
                      array_pop($matrizvolta);
                   }
                }
            }

            $iTestadas = $intNumLinhasVolta;

            for ($fq = 0; $fq < 20; $fq++) {

                $temvalor = false;
                if ($fq < $iTestadas && count($matrizvolta) > 0) {
                    $matrizdados = explode("-", $matrizvolta[$fq]);
                    $temvalor = true;
                } else {
                    $temvalor = false;
                }

                if ($fq == 0) {
                    $stylelinha = '';
                } else {
                    $stylelinha = "style='display:none'";
                }

                $disabled = '';
                $disabledIdbql = '';
                $disabledOutros = '';
                $disabledOutrosObs = '';

                if (isset($temvalor) && $temvalor == true && $iTestadas > $fq) {

                    $stylelinha = '';

                    if ($matrizdados[6] == "1") {
                        $stylelinha = "style='display:none'";
                    }

                    $x = "idbql" . $fq;
                    $$x = $matrizdados[0];

                    $x = "origem" . $fq;
                    $$x = $matrizdados[1];

                    $x = "j39_testad" . $fq;
                    $$x = $matrizdados[2];

                    $x = "j39_testle" . $fq;
                    $$x = $matrizdados[3];

                    $x = "outro" . $fq;
                    $$x = $matrizdados[4];

                    $x = "j84_observacao" . $fq;
                    $$x = $matrizdados[5];

                    $x = "tesinter_excluida" . $fq;
                    $$x = $matrizdados[6];

                    $x = "j39_sequencial" . $fq;
                    $$x = $matrizdados[7];

                    $iIdbql = $matrizdados[0];
                    $oDaoProprietario = db_utils::getDao("iptubase");
                    $sWhereProprietario = "    j01_idbql = $iIdbql
                                           and  EXISTS ( select 1
										                 from iptubase
														 where j01_idbql = $iIdbql and j01_baixa is null )";
                    $SsqlProprietario = $oDaoProprietario->proprietario_query(null, "*", null, $sWhereProprietario);

                    $sProprietario = "Vários Prorietários";
                    $rsProprietario = $oDaoProprietario->sql_record($SsqlProprietario);
                    if ($oDaoProprietario->numrows == 1) {
                        $oDadosProprietario = db_utils::fieldsMemory($rsProprietario, 0);
                        $sProprietario = $oDadosProprietario->proprietario;
                    }

                    $x = "iProprietario" . $fq;
                    $$x = $sProprietario;

                    if ($fq < $iTestadas - 1) {
                        $disabled = 'disabled';
                    }

                    if ($matrizdados[0] != "" && $matrizdados[0] != "0") {
                        array_push($aDesabilitar, "outro$fq");
                        array_push($aDesabilitar, "j84_observacao$fq");
                    }

                    if ($matrizdados[4] != "" && $matrizdados[4] != "0") {
                        array_push($aDesabilitar, "idbql$fq");
                    }
                }

                $aux = "j39_testad" . $fq;
                if (!isset($$aux) || $$aux == '') {
                    $$aux = 0;
                }
                $aux = "j39_testle" . $fq;
                if (!isset($$aux) || $$aux == '') {
                    $$aux = 0;
                }
                $x = "j39_testle" . $fq;
                $aux = "tesinter_excluida" . $fq;
                if (!isset($$aux) || $$aux == '') {
                    $$aux = 0;
                }
                $x = "tesinter_excluida" . $fq;
                $aux = "j39_sequencial" . $fq;
                if (!isset($$aux) || $$aux == '') {
                    $$aux = 0;
                }
                $x = "j39_sequencial" . $fq;

                echo "<tr id='linha" . $fq . "' $stylelinha >\n";

                echo "<td class='linhagrid' align='center'>";
                db_select("idbql$fq", $arrayIdbql, true, $db_opcao, "onChange='js_controlaIdbql(this," . $fq . ");
                            js_getRegistroProprietarioLote(this.value, $fq)'; ");
                echo "</td>";

                echo "<td class='linhagrid' align='center'>";
                db_input("iProprietario$fq", 16, 4, true, 'text', '3', '', 'iProprietario' . $fq);
                echo "</td>";

                echo "<td class='linhagrid' align='center'>";
                db_select("outro$fq", $arrayOutros, true, $db_opcao, "onChange='js_controlaOutros(this," . $fq . ");'");
                echo "</td>";

                echo "<td class='linhagrid' align='center'>";
                db_input('j84_observacao', 30, 0, true, 'text', '', '', 'j84_observacao' . $fq,'','',40);
                echo "</td>";

                echo "<td class='linhagrid' align='center'>";
                db_select("origem$fq", $arrayOri, true, $db_opcao, "onChange='js_controlaOrigem(this," . $fq . ");'");
                echo "</td>";

                echo "<td class='linhagrid' align='center'>";
                db_input('j39_testad', 16, 4, true, 'text', '', 'onChange="js_checaNum(this);"', 'j39_testad' . $fq);
                echo "</td>";

                echo "<td class='linhagrid' align='center'>";
                db_input('j39_testle', 16, 4, true, 'text', '', 'onChange="js_checaNum(this);"', 'j39_testle' . $fq);
                echo "</td>";

                echo "<td class='linhagrid' align='center' style='display:none''>";
                db_input('tesinter_excluida', 1, 4, true, 'text', '', '', 'tesinter_excluida' . $fq);
                echo "</td>";

                echo "<td class='linhagrid' align='center' style='display:none''>";
                db_input('j39_sequencial', 16, 4, true, 'text', '', '', 'j39_sequencial' . $fq);
                echo "</td>";

                echo "<td class='linhagrid' align='center' nowrap>";
                echo "<input type='button' id='nlinha" . $fq .
                     "' name='nlinha" . $fq .
                     "' $disabled value='Novo' onClick='js_addLinha(" . ($fq + 1) . ");'> "; // botao nova linha
                echo "<input type='button' id='elinha" . $fq .
                     "' name='elinha" . $fq .
                     "' value='Excluir' onClick='js_delLinha(" . $fq . ");'> "; // botao exclui linha
                echo "</td>";
                echo "</tr>\n";
            }

            $fq--;

            echo "</tbody>";?>

            <tr>
              <td class='table_header' colspan="9" align="center" style='border: 1px solid #000000;'>
                <input type="submit" name="enviar" value="Enviar" onclick="return js_checa3();">
                <input type="button" name="Fechar" value="Fechar" onClick="parent.db_iframe.hide();">
                <?php db_input('idbql', 16, "", true, 'hidden', '', '', ''); ?>
              </td>
            </tr>
            </table>

    <?php
        } else {
            db_msgbox("Não existem lotes cadastrados para quadra selecionada !");
            echo "<script>parent.db_iframe.hide();</script>";
        }
    ?>
    </center>
    </td>
  </tr>
  </form>
</table>
</div>
</body>
</html>
<script>
<?php
   //
   // Percorre o array com o ID dos db_select que tem que desabilitar
   //
   foreach ($aDesabilitar as $valor) {
   	echo "document.getElementById('{$valor}').disabled = true;";
   }
?>

var sUrlRPC = "cad1_iptubaseRPC.php";
function js_getRegistroProprietarioLote(iValor, iIndice) {

  var oParametros         = new Object();
      oParametros.executa = 'getRegistroProprietarioLote';
      oParametros.iIdbql  = iValor;
      oParametros.sCampo  = "iProprietario" + iIndice;

  new AjaxRequest("cad1_iptubaseRPC.php", oParametros, js_retornoGetRegistroProprietarioLote).execute();
}

function js_retornoGetRegistroProprietarioLote(oAjax) {

	var oRetorno = oAjax;
	if (oRetorno.erro) {
		alert( oRetorno.mensagem.urlDecode() );
		return false;
	}
	$(oRetorno.oDados.sCampo).value = oRetorno.oDados.sProprietario .urlDecode();
}

</script>
