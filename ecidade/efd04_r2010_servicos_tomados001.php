<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2009  DBSeller Servicos de Informatica
*                    www.dbseller.com.br
*                 e-cidade@dbseller.com.br
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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));

require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));

require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_sql.php"));

require_once(modification("libs/db_utils.php"));
$clcgm       = new cl_cgm();
$clcgm->rotulo->label();
?>

<html>
<head>
    <title>DBSeller Informática Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <?php db_app::load("estilos.css,grid.style.css, classes/DBViewFormularioFolha/CompetenciaFolha.js"); ?>
</head>
<body>
	<center>
	    <div class='container'>
			<form name="form1" id="form" method="post" action="sped02_preenchimento.php?formularioTipo=26&integracao=1">
		      <fieldset>
		        <legend>Filtro para Pesquisa</legend>
                    <table class="form-container">
                        <tr>
                            <td title="<?php echo $Tz01_numcgm; ?>">
                                <?php db_ancora($Lz01_numcgm, 'js_mostranomes(true);', 4); ?>
                            </td>
                            <td>
                                <?php db_input("z01_numcgm", 8, $Iz01_numcgm, false, "text", 5, "onkeyup=js_ValidaCampos(this,1,'Numcgm','t','f',event) onblur=js_ValidaMaiusculo(this,'f',event) onchange=js_mostranomes(false);"); ?>
                                <?php db_input("z01_nome", 40, $Iz01_nome, true, 'text', 5); ?>                  
                            </td>
                        </tr>
                        <tr>
                            <td id="labelCompetencia"></td>
                            <td id="formularioCompetencia"></td>
                            <input type="hidden" id="preenchimento" name="preenchimento" >
                        </tr>
                    </table>
		      </fieldset>              
		      <input onClick=verificaPreenchimento() type="button" value="Processar" id="pesquisar" name="pesquisar">
			</form>
	    </div>
    </center>
</body>
<script>

initCompetencia();

function js_mostranomes(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm_emepenho_retencoes','func_cgm_empenho_retencoes.php?somenteServidorAtivo=true&funcao_js=parent.js_preenche|z01_numcgm|z01_nome','Pesquisa CGM',true);
  }else{
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm_emepenho_retencoes','func_cgm_empenho_retencoes.php?somenteServidorAtivo=true&pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_preenche1','Pesquisa CMG',false);
  }
}

function js_preenche(chave,chave1){
  document.form1.z01_numcgm.value = chave;
  document.form1.z01_nome.value = chave1;
  db_iframe_cgm_emepenho_retencoes.hide();
}

function js_preenche1() {
  document.form1.z01_nome.value = arguments[1];

  if(arguments[0] === true) {
    document.form1.z01_numcgm.value = "";
    document.form1.z01_numcgm.focus();
  }
}

function verificaPreenchimento(){
  const form = $('form');

  if(form.z01_numcgm.value == '') {alert('Informe numcgm!');return false;}

  const 
    cgmprestador = form.z01_numcgm.value,
    ano          = form.ano.value,
    mes          = form.mes.value,
    urlRpc       = 'efd02_retencoesservicostomados.RPC.php',
    formData = new FormData();

  formData.append('acao', 'buscarCodigoPreenchimento');
  formData.append('cgmprestador', cgmprestador);
  formData.append('ano', ano);
  formData.append('mes', mes);

  HttpClient.post(urlRpc, {body: formData}).then(response => {
    if(!!response.preenchimento)
      form.preenchimento.value = response.preenchimento;
    
    form.submit();
  });

};

/**
* Instância o Input Competencia Folha 
*/
function initCompetencia(){  
  var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(false);
  oCompetenciaFolha.renderizaLabel($('labelCompetencia'));
  oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));
}

</script>
</html>
