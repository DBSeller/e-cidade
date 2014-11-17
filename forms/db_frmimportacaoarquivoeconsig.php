<?
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
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewFormularioFolha/CompetenciaFolha.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="" enctype="multipart/form-data">
        <fieldset>
          <legend>Importação Arquivo de Movimento</legend>
          <table>
            <tr>
              <td nowrap title="Ano / Mês">
                <label class="bold">
                  Ano / Mês:
                </label>
              </td>
              <td id="formularioCompetencia"></td>
            </tr>
            <tr>
              <td nowrap title="Arquivo" >
                <label class="bold" for="ano_mes" id="lbl_ano_mes">
                  Arquivo:
                </label>
              </td>
              <td>
                <?php db_input('aArquivoMovimento', 100, '', true, 'file', 1, ''); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="incluir" type="submit" id="db_opcao" value="Processar" onclick="return js_validaCampo();">
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"), 
                   db_getsession("DB_modulo"), 
                   db_getsession("DB_anousu"), 
                   db_getsession("DB_instit") ); ?>
  </body>
  <script>

    function js_validaCampo() {
      if ($('aArquivoMovimento').value == '') {
        alert( _M("recursoshumanos.pessoal.pes4_importacaoarquivoeconsig.arquivo_invalido") );
        return false;
      }

      return true;
    }

    function js_exibeInconsistencias(aArquivos) {
      var oDownload = new DBDownload();
  
      if( $('window01') ){
        $('window01').outerHTML = '';
      }
    
      if ( !aArquivos.length ) {
        return;
      }
      
      for (var i = 0; i < aArquivos.length; i++) {

        if( aArquivos[i] != ''){

          var sNomeArquivo = aArquivos[i].split('/')[1];
          oDownload.addFile( aArquivos[i], sNomeArquivo);
        }
      }
      
      oDownload.show();
    }

    (function() {

      var oCompetenciaFolha = new DBViewFormularioFolha.CompetenciaFolha(true);
    
      oCompetenciaFolha.renderizaFormulario($('formularioCompetencia'));
      oCompetenciaFolha.desabilitarFormulario();
    })()

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>