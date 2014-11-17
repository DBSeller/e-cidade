<?php
 require_once("libs/db_stdlib.php");
 require_once("libs/db_utils.php");
 require_once("libs/db_app.utils.php");
 require_once("libs/db_conecta.php");
 require_once("libs/db_sessoes.php");
 require_once("libs/db_usuariosonline.php");
 require_once("classes/db_rhfolhapagamento_classe.php");
 require_once("dbforms/db_funcoes.php");
 require_once("model/pessoal/folhapagamento/FolhaPagamento.model.php");
 require_once("model/pessoal/folhapagamento/FolhaPagamentoSalario.model.php");
 define("MENSAGEM", 'recursoshumanos.pessoal.pes4_fechamentosalario001.');
 db_postmemory($HTTP_POST_VARS);
 
 $lManutenaoSalario = true;
 
 $oPost            = db_utils::postMemory($_POST);
 $rh141_sequencial = '';
 $db_opcao         = 3;
 $botaoProcessar   = '<input name="processar" type="button" value="Processar" disabled>';
 try {

   db_inicio_transacao();

   if (!FolhaPagamentoSalario::hasFolhaAberta( new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()) ) && !isset($_GET['fechado'])) {
     db_msgbox(_M(MENSAGEM . 'folha_aberta_inexistente'));
   }


   if (FolhaPagamentoComplementar::hasFolhaAberta( new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()) ) && !isset($_GET['fechado'])) {
     throw new BusinessException(_M(MENSAGEM . 'folha_complementar_aberta')) ;
   }


   if (isset($oPost->rh141_sequencial)) {

     $oFolhaSalario = new FolhaPagamentoSalario($oPost->rh141_sequencial);
     $oFolhaSalario->setDescricao($oPost->rh141_descricao);
     $oFolhaSalario->setCompetenciaReferencia(new DBCompetencia($oPost->rh141_anoref, $oPost->rh141_mesref));
     $oFolhaSalario->setCompetenciaFolha(new DBCompetencia( DBPessoal::getAnofolha(), DBPessoal::getMesFolha()));
     $oFolhaSalario->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo( db_getsession("DB_instit"))); 

     if ($oFolhaSalario->fechar()) {

       db_msgbox(_M(MENSAGEM . 'fechado_com_sucesso'));
       db_fim_transacao();
       db_redireciona("pes4_fechamentosalario001.php?fechado=true");
       exit;
     }
   }

   //Caso tenha folha em aberto, preenche campos.
   if (FolhaPagamentoSalario::hasFolhaAberta( new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha()) )) {

     $oFolhaPagamento  = FolhaPagamentoSalario::getFolhaAberta();
     $rh141_sequencial = $oFolhaPagamento->getSequencial();
     $rh141_codigo     = $oFolhaPagamento->getNumero();
     $rh141_descricao  = $oFolhaPagamento->getDescricao();
     $rh141_anoref     = $oFolhaPagamento->getCompetenciaReferencia()->getAno();
     $rh141_mesref     = $oFolhaPagamento->getCompetenciaReferencia()->getMes();
     $botaoProcessar   = '<input name="processar" type="button" value="Processar" id="processar">';
   }
 } catch (Exception $eException) {

   db_fim_transacao(true);
   db_msgbox($eException->getMessage());
 }
 
?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <form name="form1" method="post" class="container" action="">
      <fieldset>
        <legend align="left">Fechamento da Folha Salário</legend>
       <?php include("forms/db_frmrhfolhapagamento.php");?>
      </fieldset>
      <?php
      echo $botaoProcessar;
      db_input('rh141_sequencial', 4, $rh141_sequencial, true, 'hidden', 3);
      ?>
    </form>
    <?php db_menu(); ?>
    <script>
      $('processar').focus();
      
      $('processar').addEventListener("click", function() {
        js_calcularfixo();
      });

      function js_calcularfixo() {

        var ojanela = js_OpenJanelaIframe(
          "",
          "db_calculo",
          "pes4_gerafolha002.php?opcao_gml=g&opcao_geral=10&sCallBack=js_callbackcalculosalario()",
          "Cálculo Financeiro",
          true
          );

        if ( ojanela ) {
          ojanela.setAltura("70%");
          ojanela.setLargura("calc(100% - 10px)");
        }
      };

      function js_callbackcalculosalario() {

        db_calculo.hide();

        var ojanelaSalario = js_OpenJanelaIframe(
          "",
          "db_calculo",
          "pes4_gerafolha002.php?opcao_gml=g&opcao_geral=1&sCallBack=js_callbackcalculo()",
          "Cálculo Financeiro",
          true
          );

        if ( ojanelaSalario ) {

          ojanelaSalario.setAltura("70%");
          ojanelaSalario.setLargura("calc(100% - 10px)");
        }
      }

      function js_callbackcalculo() {
        db_calculo.hide();
        document.form1.submit();
      }
    </script>
  </body>
</html>
