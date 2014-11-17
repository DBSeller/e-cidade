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

require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");
require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_libpessoal.php");
require_once("std/db_stdClass.php");
require_once("libs/db_libpostgres.php");

db_app::import("pessoal.arquivos.dirf.Dirf");
db_app::import("pessoal.arquivos.dirf.Dirf2012");

$oJson    = new services_json();
$oParam   = $oJson->decode((str_replace("\\","",$_POST["json"])));
$oRetorno = new stdClass();
$oRetorno->status           = 1;
$oRetorno->message          = 1;
$oRetorno->itens            = array();
$oRetorno->aListaMatriculas = array();

switch($oParam->exec) {

  case "processarDirf":

    $subpes = $oParam->iAno.'/'.db_mesfolha();
    $subini = $oParam->iAno."/01";

    try {

      db_inicio_transacao();

      $oDirf = new Dirf2012($oParam->iAno, $oParam->sCnpj);

      $oDirf->setDesdobramentos($oParam->aDesdobramentos);
      $oDirf->processar($oParam->lProcessaEmpenho);
      $oRetorno->aArquivosInconsistentes = array();

      if ($oDirf->hasInconsistencias()) {

        $aArquivosInconsistentes = $oDirf->geraArquivoInconsistencias();
        foreach ($aArquivosInconsistentes as $sArquivoInconsistente) {

          $oRetorno->aArquivosInconsistentes[] = urlencode($sArquivoInconsistente);
        }
      }

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }

  break;

  case 'gerarDirf':

    $oParam->sNomeResponsavel = db_stdClass::db_stripTagsJson($oParam->sNomeResponsavel);

    $iValor = db_formatar((int) $oParam->iValor,'p');

    $oDirf = new Dirf2012($oParam->iAno, $oParam->sCnpj);
    $oDirf->setValorLimite($iValor);
    $oDirf->setCodigoArquivo($oParam->sCodigoArquivo);

    $oDirf->setMatriculas($oParam->aMatriculaSelecionadas);
    $oRetorno->arquivo = $oDirf->gerarArquivo($oParam, $oParam->lProcessaEmpenho);

  break;

  case "getUnidadesCnpjInvalido":

    $oRetorno->unidades = Dirf::retornarUnidadesCnpjInvalido();

  break;

  case "getMatriculasDirf":

    $iValor = db_formatar((int) $oParam->iValor,'p');
    
    $oDirf = new Dirf($oParam->iAno, $oParam->sCnpj);
    $oDirf->setValorLimite($iValor);

    $oRetorno->aListaMatriculas = $oDirf->retornaMatriculasDirf($oParam->lProcessaEmpenho, $oParam->sAcima);

  break;

  case "verificaProcessamento":
    $oRetorno->lProcessado = false;

    if (!empty($oParam->iAno) && !empty($oParam->sFontePagadora)) {

      $oDaoRhDirfGeracao = db_utils::getDao("rhdirfgeracao");

      $sSql = $oDaoRhDirfGeracao->sql_query_file( null, 
                                                  "*", 
                                                  null,
                                                  " rh95_ano = {$oParam->iAno} and rh95_fontepagadora = '{$oParam->sFontePagadora}'" );
      $oDaoRhDirfGeracao->sql_record($sSql);

      if ($oDaoRhDirfGeracao->numrows > 0) {
        $oRetorno->lProcessado = true;
      }
    }

  break;

}

echo $oJson->encode($oRetorno);