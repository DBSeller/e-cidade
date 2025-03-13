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
			<form name="form1" id="form" method="post" action="sped02_preenchimento.php?formularioTipo=32&integracao=1">
            <fieldset id="fieldsetContribuinte">
              <legend>Fechamento dos eventos periódicos do EFD-Reinf</legend>
                  <table class="form-container">
                      <tbody>
                      <tr>
                          <td>
                            <label for="descricao"><b>Contribuinte: </b></label>
                          </td>
                          <td>
                              <select name="contribuinte" id="contribuinte">
                          </td>
                      </tr>
                      <tr>
                          <td id="labelCompetencia"></td>
                          <td id="formularioCompetencia"></td>
                          <input type="hidden" id="preenchimento" name="preenchimento" >
                      </tr>
                    </table>
		      </fieldset>
		      <input onClick="verificaPreenchimento()" type="button" value="Processar" id="pesquisar" name="pesquisar">
			</form>
	    </div>
    </center>
</body>
<script>
const rpc = 'sped02_preenchimento.RPC.php';
const selectContribuinte = document.getElementById('contribuinte');

/**
* Instância o Input Competencia Folha
*/
const initCompetencia = () => {
  var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(false);
  oCompetenciaFolha.renderizaLabel($('labelCompetencia'));
  oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));
}

const inicializar = () => {
  initCompetencia();
  const formData = new FormData();
  formData.append('acao', 'inicializar');
  formData.append('integracao', 1);

  HttpClient.post(rpc, {
      body: formData
  }).then(response => {
      if (response.erro) {
          throw response.mensagem;
      }
      const contribuintes = response.contribuinte;
      contribuintes.forEach(i => {
        let option = new Option(i.descricao, i.cgm)
        selectContribuinte.appendChild(option);
      })
  }).catch(mensagem => alert(mensagem));
};

const verificaPreenchimento = () => {
  const form = $('form');

  if(form.ano.value == '') {alert('Ano do campo Competência é de preenchimento obrigatório.');return false;}
  if(form.mes.value == '') {alert('Mês do campo Competência é de preenchimento obrigatório.');return false;}
  form.submit();
};


(() => {
  inicializar();
})();


</script>
</html>
