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

class BalancoOrcamentarioRPPS extends RelatoriosLegaisBase {

  /**
   * Instancia o Balanco Financeiro do RPPS
   * Apenas instituicoes que sao do tipo RPPS sao processadas neste relatorio;.
   * @param int $iAnoUsu Ano de Emissão
   * @param int $iCodigoRelatorio Código do Relatorio
   * @param int $iCodigoPeriodo Codigo do Periodo
   */
  public function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {

    parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);

    $oDaoDBConfig  = new cl_db_config();
    $sSqlInstituicoesRPPS = $oDaoDBConfig->sql_query_tipoinstit(null, "codigo", null, "db21_tipoinstit in (5,6)");
    $rsInstituicoesRPPS   = $oDaoDBConfig->sql_record($sSqlInstituicoesRPPS);
    if (!$rsInstituicoesRPPS || $oDaoDBConfig->numrows == 0) {
      throw new BusinessException('Não existem instituições RPPS cadastradas');
    }

    $aInstituicoesRetorno = db_utils::getCollectionByRecord($rsInstituicoesRPPS);
    $aInstituicoes        = array();
    foreach ($aInstituicoesRetorno as $oInstituicao) {
      $aInstituicoes[] = $oInstituicao->codigo;
    }
    $this->setInstituicoes(implode(",", $aInstituicoes));
    unset($aInstituicoes);
  }

  /**
   * Retorna as linhas processadas do relatorio
   * @return array lista de linhas processadas do relatorio
   */
  public function getDados() {

    $sWhereReceita = " o70_instit in ({$this->getInstituicoes()}) ";
    $sWhereDespesa = " o58_instit in ({$this->getInstituicoes()}) ";

    $aLinhasComReceita = array(2, 3, 4, 6, 7, 8, 10, 11, 12, 14, 15, 16);
    $aLinhasComDespesa = array(23, 24, 26, 27, 28, 31,33, 34, 38, 39, 41, 42, 43, 46, 48, 49);
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

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                           $this->iAnoUsu
                                                                          );
      foreach($aValoresColunasLinhas as $oValores) {
        foreach ($oValores->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }

      if (in_array($iLinha, $aLinhasComReceita)) {

        $oPrevisao          = new stdClass();
        $oPrevisao->nome    = 'previsao';
        $oPrevisao->formula = '#saldo_inicial';

        $oExecucao          = new stdClass();
        $oExecucao->nome    = 'execucao';
        $oExecucao->formula = '#saldo_arrecadado_acumulado';

        $oDiferenca          = new stdClass();
        $oDiferenca->nome    = 'diferenca';
        $oDiferenca->formula = "($oPrevisao->formula) - ($oExecucao->formula)";
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteReceita,
                                                   $oLinha,
                                                   array($oPrevisao, $oExecucao, $oDiferenca),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
                                                  );

      }

      if (in_array($iLinha, $aLinhasComDespesa)) {


        $sRegra = "#dot_ini > 0";
        if ($iLinha >= 38) {
          $sRegra = "#dot_ini == 0";
        }
        $oFixacao          = new stdClass();
        $oFixacao->nome    = 'fixacao';
        $oFixacao->formula = "({$sRegra} ? #dot_ini + #suplementado_acumulado - #reduzido_acumulado : 0)";
        $oExecucao          = new stdClass();
        $oExecucao->nome    = 'execucao';
        $oExecucao->formula = "({$sRegra} ? #liquidado_acumulado : 0)";

        $oDiferenca          = new stdClass();
        $oDiferenca->nome    = 'diferenca';
        $oDiferenca->formula = "($oFixacao->formula) - ($oExecucao->formula)";
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
                                                   $oLinha,
                                                   array($oFixacao, $oExecucao, $oDiferenca),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
                                                  );

      }
    }

    $this->processaTotalizadores($aLinhas);
    return $aLinhas;
  }
}