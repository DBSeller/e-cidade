<?php
/**
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
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet     = db_utils::postMemory($_GET);
$oPost    = db_utils::postMemory($_POST);

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php db_app::load("estilos.css, scripts.js, strings.js, prototype.js"); ?>
    <script src="scripts/datagrid.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBModal.widget.js"></script>
    <script rel="script" type="text/javascript" src="scripts/classes/http/http.js"></script>
    <style>
      .botoes_acao {
        width:58px;
      }
      .botoes_especificos {
        width:60px;
      }

      #registrosLotebody{
        table-layout: auto !important;
      }

      #body-container-unidades{
        height: 600px !important;
      }

    </style>
  </head>
  <body>
   <div class="container">
     <fieldset>
       <legend>Liberação da Autorização de Empenho por Unidade</legend>
         
         <table class="form-container">
  
           <tr>
             <td>
               <br />
               <p>Órgãos / Unidades habilitadas para uso da liberação da autorização de empenho:</p>
             </td>
           </tr>

         </table>

         </br> 
         <div id="div_grid_unidades" style="width: 1300px"></div>
         
        </fieldset>
        <button type="button" id="btnSalvar" onclick="js_executarAcao('salvar')">
            <i class="fas fa-save"></i>
            Salvar
        </button>
   </div>
   
  </body>
</html>
<script>

  const rpcFile = 'emp4_liberacao_autorizacaoempenho.RPC.php';
  (function(oWindow){

    oWindow.oGridUnidades   = new DBGrid("unidades");
    oWindow.oGridUnidades.nameInstance = "window.oGridUnidades";
    oWindow.oGridUnidades.setCheckbox(0);
    oWindow.oGridUnidades.setHeader(["Referência","Órgão","Unidade"]);
    oWindow.oGridUnidades.setCellWidth(["10%","45%","45%"]);
    oWindow.oGridUnidades.setCellAlign(["left", "left", "left"]); 
    oWindow.oGridUnidades.show( $('div_grid_unidades') );

    carregarUnidades();

  })(window);
  /**
   * Carrega as unidades
   */
  function carregarUnidades(){
    var oDataGrid    = window.oGridUnidades;
    const parametros = new FormData();
    parametros.append('exec', 'buscarUnidades');

    HttpClient.post(rpcFile, {body: parametros}).then((response) => {
        
      window.oGridUnidades.clearAll(true);
      for (var iUnidade = 0; iUnidade < response.aResposta.length; iUnidade++) {

        var unidade = response.aResposta[iUnidade];
        var marcado = unidade.salvo != '-';

        var aLinha        = new Array();
        aLinha[0]         = unidade.orgao_id+'-'+unidade.unidade_id;
        aLinha[1]         = unidade.orgao.urlDecode();
        aLinha[2]         = unidade.unidade.urlDecode();

        window.oGridUnidades.addRow(aLinha, null, null, marcado ); 
      }

      window.oGridUnidades.renderRows();

    });
  }

  /**
   * Função que define qual ação deve ser tomada com base no que foi especificado no parametro
   */
  function js_executarAcao(sAcao) {

    var linhas = window.oGridUnidades.getSelection("array");
    var linhas_sel = linhas.length;

    var oParametros = {};
    
    

    if(linhas_sel == 0){
      alert('É preciso selecionar pelo menos uma unidade antes de salvar.');
      return false;
    }

    var unidades  = [];
    for(var i = 0;i<linhas_sel;i++){
        unidades.push(linhas[i][1]);
    }

    switch (sAcao) {
      case 'salvar':
        const parametros = new FormData();
        parametros.append('exec', 'salvarUnidades');
        parametros.append('exercicio', '<?= db_getsession("DB_anousu") ?>');

        for(var i = 0; i < linhas_sel; i++)
        {
          parametros.append('unidades[]', unidades[i]);
        }

        HttpClient.post(rpcFile, {body: parametros}).then((response) => {
 
          if (response.erro) {
            alert(oRetorno.message.urlDecode());
            return false;
          }
          
          alert(response.message);
          carregarUnidades();
    
        });

        break;       
      default:
        throw 'Operação inválida.';
        break;
    }
    
  }
  
</script>
