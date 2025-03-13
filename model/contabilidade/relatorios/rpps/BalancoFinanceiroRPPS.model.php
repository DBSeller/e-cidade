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
class BalancoFinanceiroRPPS extends RelatoriosLegaisBase {

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

    $oDaoRestosAPagar = new cl_empresto();


    $aLinhasUtilizamBalanceteReceita     = array(2, 3, 4, 5, 6);
    $aLinhasUtilizamBalanceteDespesa     = array(14, 15);
    $aLinhasUtilizamBalanceteVerificacao = array(8, 9, 17, 18, 11, 20);

    $sWhereRestoPagar = " e60_instit in({$this->getInstituicoes()})";
    $sWhereReceita    = " o70_instit in({$this->getInstituicoes()})";
    $sWhereDespesa    = " o58_instit in({$this->getInstituicoes()})";
    $sWherePlano      = " c61_instit in({$this->getInstituicoes()})";

    $sSqlRestosaPagar = $oDaoRestosAPagar->sql_rp_novo($this->iAnoUsu,
                                                       $sWhereRestoPagar,
                                                       $this->getDataInicial()->getDate(),
                                                       $this->getDataFinal()->getDate()
                                                      );
    $rsRestosPagar   = db_query($sSqlRestosaPagar);



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

    /**
     * Esse valor, ira recever o valor total da Despesa,
     * O Valor dessa linha, também recebera o valor de contas especificas do balancete de receita.
     */
    $nValorExtraOrcamentarioIngresso = 0;

    $iTotalLinhasDespesa = pg_num_rows($rsBalanceteDespesa);
    for ($iDespesa = 0; $iDespesa < $iTotalLinhasDespesa; $iDespesa++) {

      $oDespesa = db_Utils::fieldsMemory($rsBalanceteDespesa, $iDespesa);
      $nValorExtraOrcamentarioIngresso += $oDespesa->liquidado_acumulado - $oDespesa->pago_acumulado;
      unset($oDespesa);
    }

    /**
     * nessa Variavel somamos os valores dos dispendios. Inicialmente ela recebe
     * todos  valor pago e anuladoss de Restos a pagar. (Restos anulados também contam como baixas)
     * Esse valor será somados na linha 'Dispendios'
     */
    $nValorExtraOrcamentarioDispendio = 0;
    $iTotalLinhasResto                = pg_num_rows($rsRestosPagar);
    for ($iResto = 0; $iResto < $iTotalLinhasResto; $iResto++) {

      $oDespesaRp                        = db_utils::fieldsMemory($rsRestosPagar, $iResto);
      $nValorExtraOrcamentarioDispendio += $oDespesaRp->vlrpag + $oDespesaRp->vlranu;

    }
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
      }

      if (in_array($iLinha, $aLinhasUtilizamBalanceteVerificacao)) {

        $oColuna          = new stdClass();
        $oColuna->nome    = 'vlrexatual';
        $oColuna->formula = '#saldo_final';
        switch ($iLinha) {

          case 9:

            $oColuna->formula = '#saldo_anterior_credito';
            break;

          case 11:

            $oColuna->formula = '#sinal_anterior == "C" ? #saldo_anterior * -1 : #saldo_anterior';
            break;

          case 17:

            $oColuna->formula = '#saldo_anterior_debito';
            break;

          case 18:

            $oColuna->formula = '#sinal_final == "C" ? #saldo_final * -1 : #saldo_final';
            break;

        }

        RelatoriosLegaisBase::calcularValorDaLinha($rsBalanceteVerificacao,
                                                   $oLinha,
                                                   array($oColuna),
                                                   RelatoriosLegaisBase::TIPO_CALCULO_VERIFICACAO
                                                  );
      }
    }

    $aLinhas[9]->vlrexatual  += $nValorExtraOrcamentarioIngresso;
    $aLinhas[18]->vlrexatual += $nValorExtraOrcamentarioDispendio;
    $this->processaTotalizadores($aLinhas);
    return $aLinhas;
  }
}