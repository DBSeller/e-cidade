<?php
/**
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

$clrotulo = new rotulocampo;
$clrotulo->label("am05_sequencial");
$clrotulo->label("am05_nome");
$clrotulo->label("am08_protprocesso");
$clrotulo->label("am08_tipolicenca");
$clrotulo->label("am08_dataemissao");
$clrotulo->label("am08_datavencimento");

$db_opcao = 1;
?>
<form name="formEmissaoLicenca" id="formEmissaoLicenca" method="post" action="">

  <fieldset>
    <legend>Emissão de Licenças</legend>

    <table>
      <tr>
        <td nowrap title="<?php echo $Tam05_sequencial; ?>">
         <?php
          db_ancora($Lam05_sequencial,' js_pesquisaEmpreendimento(true); ',1);
         ?>
        </td>
        <td>
         <?php
          db_input('am05_sequencial',5,1,true,'text',1,"onchange='js_pesquisaEmpreendimento(false)'");
          db_input('am05_nome',40,0,true,'text',3,"",null);
         ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?php echo $Tam08_protprocesso; ?>">
         <?php
          db_ancora('Código do Processo:',' js_pesquisaProcesso(true); ',1);
         ?>
        </td>
        <td>
         <?php
          db_input('am08_protprocesso',5,1,true,'text',1,"onchange='js_pesquisaProcesso(false)'");
          db_input('p51_descr',40,0,true,'text',3,"",null);
         ?>
        </td>
      </tr>
    </table>

    <fieldset>

      <legend>Dados da Licença</legend>

      <table>

        <tr>
          <td nowrap title="<?php echo $Tam08_tipolicenca; ?>">
            <?php echo $Lam08_tipolicenca; ?>
          </td>
          <td>
            <?php
              $aOpcoes = array(''  => 'Selecione');
              db_select('am08_tipolicenca', $aOpcoes, true, $db_opcao, "onchange='js_getTiposEmissao()'");
            ?>
          </td>
        </tr>

         <tr>
          <td nowrap style="width:150px;" >
            <strong>Tipo de Emissão:</strong>
          </td>
          <td>
            <?php
              db_select('tipoEmissao', $aOpcoes, true, $db_opcao);
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tam08_dataemissao; ?>">
            <?php echo $Lam08_dataemissao; ?>
          </td>
          <td>
            <?php

              $sDataHoje = date( "d/m/Y", db_getsession("DB_datausu") );
              $aDataHoje = explode("/", $sDataHoje);
              $am08_dataemissao_dia = $aDataHoje[0];
              $am08_dataemissao_mes = $aDataHoje[1];
              $am08_dataemissao_ano = $aDataHoje[2];

              db_inputdata('am08_dataemissao',$am08_dataemissao_dia,$am08_dataemissao_mes,$am08_dataemissao_ano,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap title="<?php echo $Tam08_datavencimento; ?>">
            <?php echo $Lam08_datavencimento; ?>
          </td>
          <td>
            <?php

              $am08_datavencimento_dia = '';
              $am08_datavencimento_mes = '';
              $am08_datavencimento_ano = '';
              db_inputdata('am08_datavencimento',$am08_datavencimento_dia,$am08_datavencimento_mes,$am08_datavencimento_ano,true,'text',$db_opcao,"");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

  </fieldset>

  <input name="emitir" type="button" id="emitir" value="Emitir" onclick="return js_validaEmissao();"/>
  <input name="limpar" type="reset"  id="limpar" value="Limpar" onclick="js_limpaFormulario()"/>

</form>
<script type="text/javascript">

$('tipoEmissao').addClassName('field-size3');
$('am08_tipolicenca').addClassName('field-size3');

var sCaminhoMensagens = "tributario.meioambiente.amb4_emissaodelicenca.";
var sRpc              = "amb4_emissaodelicenca.RPC.php";

/**
 * Função que reseta o formulário, mantendo os dados do empreendimento
 */
function js_limpaFormulario() {

  var iCodigoEmpreendimento = $('am05_sequencial').value;

  $('limpar').click();

  $('am05_sequencial').value = iCodigoEmpreendimento;
}

function js_validaEmissao(){

  if( !isNumeric( $F('am05_sequencial') ) || empty( $F('am05_sequencial') ) ){

    alert( _M( sCaminhoMensagens + 'empreendimento_obrigatorio' ) );
    return false;
  }

  if( !isNumeric( $F('am08_protprocesso') ) || empty( $F('am08_protprocesso') )  ){

    alert( _M( sCaminhoMensagens + 'processo_obrigatorio' ) );
    return false;
  }

  if( empty( $F('am08_tipolicenca') ) ){

    alert( _M( sCaminhoMensagens + 'tipo_de_licenca_obrigatorio' ) );
    return false;
  }

  if( empty( $F('tipoEmissao') ) ){

    alert( _M( sCaminhoMensagens + 'tipo_de_emissao_obrigatorio' ) );
    return false;
  }

  if( empty( $F('am08_dataemissao') ) ){

    alert( _M( sCaminhoMensagens + 'data_emissao_obrigatorio' ) );
    return false;
  }

  if( empty( $F('am08_datavencimento') ) ){

    alert( _M( sCaminhoMensagens + 'data_vencimento_obrigatorio' ) );
    return false;
  }

  var oParametros = {
      sExecucao             : 'emitirLicenca',
      iCodigoEmpreendimento : $F('am05_sequencial'),
      iCodigoProtocolo      : $F('am08_protprocesso'),
      iTipoLicenca          : $F('am08_tipolicenca'),
      iTipoEmissao          : $F('tipoEmissao'),
      sDataEmissao          : $F('am08_dataemissao'),
      sDataVencimento       : $F('am08_datavencimento')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    alert(oRetorno.sMensagem.urlDecode());

    if (erro) {
      return false;
    }

    /**
     * Utilizar a DBDownload.widget.js
     */
    var oDownload  = new DBDownload();
    oDownload.addGroups( 'sxw', 'Licença');
    oDownload.addFile( oRetorno.sArquivoRetorno, 'Download da Licença', 'sxw' );
    oDownload.show();

    $('limpar').click();

  }).setMessage( _M( sCaminhoMensagens + 'carregando_emissao' ) ).execute();
}

