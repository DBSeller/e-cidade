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

/**
 * MODULO: fumam
 */

$clservicos->rotulo->label();
$clrotulo = new rotulocampo;

$clrotulo->label("fm08_codigo");
$clrotulo->label("fm08_prestador");
$clrotulo->label("fm08_servico");
$clrotulo->label("fm08_situacao");
$clrotulo->label("fm08_autoriza");
$clrotulo->label("fm06_codigo");
$clrotulo->label("fm12_descricao");
$clrotulo->label("fm02_descricao");

if (!isset($fm08_prestador_cgm)) {
  $fm08_prestador_cgm = '';
}

if (!isset($nome_prestador)) {
  $nome_prestador = '';
}

if (!isset($servico_nome)) {
  $servico_nome = '';
}

if (!isset($db_botao)) {
  $db_botao = '';
}

if ($db_opcao == 1) {
  $sNameBotaoProcessar = "incluir";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $sNameBotaoProcessar = "alterar";
} else {
  $sNameBotaoProcessar = "excluir";
}
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Serviços Prestados</legend>
          <table>
            <tr>
              <td nowrap title="<?php echo $Tfm08_codigo; ?>" >
                <label class="bold" for="fm08_codigo" id="lbl_fm08_codigo"><?php echo $Sfm08_codigo; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('fm08_codigo', 12, $Ifm08_codigo, true, 'text', 3,"");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm08_prestador; ?>" >
                <label class="bold" for="fm08_prestador" id="lbl_fm08_prestador"><?php echo $Sfm08_prestador; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('fm08_prestador', 12, $Ifm08_prestador, true, 'hidden', 3,"");
                  db_input('fm08_prestador_cgm', 12, $fm08_prestador_cgm, true, 'text', 3,"");
                  db_input('nome_prestador',50,$nome_prestador, true,'text',3,'');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm08_servico; ?>" >
                <?php db_ancora($Lfm08_servico,"js_pesquisafm08_servico(true);",$db_opcao); ?>
              </td>
              <td>
                <?php
                  db_input('fm08_servico', 12, $Ifm08_servico, true, 'text', $db_opcao," onchange='js_pesquisafm08_servico(false);'");
                  db_input('fm12_descricao', 50, $Ifm12_descricao, true, 'text', 3, ''); 
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm08_situacao; ?>" >
                <label class="bold" for="fm08_situacao" id="lbl_fm08_situacao"><?php echo $Sfm08_situacao; ?>:</label>
              </td>
              <td>
                <?php
                  $aSituacao = array("t" => "ATIVO", "f" => "INATIVO");
                  db_select('fm08_situacao', $aSituacao, true, ($db_opcao==3?3:1));
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tfm08_autoriza; ?>" >
                <label class="bold" for="fm08_autoriza" id="lbl_fm08_autoriza"><?php echo $Sfm08_autoriza; ?>:</label>
              </td>
              <td>
                <?php
                  $x = array("f" => "NAO", "t" => "SIM");
                  db_select('fm08_autoriza', $x, true, $db_opcao, "");
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao"
               value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <input id="cancelar" name="cancelar" type="button" value="Cancelar" onclick="return js_cancelar();">
        <table>
        <tr>
          <td valign="top"  align="center">  
            <?php
              if (isset($fm08_prestador)) {
                 $sCampos = "fm08_codigo, fm12_descricao, fm08_servico, ";
                 $sCampos .= " case when fm08_autoriza is true then 'Sim' else 'NÃO' end as fm08_autoriza, ";
                 $sCampos .= " case when fm08_situacao is true then 'ATIVO' else 'INATIVO' end as fm08_situacao ";
                 $chavepri= array("fm08_codigo"=>@$fm08_codigo);
                 $cliframe_alterar_excluir->iframe_nome = "frm_servicos";
                 $cliframe_alterar_excluir->chavepri      = $chavepri;
                 $cliframe_alterar_excluir->sql           = $clservicos->sql_query(null,$sCampos,null,"fm08_prestador = $fm08_prestador");
                 $cliframe_alterar_excluir->campos        = "fm08_codigo, fm08_servico, fm12_descricao, fm08_situacao, fm08_autoriza";
                 $cliframe_alterar_excluir->legenda       = "Registros";
                 $cliframe_alterar_excluir->tamfontecabec = 11;
                 $cliframe_alterar_excluir->tamfontecorpo = 10;
                 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
              }
            ?>
          </td>
        </tr>
      </table>        
      </form>
    </div>
    <?php db_menu(); ?>
  </body>
  <script>

    function js_cancelar() {

      document.getElementById('fm08_codigo').value = '';
      document.getElementById('fm08_servico').value = '';
      document.getElementById('fm12_descricao').value = '';
      frm_servicos.location.reload();
    }

    function js_pesquisafm08_prestador(lExibeJanela) {

      if (lExibeJanela) {
         js_OpenJanelaIframe( 'CurrentWindow.corpo',
                              'db_iframe_prestadores',
                              'func_prestadores.php?funcao_js=parent.js_mostraprestadores1|fm06_codigo|fm06_codigo',
                              'Pesquisa', true);
      } else {
         if (document.form1.fm08_prestador.value != '') {
            js_OpenJanelaIframe( 'CurrentWindow.corpo',
                                 'db_iframe_prestadores',
                                 'func_prestadores.php?pesquisa_chave=' + document.form1.fm08_prestador.value + '&funcao_js=parent.js_mostraprestadores',
                                 'Pesquisa', false);
         } else {
            document.form1.fm06_codigo.value = ''; 
         }
      }
    }

    function js_mostraprestadores(sChave, lErro) {

      document.form1.fm06_codigo.value = sChave;
      if (lErro) {
         document.form1.fm08_prestador.focus();
         document.form1.fm08_prestador.value = '';
      }
    }

    function js_mostraprestadores1(sChave, sDescricao) {

      document.form1.fm08_prestador.value = sChave;
      document.form1.fm06_codigo.value = sDescricao;
      db_iframe_prestadores.hide();
    }

    function js_pesquisafm08_servico(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( '', 
                             'db_iframe_associadoservicos', 
                             'func_associadoservicos.php?funcao_js=parent.js_mostraassociadoservicos1|fm12_codigo|fm12_descricao', 
                             'Pesquisa', true);
      } else {
        if (document.form1.fm08_servico.value != '') {
          js_OpenJanelaIframe( '', 
                               'db_iframe_associadoservicos', 
                               'func_associadoservicos.php?pesquisa_chave=' + document.form1.fm08_servico.value + '&funcao_js=parent.js_mostraassociadoservicos', 
                               'Pesquisa', false);
        } else {
          document.form1.fm12_descricao.value = ''; 
        }
      }
    }

    function js_mostraassociadoservicos(sChave, lErro) {

      document.form1.fm12_descricao.value = sChave;
      if (lErro) {
        document.form1.fm08_servico.focus();
        document.form1.fm08_servico.value = '';
      }
    }

    function js_mostraassociadoservicos1(sChave, sDescricao) {

      document.form1.fm08_servico.value = sChave;
      document.form1.fm12_descricao.value = sDescricao;
      db_iframe_associadoservicos.hide();
    }

    function js_pesquisafm08_situacao(lExibeJanela) {

      if (lExibeJanela) {
         js_OpenJanelaIframe( '', 
                              'db_iframe_associadosituacao',
                              'func_associadosituacao.php?funcao_js=parent.js_mostraassociadosituacao1|fm02_situacao|fm02_descricao',
                              'Pesquisa', true);
      } else {
         if (document.form1.fm08_situacao.value != '') {
            js_OpenJanelaIframe( '', 
                                 'db_iframe_associadosituacao',
                                 'func_associadosituacao.php?pesquisa_chave='+document.form1.fm08_situacao.value+'&funcao_js=parent.js_mostraassociadosituacao',
                                 'Pesquisa', false);
         } else {
            document.form1.fm02_descricao.value = '';
         }
      }
    }

    function js_mostraassociadosituacao(sChave, lErro) {

      document.form1.fm02_descricao.value = sChave;
      if (lErro) {
         document.form1.fm08_situacao.focus();
         document.form1.fm08_situacao.value = '';
      }
    }

    function js_mostraassociadosituacao1(sChave, sDescricao) {

      document.form1.fm08_situacao.value = sChave;
      document.form1.fm02_descricao.value = sDescricao;
      db_iframe_associadosituacao.hide();
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( 'CurrentWindow.corpo',
                           'db_iframe_servicosprestadores',
                           'func_servicosprestadores.php?funcao_js=parent.js_preenchepesquisa|fm08_codigo',
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_servicosprestadores.hide();
      <?php
        if ($db_opcao != 1) {
           echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>
