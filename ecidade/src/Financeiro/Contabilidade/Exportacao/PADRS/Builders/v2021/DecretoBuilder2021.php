<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2021;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2020\DecretoBuilder2020;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2021\Decreto;

class DecretoBuilder2021 extends DecretoBuilder2020
{
    protected function create()
    {
        $this->layout = new Decreto();
    }

    protected function processar()
    {
        $alteracaoOrcamentaria = $this->identificaAlteracaoOrcamentaria($this->dados['tipo_suplementacao']);

        parent::processar();

        $dataReabertura = $this->dados['data_reabertura_credito_adicional'] ?: '00000000';

        $this->layout->setAlteracoesOrcamentarias($alteracaoOrcamentaria);
        $this->layout->setDataReaberturaCreditoAdicional($this->formataData($dataReabertura));
        $this->layout->setValorAlteracoeOrcamentarias(
            $this->formataValor($this->dados['valor_alteracao_orcamentaria'], 13)
        );
        $this->layout->setValorSaldoReaberto($this->formataValor($this->dados['valor_saldo_reaberto'], 13));

        /*
         ALTERACAO DE REGRA
          se tipo for 0 então o valor  ValorAlteracoeOrcamentarias  passa a ser setValorCreditoAdicional
          e o valor setValorCreditoAdicional passa a ser zero
          $this->formataValor($this->dados['valor_credito']
        */

        $tipoCredito = $this->identificaTipoCredito($this->dados['tipo_suplementacao']);
        if ($tipoCredito == 0) {
            $this->layout->setValorCreditoAdicional($this->formataValor(0, 13));
            $this->layout->setValorAlteracoeOrcamentarias(
                $this->formataValor($this->dados['valor_credito'], 13)
            );
        }
    }

    protected function identificaTipoCredito($tipoSuplementacao)
    {
        switch ($tipoSuplementacao) {
            case 1014:
            case 1015:
            case 1016:
                return 0;

            case 1001:
            case 1002:
            case 1003:
            case 1004:
            case 1005:
                return 1;

            case 1006:
            case 1007:
            case 1008:
            case 1009:
            case 1010:
            case 1012:
            case 1013:
                return 2;
            case 1011:
            case 1017:
            case 1018:
            case 1019:
            case 1020:
            case 1050:
                return 3;
        }
    }

    protected function identificaOrigemRecursos($tipoSuplementacao, $entreEntidades)
    {
        if ($entreEntidades == 't') {
            return 6;
        }

        switch ($tipoSuplementacao) {
            case 1003:
            case 1008:
            case 1012:
            case 1019:
                return 1;
            case 1004:
            case 1009:
            case 1011:
            case 1018:
                return 2;
            case 1002:
            case 1007:
            case 1013:
            case 1020:
                return 3;
            case 1005:
            case 1010:
                return 4;
            case 1001:
            case 1006:
            case 1014:
            case 1015:
            case 1016:
            case 1017:
                return 5;
            case 1050:
                return 0;
        }
    }

    protected function identificaAlteracaoOrcamentaria($tipoSuplementacao)
    {
        switch ($tipoSuplementacao) {
            case 1014:
                return 1;
            case 1016:
                return 2;
            case 1015:
                return 3;
            default:
                return 0;
        }
    }
}