/**
 * Func Empreendimento
 */
function js_pesquisaEmpreendimento(mostra) {

  js_limpaFormulario();

  if (mostra==true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_empreendimento','func_empreendimento.php?funcao_js=parent.js_mostraempreendimento1|0|1','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('top.corpo','db_iframe_empreendimento','func_empreendimento.php?pesquisa_chave='+document.formEmissaoLicenca.am05_sequencial.value+'&funcao_js=parent.js_mostraempreendimento','Pesquisa',false,0);
  }
}

function js_mostraempreendimento1(chave1,chave2) {

  document.formEmissaoLicenca.am05_sequencial.value = chave1;
  document.formEmissaoLicenca.am05_nome.value       = chave2;
  db_iframe_empreendimento.hide();
  js_getTiposLicenca();
}

function js_mostraempreendimento(chave,erro) {

  document.formEmissaoLicenca.am05_nome.value = chave;
  if (erro==true) {

    document.formEmissaoLicenca.am05_sequencial.focus();
    document.formEmissaoLicenca.am05_sequencial.value = '';
  }else{
    js_getTiposLicenca();
  }
}

/**
 * Func protprocesso
 */
function js_pesquisaProcesso(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_processo','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|p51_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_processo','func_protprocesso.php?pesquisa_chave='+document.formEmissaoLicenca.am08_protprocesso.value+'&rettipoproc=true&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
  }
}

function js_mostraprocesso1(chave1,chave2){

  document.formEmissaoLicenca.am08_protprocesso.value = chave1;
  document.formEmissaoLicenca.p51_descr.value         = chave2;
  db_iframe_processo.hide();
}

function js_mostraprocesso(chave, sDescricao, lErro){

  document.formEmissaoLicenca.p51_descr.value = sDescricao;
  if( lErro==true){

    document.formEmissaoLicenca.am08_protprocesso.focus();
    document.formEmissaoLicenca.am08_protprocesso.value = '';
  }
}

/**
 * Função que retorna os tipos de licença disponíveis para emissão
 */
function js_getTiposLicenca(){

  if( !isNumeric( $F('am05_sequencial') ) || empty( $F('am05_sequencial') ) ){

    alert( _M( sCaminhoMensagens + 'empreendimento_obrigatorio' ) );
    return false;
  }

  var oParametros = {
      sExecucao             : 'getTiposLicenca',
      iCodigoEmpreendimento : $F('am05_sequencial')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    /**
     * Remove options e adiciona o padrão
     */
    var oSelectLicenca       = $('am08_tipolicenca');

    oSelectLicenca.innerHTML = '';
    var option               = document.createElement("option");
    option.text              = 'Selecione';
    option.value             = '';
    oSelectLicenca.add(option);

    for (var key in oRetorno.aTiposLicenca) {

      if (!isNumeric(key)) {
        break;
      }

      var option   = document.createElement("option");
      option.text  = oRetorno.aTiposLicenca[key];
      option.value = key;
      oSelectLicenca.add(option);
    }
  }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_tipolicenca' ) ).execute();
}

/**
 * Função que retorna os tipos de emissão disponíveis
 */
function js_getTiposEmissao(){

  if( !isNumeric( $F('am05_sequencial') ) || empty( $F('am05_sequencial') ) ){

    alert( _M( sCaminhoMensagens + 'empreendimento_obrigatorio' ) );
    return false;
  }

  if ( !isNumeric( $F('am08_tipolicenca') ) ) {
    return false;
  }

  var oParametros = {
      sExecucao             : 'getTiposEmissao',
      iCodigoEmpreendimento : $F('am05_sequencial'),
      iTipoLicenca          : $F('am08_tipolicenca')
  }

  new AjaxRequest(sRpc, oParametros, function(oRetorno, erro) {

    if (erro) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    /**
     * Remove options e adiciona o padrão
     */
    var oSelectEmissao       = $('tipoEmissao');

    oSelectEmissao.innerHTML = '';
    var option               = document.createElement("option");
    option.text              = 'Selecione';
    option.value             = '';
    oSelectEmissao.add(option);

    for (var key in oRetorno.aTiposEmissao) {

      if (!isNumeric(key)) {
        break;
      }

      var option   = document.createElement("option");
      option.text  = oRetorno.aTiposEmissao[key];
      option.value = key;
      oSelectEmissao.add(option);
    }
  }).setMessage( _M( sCaminhoMensagens + 'carregando_dados_tipolicenca' ) ).execute();
}
</script>
