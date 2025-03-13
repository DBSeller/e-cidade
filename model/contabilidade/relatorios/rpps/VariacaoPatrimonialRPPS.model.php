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
 * Classe para geração dos dados do balanco Financeiro do RPPS
 */
class VariacaoPatrimonialRPPS extends RelatoriosLegaisBase {

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
    unset($aInstituicoesRetorno);
  }

  /**
   * return array Linhas com os dados processados do relatorio
   */
  public function getDados() {

    $aLinhasUtilizamBalanceteReceita     = array(3, 4, 5, 6);
    $aLinhasUtilizamBalanceteDespesa     = array(26, 27, 28, 29);
    $aLinhasUtilizamBalanceteVerificacao = array();
    
    $sWhereReceita    = " o70_instit in({$this->getInstituicoes()})";
    $sWhereDespesa    = " o58_instit in({$this->getInstituicoes()})";
    $sWherePlano      = " c61_instit in({$this->getInstituicoes()})";

    $rsReceita = db_receitasaldo(11, 1, 3, true, $sWhereReceita, $this->iAnoUsu,
                                 $this->getDataInicial()->getDate(),
                                 $this->getDataFinal()->getDate()
                                );



    $rsBalanceteDespesa = db_dotacaosaldo(8,2,2, true, $sWhereDespesa,
                                                  $this->iAnoUsu,
                                                  $this->getDataInicial()->getDate(),
                                                  $this->getDataFinal()->getDate()
                                                );


    $rsBalanceteVerificacao =  db_planocontassaldo_matriz($this->iAnoUsu,
                                                          $this->getDataInicial()->getDate(),
                                                          $this->getDataFinal()->getDate(),
                                                          false,
                                                          $sWherePlano,
                                                          '',
                                                          'true',
                                                          'false'
                                                        );
    
    $aLinhas = $this->getLinhasRelatorio();
    foreach ($aLinhas as $iLinha => $oLinha) {

      if ($oLinha->totalizar) {
        continue;
      }

      $aValoresColunasLinhas = $oLinha->oLinhaRelatorio->getValoresColunas(null, null, $this->getInstituicoes(),
                                                                           $this->iAnoUsu);
      foreach($aValoresColunasLinhas as $oValores) {
        foreach ($oValores->colunas as $oColuna) {
          $oLinha->{$oColuna->o115_nomecoluna} += $oColuna->o117_valor;
        }
      }

      if (in_array($iLinha, $aLinhasUtilizamBalanceteReceita)) {

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '#saldo_arrecadado_acumulado';
        RelatoriosLegaisBase::calcularValorDaLinha($rsReceita,
                                                    $oLinha,
                                                    array($oColuna),
                                                    RelatoriosLegaisBase::TIPO_CALCULO_RECEITA
                                                  );
        continue;
      }

      if (in_array($iLinha, $aLinhasUtilizamBalanceteDespesa)) {

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '#liquidado_acumulado';
        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteDespesa,
                                                  $oLinha,
                                                  array($oColuna),
                                                  RelatoriosLegaisBase::TIPO_CALCULO_DESPESA
                                                );
        continue;
      }

      $oColuna          = new stdClass();
      $oColuna->nome    = 'vlrexatual';
      $oColuna->formula = '#saldo_final';

      RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacao,
                                                 $oLinha,
                                                 array($oColuna),
                                                 RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
                                                );
    }

    $this->processaTotalizadores($aLinhas);
    return $aLinhas;
  }
}