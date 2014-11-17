<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: saude
$clcgs_und->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j14_codigo");
$clrotulo->label("j13_cod");
$clrotulo->label("j13_codi");
$clrotulo->label("DBtxt1");
$clrotulo->label("DBtxt5");
$clrotulo->label("s115_c_cartaosus");
$clrotulo->label("s115_i_codigo");
$clrotulo->label("s115_c_tipo");

$municipio   = "S";
$retornacgs  = isset( $retornacgs ) ? $retornacgs : "";
$retornanome = isset( $retornanome ) ? $retornanome : "";
?>
<form name="form1" method="post" action="" class="container">

  <fieldset>
    <legend>Dados CGS</legend>

    <fieldset class='separator'>
      <legend>Dados Pessoais</legend>
      <table class="form-container">
        <tr>
          <td title="<?=@$Tz01_v_cgccpf?>">
            <?=$Lz01_i_cgsund?>
          </td>
          <td>
            <?php db_input('z01_i_cgsund',15,$Iz01_i_cgsund,true,'text',3);?>
          </td>
          <td title="<?=$Lz01_i_cgsund?>" class='bold' >
            CGS do Município:
          </td>
          <td>
            <?php
  	          $x = array("S"=>"SIM","N"=>"NÃO");
           	  db_select('municipio',$x,true,$db_opcao,'onChange="js_zerac(this.value);"');
  	        ?>
          </td>
        </tr>
        <tr>
          <td title='<?=$Tz01_i_cgsund?>'>
            <?=@$Lz01_v_cgccpf?>
          </td>
          <td>
            <?php
              db_input('z01_v_cgccpf',15,@$Iz01_v_cgccpf,true,'text',$db_opcao,"onBlur='js_verificaCGCCPF(this);js_testanome(\"\",this.value,\"\")'");
            ?>
          </td>
          <td>
            <?=@$Lz01_v_ident?>
          </td>
          <td>
            <?db_input('z01_v_ident',15,$Iz01_v_ident,true,'text',$db_opcao);?>
          </td>
        </tr>
        <tr>
          <td title=<?=@$Tz01_v_nome?>>
            <?=@$Lz01_v_nome?>
          </td>
          <td title="<?=@$Tz01_v_nome?>">
            <?php db_input('z01_v_nome',52,$Iz01_v_nome,true,'text',$db_opcao," onChange=\"js_ValidaCamposEdu(this,1,'Campo Nome','f','t',event);\"");?>
          </td>
          <td title="<?=$Tz01_d_nasc?>">
            <?=$Lz01_d_nasc?>
          </td>
          <td title="<?=$Tz01_d_nasc?>">
            <?php db_inputdata('z01_d_nasc',@$z01_d_nasc_dia,@$z01_d_nasc_mes,@$z01_d_nasc_ano,true,'text',$db_opcao);?>
          </td>
        </tr>
        <tr>
          <td title=<?=@$Tz01_v_pai?>>
            <?=@$Lz01_v_pai?>
          </td>
          <td title="<?=@$Tz01_v_pai?>">
            <?php db_input('z01_v_pai',52,$Iz01_v_pai,true,'text',$db_opcao," onChange=\"js_ValidaCamposEdu(this,1,'Campo Pai','f','t',event);\"");?>
          </td>
          <td title="<?=$Tz01_i_estciv?>">
            <?=$Lz01_i_estciv?>
          </td>
          <td>
            <?php
              $x = array("1"=>"Solteiro",
                         "2"=>"Casado",
                         "3"=>"Viúvo",
                         "4"=>"Separado ",
                         "5"=>"União C.",
                         "9"=>"Ignorado");
              db_select('z01_i_estciv',$x,true,$db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td title=<?=@$Tz01_v_mae?>>
            <?=@$Lz01_v_mae?>
          </td>
          <td title="<?=@$Tz01_v_mae?>">
            <?php db_input('z01_v_mae',52,$Iz01_v_mae,1,'text',$db_opcao," onChange=\"js_ValidaCamposEdu(this,1,'Campo Mãe','f','t',event);\"");?>
          </td>
          <td>
            <?=$Lz01_v_sexo?>
          </td>
          <td>
            <?php
              $sex = array("M"=>"Masculino","F"=>"Feminino");
              db_select('z01_v_sexo',$sex,true,$db_opcao);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" class="bold">
            Cartao SUS
          </td>
          <td>
            <?php
              db_input('s115_i_codigo',15,@$Is115_i_codigo,true,'hidden',$db_opcao);
           	  db_input('s115_c_cartaosus',17,@$Is115_c_cartaosus,true,'text',$db_opcao, "onChange=validaCNS();");
            ?>
          </td>
          <td  class='bold' >
            Tipo Cartao SUS
          </td>
          <td>
            <?php
  		        $x = array("D"=>"Definitivo","P"=>"Provisório");
  	        	db_select('s115_c_tipo',$x,true,$db_opcao);
            ?>
          </td>
        </tr>

        <tr>
          <td title="<?=@$Tz01_v_telef?>">
            <?=@$Lz01_v_telef?>
          </td>
          <td >
            <?php db_input('z01_v_telef',15,$Iz01_v_telef,true,'text',$db_opcao);?>
          </td>
          <td title="<?=@$Tz01_v_telcel?>">
            <?=@$Lz01_v_telcel?>
          </td>
          <td >
            <?php db_input('z01_v_telcel',15,$Iz01_v_telcel,true,'text',$db_opcao);?>
          </td>
        </tr>

        <tr>
          <td title="<?=@$Tz01_v_email?>" class='bold'>
            E-mail
          </td>
          <td colspan="3">
            <?php db_input('z01_v_email',45,$Iz01_v_email,true,'text',$db_opcao);?>
          </td>
        </tr>
      </table>
    </fieldset>


    <fieldset class='separator'>
      <legend>Endereço</legend>
      <table class="form-container">
        <tr>
          <td title="<?=@$Tz01_v_cep?>">
            <?php
              db_ancora(@$Lz01_v_cep,"js_cepcon(true);",$db_opcao);
            ?>
          </td>
          <td>
            <?php db_input('z01_v_cep',8,$Iz01_v_cep,true,'text',$db_opcao);?>
            <input type="button" name="buscacep" value="Pesquisar" onClick="js_cepcon(false);">
          </td>
        </tr>
        <tr>
          <td title="<?=@$Tz01_v_ender?>">
            <?php db_ancora(@$Lz01_v_ender,"js_ruas();",$db_opcao);?>
          </td>
          <td>
           <?db_input('z01_v_ender',52,$Iz01_v_ender,true,'text',$db_opcao);?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Tz01_i_numero?>">
            <?=@$Lz01_i_numero?>
          </td>
          <td>
            <a name="AN3">
              <?php db_input('z01_i_numero',8,$Iz01_i_numero,true,'text',$db_opcao);?>
          </td>
          <td>
            <?=@$Lz01_v_compl?>
          </td>
          <td>
            <?php db_input('z01_v_compl',15,$Iz01_v_compl,true,'text',$db_opcao);?>
            </a>
          </td>
        </tr>
     	  <tr>
          <td title="<?=$Tz01_v_bairro?>">
            <?php db_ancora(@$Lz01_v_bairro,"js_bairro();",$db_opcao);?>
          </td>
          <td >
            <?php
              db_input('j13_codi',10,$Ij13_codi,true,'hidden',$db_opcao);
              db_input('z01_v_bairro',52,$Iz01_v_bairro,true,'text',3);
            ?>
          </td>
          <td title="<?=@$Tz01_d_cadast?>">
            <?=@$Lz01_d_cadast?>
          </td>
          <td>
            <?php db_inputdata('z01_d_cadast',@$z01_d_cadast_dia,@$z01_d_cadast_mes,@$z01_d_cadast_ano,true,'text',$db_opcao);?>
          </td>
        </tr>
        <tr>
          <td title="<?=@$Tz01_v_munic?>">
            <?php db_ancora("Município:", "js_buscaMunicipio();", $db_opcao);?>
          </td>
          <td>
            <?php db_input('z01_v_munic', 30, $Iz01_v_munic, true, 'text', 3);?>
          </td>
          <td title="<?=@$Tz01_v_uf?>">
            <?=@$Lz01_v_uf?>
          </td>
          <td>
            <?php db_input('z01_v_uf', 2, $Iz01_v_uf, true, 'text', 3);?>
         </td>
        </tr>

        <tr>
          <td title="<?=@$Tz01_codigoibge?>" class='bold'>
            IBGE:
          </td>
          <td >
            <?php db_input('z01_codigoibge', 15, $Iz01_codigoibge, true, 'text', 3);?>
          </td>

          <td title="<?=@$Tz01_v_cxpostal?>">
            <?=@$Lz01_v_cxpostal?>
          </td>
          <td >
            <?php db_input('z01_v_cxpostal',15,$Iz01_v_cxpostal,true,'text',$db_opcao);?>
          </td>
        </tr>

      </table>
    </fieldset>
  </fieldset>
  <input type="hidden"
         value="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
         name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>" />
  <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
         type="button"
         id="db_opcao"
         value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
         <?=( $db_botao == false ? "disabled" : "" )?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" <?=$db_opcao==1?"disabled":""?>>
  <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" <?=$db_opcao==1?"disabled":""?>>
  <?php
    if ( isset( $retornacgs ) ) {
	   echo '<input name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_cgs_und.hide();">';
    } elseif ( isset( $funcao_js ) ) {

	    echo '<input name="fechar" id="fechar" type="button" value="Fechar" onclick="parent.db_iframe_cgs_und.hide();">';
      echo '<input name="funcaojs" id="funcaojs" type="hidden" value="'.$funcao_js.'">';
    }
  ?>
</form>
<script>
var iOpcao = <?=$db_opcao;?>;

$('db_opcao').onclick = function() {
  validaIBGE();
};

function validaIBGE() {

  if( iOpcao != 3 && empty( $F('z01_codigoibge') ) ) {

    alert( 'Código IBGE não informado.' );
    return;
  }

  document.form1.submit();
}

function js_ruas() {
  js_OpenJanelaIframe('','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas|j14_codigo|j14_nome','Pesquisa',true);
}

function js_preenchepesquisaruas( chave, chave1 ) {

  document.form1.z01_v_ender.value = chave1;
  db_iframe_ruas.hide();
}

function js_bairro() {
  js_OpenJanelaIframe('','db_iframe_bairro','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro|j13_codi|j13_descr','Pesquisa',true);
}

function js_preenchebairro( chave, chave1 ) {

  document.form1.j13_codi.value = chave;
  document.form1.z01_v_bairro.value = chave1;
  db_iframe_bairro.hide();
}

function js_ruas1() {
  js_OpenJanelaIframe('','db_iframe_ruas1','func_ruas.php?rural=1&funcao_js=parent.js_preenchepesquisaruas1|j14_codigo|j14_nome','Pesquisa',true);
}

function js_preenchepesquisaruas1( chave, chave1 ) {
  document.form1.z01_v_endcon.value = chave1;
  db_iframe_ruas1.hide();
}

function js_bairro1() {
  js_OpenJanelaIframe('','db_iframe_bairro1','func_bairro.php?rural=1&funcao_js=parent.js_preenchebairro1|j13_codi|j13_descr','Pesquisa',true);
}

function js_preenchebairro1( chave, chave1 ) {
  document.form1.z01_v_baicon.value = chave1;
  db_iframe_bairro1.hide();
}

function js_pesquisa() {
  js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_preenchepesquisa|z01_i_cgsund|s115_c_cartaosus&redireciona=parent.parent.js_preenchepesquisa(document.form1.z01_i_cgsund.value)','Pesquisa CGS',true);
}

function js_preenchepesquisa( chave, chave1 ) {

  db_iframe_cgs_und.hide();
  location.href = "<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>?chavepesquisa="+chave+"&retornacgs=<?=$retornacgs?>&retornanome=<?=$retornanome?>&sCartaoSus="+chave1;
}

function js_novo() {
  parent.location="sau1_cgs_und000.php?id=1";
}

function js_zerac( municipio ) {

  document.form1.z01_v_cep.value    = "";
  document.form1.z01_v_ender.value  = "";
  document.form1.z01_v_munic.value  = "";
  document.form1.z01_v_uf.value	    = "";
  document.form1.z01_v_bairro.value = "";
  $('z01_i_numero').value           = "";
  $('z01_v_compl').value            = "";
  $('z01_v_cxpostal').value         = "";
  $('z01_codigoibge').value         = "";

  if ( document.form1.municipio.value == "N" ) {

    document.form1.buscacep.disabled             = false;
    document.form1.z01_v_bairro.readOnly         = false;
    document.form1.z01_v_bairro.style.background = "#FFFFFF";
    document.links[0].style.color                = "#000000";
    document.links[0].style.textDecoration       = "none";
    document.links[0].href                       = "";
    document.links[1].style.color                = "#000000";
    document.links[1].style.textDecoration       = "none";
    document.links[1].href                       = "";
    document.links[2].style.color                = "#000000";
    document.links[2].style.textDecoration       = "none";
    document.links[2].href                       = "";
 	} else if ( document.form1.municipio.value == "S" ) {

    document.form1.z01_v_bairro.readOnly         = false;
    document.form1.z01_v_bairro.style.background = "#FFFFFF";
    document.links[0].style.color                = "blue";
    document.links[0].style.textDecoration       = "underline";
    document.links[0].href                       = "";
    document.links[1].style.color                = "blue";
    document.links[1].style.textDecoration       = "underline";
    document.links[1].href                       = "";
    document.links[2].style.color                = "blue";
    document.links[2].style.textDecoration       = "underline";
    document.links[2].href                       = "";
 	  document.form1.submit();
 	}
}

if ( document.form1.municipio.value == 'S') {
  document.form1.buscacep.disabled = true;
}

function js_cepcon( abre ) {

  if ( abre == true ) {
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|z01_v_cep','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_cep','func_cep.php?pesquisa_chave='+document.form1.z01_v_cep.value+'&funcao_js=parent.js_preenchecepcon|cep|cp06_logradouro|cp05_localidades|cp05_sigla|cp01_bairro|z01_v_cep','Pesquisa',false);
  }
}

function js_preenchecepcon( chave, chave1, chave2, chave3, chave4 ) {

  document.form1.z01_v_cep.value    = chave;
  document.form1.z01_v_ender.value  = chave1;
  document.form1.z01_v_bairro.value = chave4;

  db_iframe_cep.hide();
}

function js_preenchecepcon1( chave, chave1, chave2, chave3, chave4 ) {

  if( chave == "" && chave1 == "" && chave2 == "" && chave3 == "" && chave4 == "" ) {

    alert('CEP não encontrado!');
    document.form1.z01_v_cep.focus();
  }

  document.form1.z01_v_cep.value    = chave;
  document.form1.z01_v_ender.value  = chave1;
  document.form1.z01_v_bairro.value = chave4;
}

/**
 * Verifica se o que foi digitado é diferente de um numero e apaga a letra digitada
 */
$('s115_c_cartaosus').onkeyup = function() {
  $('s115_c_cartaosus').value = $F('s115_c_cartaosus').somenteNumeros();
}

$('s115_c_cartaosus').onkeydown = function() {
  $('s115_c_cartaosus').value = $F('s115_c_cartaosus').somenteNumeros();
}

/**
 * Valida o código do cartão SUS
 * @return {boolean}
 */
function validaCNS() {

  if ( !$F('s115_c_cartaosus').validaCNS() ) {

    alert("Número do cartão do SUS inválido.");
    $('s115_c_cartaosus').value = "";
    return false
  }

  return true;
}

function js_buscaMunicipio() {

  var sUrl  = "func_cadendermunicipiosistema.php";
      sUrl += "?iTipoSistema=4";
      sUrl += "&funcao_js=parent.js_retornoMunicipio|db72_descricao|db71_sigla|db125_codigosistema";
  js_OpenJanelaIframe('', 'db_iframe_cadendermunicipiosistema', sUrl, 'Pesquisa Município', true);
}

function js_retornoMunicipio() {

  $('z01_v_munic').value    = arguments[0];
  $('z01_v_uf').value       = arguments[1];
  $('z01_codigoibge').value = arguments[2];

  db_iframe_cadendermunicipiosistema.hide();
}


/**
 * Valida somente caracteres alfa-numericos
 */
$('z01_v_ident').observe('keyup', function () {

  var oRegex = /^[A-Za-z0-9]+$/;
  if (  !oRegex.test( this.value ) ) {
    this.value = "";
  }
});

$('z01_i_cgsund').addClassName("field-size3");
$('z01_v_cgccpf').addClassName("field-size3");
$('z01_v_nome').addClassName("field-size9");
$('z01_v_pai').addClassName("field-size9");
$('z01_v_mae').addClassName("field-size9");
$('s115_c_cartaosus').addClassName("field-size3");
$('z01_v_cep').addClassName("field-size2");
$('z01_v_ender').addClassName("field-size9");
$('z01_i_numero').addClassName("field-size2");
$('z01_v_bairro').addClassName("field-size9");
$('z01_v_munic').addClassName("field-size9");
$('z01_v_email').addClassName("field-size-max");
$('z01_v_cxpostal').addClassName("field-size3");
$('z01_v_ident').addClassName("field-size3");
$('z01_v_compl').addClassName("field-size3");
$('z01_v_telef').addClassName("field-size3");
$('z01_v_telcel').addClassName("field-size3");
$('z01_codigoibge').addClassName("field-size3");
</script>