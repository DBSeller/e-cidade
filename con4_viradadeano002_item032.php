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

require_once("classes/db_avaliacaoestruturanota_classe.php");
require_once("classes/db_avaliacaoestruturaregra_classe.php");

if ($sqlerro == false) {

  db_atutermometro(0, 2, 'termometroitem', 1, $sMensagemTermometroItem);
  
  /**
   * Buscamos os dados da tabela avaliacaoestruturanota a serem importados
   */
  $oDaoAvaliacaoEstruturaNota   = db_utils::getDao("avaliacaoestruturanota");
  $sWhereAvaliacaoEstruturaNota = "ed315_ano = {$iAnoOrigem}";
  $sSqlAvaliacaoEstruturaNota   = $oDaoAvaliacaoEstruturaNota->sql_query_file(
                                                                              null,
                                                                              "*",
                                                                              null,
                                                                              $sWhereAvaliacaoEstruturaNota
                                                                             );
  $rsAvaliacaoEstruturaNota      = $oDaoAvaliacaoEstruturaNota->sql_record($sSqlAvaliacaoEstruturaNota);
  $iLinhasAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNota->numrows;
  
  if ($iLinhasAvaliacaoEstruturaNota > 0) {
    
    for ($iContadorNota = 0; $iContadorNota < $iLinhasAvaliacaoEstruturaNota; $iContadorNota++) {
      
      $oDadosEstruturaNota                = db_utils::fieldsMemory($rsAvaliacaoEstruturaNota, $iContadorNota);
      $oDaoAvaliacaoEstruturaNotaMigracao = db_utils::getDao("avaliacaoestruturanota");
      
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_escola         = $oDadosEstruturaNota->ed315_escola;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_db_estrutura   = $oDadosEstruturaNota->ed315_db_estrutura;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_ativo          = $oDadosEstruturaNota->ed315_ativo ? 'true':'false';
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_arredondamedia = $oDadosEstruturaNota->ed315_arredondamedia ? 'true':'false';
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_observacao     = $oDadosEstruturaNota->ed315_observacao;
      $oDaoAvaliacaoEstruturaNotaMigracao->ed315_ano            = $iAnoDestino;
      $oDaoAvaliacaoEstruturaNotaMigracao->incluir(null);
      $iCodigoAvaliacaoEstruturaNota = $oDaoAvaliacaoEstruturaNotaMigracao->ed315_sequencial;
      
      if ($oDaoAvaliacaoEstruturaNotaMigracao->erro_status == "0") {
        
        $sqlerro   = true;
        $erro_msg .= $oDaoAvaliacaoEstruturaNotaMigracao->erro_msg;
        
      }
      
      /**
       * Buscamos os dados da tabela avaliacaoestruturaregra, que tenham alguma configuracao de nota migrada, vinculada
       */
      $oDaoAvaliacaoEstruturaRegra   = db_utils::getDao("avaliacaoestruturaregra");
      $sWhereAvaliacaoEstruturaRegra = "ed318_avaliacaoestruturanota = {$oDadosEstruturaNota->ed315_sequencial}";
      $sSqlAvaliacaoEstruturaRegra   = $oDaoAvaliacaoEstruturaRegra->sql_query_file(
                                                                                    null,
                                                                                    "*",
                                                                                    null,
                                                                                    $sWhereAvaliacaoEstruturaRegra
                                                                                   );
      $rsAvaliacaoEstruturaRegra      = $oDaoAvaliacaoEstruturaRegra->sql_record($sSqlAvaliacaoEstruturaRegra);
      $iLinhasAvaliacaoEstruturaRegra = $oDaoAvaliacaoEstruturaRegra->numrows;
      
      if ($iLinhasAvaliacaoEstruturaRegra > 0) {
        
        for ($iContadorRegra = 0; $iContadorRegra < $iLinhasAvaliacaoEstruturaRegra; $iContadorRegra++) {
          
          $oDadosEstruturaRegra                = db_utils::fieldsMemory($rsAvaliacaoEstruturaRegra, $iContadorRegra);
          $oDaoAvaliacaoEstruturaRegraMigracao = db_utils::getDao("avaliacaoestruturaregra");
          
          $oDaoAvaliacaoEstruturaRegraMigracao->ed318_avaliacaoestruturanota = $iCodigoAvaliacaoEstruturaNota;
          $oDaoAvaliacaoEstruturaRegraMigracao->ed318_regraarredondamento    = $oDadosEstruturaRegra->ed318_regraarredondamento;
          $oDaoAvaliacaoEstruturaRegraMigracao->incluir(null);
          
          if ($oDaoAvaliacaoEstruturaRegraMigracao->erro_status == "0") {
            
            $sqlerro   = true;
            $erro_msg .= $oDaoAvaliacaoEstruturaRegraMigracao->erro_msg;
          }
          
          unset($oDadosEstruturaRegra);
          unset($oDaoAvaliacaoEstruturaRegraMigracao);
        }
      } else {
    
        if ($iLinhasAvaliacaoEstruturaRegra == 0) {
          
          $cldb_viradaitemlog->c35_log  = "Não existe regra de arredondamento vinculada a configuração da nota para";
          $cldb_viradaitemlog->c35_log .= " migração para o ano de destino $iAnoDestino";
        }
        
        $cldb_viradaitemlog->c35_codarq        = 893;
        $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
        $cldb_viradaitemlog->c35_data          = date("Y-m-d");
        $cldb_viradaitemlog->c35_hora          = date("H:i");
        $cldb_viradaitemlog->incluir(null);
        
        if ($cldb_viradaitemlog->erro_status == 0) {
          
          $sqlerro   = true;
          $erro_msg .= $cldb_viradaitemlog->erro_msg;
        }
      }
      
      unset($oDadosEstruturaNota);
      unset($oDaoAvaliacaoEstruturaNota);
    }
  } else {
    
    if ($iLinhasAvaliacaoEstruturaRegra == 0) {
      $cldb_viradaitemlog->c35_log = "Não existem configurações de nota para migração para o ano de destino $iAnoDestino";
    }
    
    $cldb_viradaitemlog->c35_codarq        = 893;
    $cldb_viradaitemlog->c35_db_viradaitem = $cldb_viradaitem->c31_sequencial;
    $cldb_viradaitemlog->c35_data          = date("Y-m-d");
    $cldb_viradaitemlog->c35_hora          = date("H:i");
    $cldb_viradaitemlog->incluir(null);
    
    if ($cldb_viradaitemlog->erro_status == 0) {
      
      $sqlerro   = true;
      $erro_msg .= $cldb_viradaitemlog->erro_msg;
    }
  }
  
  db_atutermometro(1, 2, 'termometroitem', 1, $sMensagemTermometroItem);
}
?>