<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2020;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\PadBuilder;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2020\Decreto;

/**
 * Class Decreto
 */
class DecretoBuilder2020 extends PadBuilder
{
    /**
     * @var Decreto
     */
    protected $layout;

    protected function create()
    {
        $this->layout = new Decreto();
    }

    public function build()
    {
        $this->create();
        $this->processar();
        return $this->layout;
    }

    protected function processar()
    {
        $tipoCredito = $this->identificaTipoCredito($this->dados['tipo_suplementacao']);
        $origem = $this->identificaOrigemRecursos($this->dados['tipo_suplementacao'], $this->dados['entre_entidades']);

        $this->layout->setNumeroLei($this->formataCaractere($this->dados['numero_lei'], 20));
        $this->layout->setDataLei($this->formataData($this->dados['data_lei']));
        $this->layout->setNumeroDecreto($this->formataCaractere($this->dados['numero_decreto'], 20));
        $this->layout->setDataDecreto($this->formataData($this->dados['data_decreto']));
        $this->layout->setValorCreditoAdicional($this->formataValor($this->dados['valor_credito'], 13));
        $this->layout->setValorReducaoDotacoes($this->formataValor($this->dados['valor_reducao'], 13));
        $this->layout->setTipoCreditoAdicional($tipoCredito);
        $this->layout->setOrigemRecurso($origem);
    }

    protected function identificaTipoCredito($tipoSuplementacao)
    {
        switch ($tipoSuplementacao) {
            case 1001:
            case 1002:
            case 1003:
            case 1004:
            case 1005:
            case 1014:
            case 1015:
            case 1016:
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
                return  3;
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
        }
    }
}
