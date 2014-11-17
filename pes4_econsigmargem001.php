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
  require_once("libs/db_utils.php");
  require_once("libs/db_app.utils.php");
  require_once("libs/db_conecta.php");
  require_once("libs/db_sessoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js, strings.js, prototype.js, estilos.css");
    ?>
    <script type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script type="text/javascript" src="scripts/json2.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  </head>
  <body style="background-color: #ccc; margin-top: 30px">

    <div class="container">

      <form action="" method="POST">

        <fieldset>
          <legend>Arquivo E-Consig - Margem</legend>

          <table class="form-container">
            <tr>
              <td id="labelCompetencia"></td>
              <td id="competencia"></td>
            </tr>
          </table>

        </fieldset>

        <input type="button" id="gerar" value="Gerar" />

      </form>
    </div>

  <script type="text/javascript">

    (function() {

      var oCompetencia = new DBViewFormularioFolha.CompetenciaFolha(true);

      oCompetencia.renderizaLabel($("labelCompetencia"));
      oCompetencia.renderizaFormulario($("competencia"));

      var sMsg = 'Gerando arquivo de Margem, aguarde...';

      var sUrlRpc = "pes4_geracaoarquivoeconsig.RPC.php";

      $("gerar").observe("click", function() {

        js_divCarregando(sMsg, 'msgbox');

        var oParametros = {
          iAnoUsu: oCompetencia.oAno.sValue,
          iMesUsu: oCompetencia.oMes.sValue,
          sExecucao: "gerarArquivoMargem"
        }

        var oDadosRequisicao = {
          method: "POST",
          asynchronous: false,
          parameters: 'json='+Object.toJSON(oParametros),
          onComplete: function(oAjax) {

            var oRetorno = JSON.parse(oAjax.responseText);

            js_removeObj('msgbox');

            if (oRetorno.iStatus == "2") {
              alert(oRetorno.sMensagem.urlDecode());
              return false;
            }

            /**
             * Remove DBDownload caso ja exista.
             */
            if( $('window01') ){
              $('window01').outerHTML = '';
            }

            var oDownload = new DBDownload();
            oDownload.addGroups("txt", "Arquivos");
            oDownload.addFile(oRetorno.sArquivoEconsig.urlDecode(), oRetorno.sNomeArquivo.urlDecode(), "txt");
            oDownload.show();
          }
        }

        new Ajax.Request(sUrlRpc, oDadosRequisicao)

        return false;
      })

    })();

  </script>

  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
</html>