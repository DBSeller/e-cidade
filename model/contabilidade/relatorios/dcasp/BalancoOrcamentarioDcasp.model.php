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


final class BalancoOrcamentarioDcasp extends RelatoriosLegaisBase {

  public function getDados() {

    $sWhereReceita = " o70_instit in ({$this->getInstituicoes()}) ";
    $sWhereDespesa = " o58_instit in ({$this->getInstituicoes()}) ";

    $rsBalanceteReceita = db_receitasaldo(11, 1, 3, true,
                                          $sWhereReceita,
                                          $this->iAnoUsu,
                                          $this->getDataInicial()->getDate(),
                                          $this->getDataFinal()->getDate()
                                         );

    db_query("drop table work_receita");

    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
                                          $this->iAnoUsu,
                                          $this->getDataInicial()->getDate(),
                                          $this->getDataFinal()->getDate());
    $aLinhas = $this->getLinhasRelatorio();

    foreach ($aLinhas as $iLinha => $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      $oLinha->oLinhaRelatorio->setPeriodo($this->iCodigoPeriodo);
      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                           $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $oValor) {

        foreach ($oValor->colunas as $oColuna) {
           $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }

      if ($iLinha <= 75) {

        $oSaldoInicial          = new stdClass();
        $oSaldoInicial->nome    = 'previni';
        $oSaldoInicial->formula = '#saldo_inicial';

        $oPrevisaoAtualizada          = new stdClass();
        $oPrevisaoAtualizada->nome    = 'prevatu';
        $oPrevisaoAtualizada->formula = '#saldo_inicial_prevadic';

        $oReceitaRealizada          = new stdClass();
        $oReceitaRealizada->nome    = 'recrealiza';
        $oReceitaRealizada->formula = '#saldo_arrecadado_acumulado';

        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteReceita,
                                                   $oLinha,
                                                   array($oSaldoInicial, $oPrevisaoAtualizada, $oReceitaRealizada),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
                                                 );

      }

      if ($iLinha > 75) {

        $oDotacaoInicial          = new stdClass();
        $oDotacaoInicial->nome    = 'dotini';
        $oDotacaoInicial->formula = '#dot_ini';

        $oDotacaoAtualizada       = new stdClass();
        $oDotacaoAtualizada->nome    = 'dotatu';
        $oDotacaoAtualizada->formula = '#dot_ini + #suplementado_acumulado - #reduzido_acumulado';

        $oDotacaoEmpenhada          = new stdClass();
        $oDotacaoEmpenhada->nome    = 'despemp';
        $oDotacaoEmpenhada->formula = '#empenhado_acumulado - #anulado_acumulado';

        $oDotacaoLiquidada          = new stdClass();
        $oDotacaoLiquidada->nome    = 'despliq';
        $oDotacaoLiquidada->formula = '#liquidado_acumulado';

        $oDotacaoPaga          = new stdClass();
        $oDotacaoPaga->nome    = 'desppag';
        $oDotacaoPaga->formula = '#pago_acumulado';

        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
                                                   $oLinha,
                                                   array($oDotacaoInicial, $oDotacaoAtualizada,
                                                         $oDotacaoEmpenhada, $oDotacaoLiquidada, $oDotacaoPaga),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
                                                  );

        $oLinha->saldo = 0;
      }
    }

    $this->processaTotalizadores($aLinhas);
    /**
     * Somamos a coluna Saldo do relatorio
     */
    foreach ($aLinhas as $iLinha => $oLinha) {

      if ($iLinha < 76) {
         $oLinha->saldo = ($oLinha->recrealiza - $oLinha->prevatu);
      }
      if ($iLinha > 75) {
        $oLinha->saldo = ($oLinha->dotatu - $oLinha->despemp);
      }
    }

    $aLinhas[96]->despliq = $aLinhas[94]->despliq;
    $aLinhas[96]->desppag = $aLinhas[94]->desppag;
    $aLinhas[96]->saldo = $aLinhas[94]->saldo;

    return $aLinhas;
  }
}