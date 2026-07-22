<?php
require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));

/**
 * Class LancamentoAuxiliarRetificacao
 */
class LancamentoAuxiliarRetificacao extends LancamentoAuxiliarBase implements ILancamentoAuxiliar
{

    /**
     * @var integer
     */
    private $codigoHistorico;

    /**
     * @var float
     */
    private $valorTotal;

    /**
     * @var integer
     */
    private $contaDebito;

    /**
     * @var integer
     */
    private $contaCredito;

    /**
     * @return int
     */
    public function getContaDebito()
    {
        return $this->contaDebito;
    }

    /**
     * @param int $contaDebito
     */
    public function setContaDebito($contaDebito)
    {
        $this->contaDebito = $contaDebito;
    }

    /**
     * @return int
     */
    public function getContaCredito()
    {
        return $this->contaCredito;
    }

    /**
     * @param int $contaCredito
     */
    public function setContaCredito($contaCredito)
    {
        $this->contaCredito = $contaCredito;
    }


    /**
     * @inheritDoc
     * @throws Exception
     */
    public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)
    {
        $this->iCodigoLancamento = $iCodigoLancamento;
        $this->dtLancamento = $dtLancamento;
        $this->salvarVinculoComplemento();
    }

    /**
     * @inheritDoc
     */
    public function setValorTotal($nValorTotal)
    {
        $this->valorTotal = $nValorTotal;
    }

    /**
     * @inheritDoc
     */
    public function getValorTotal()
    {
        return $this->valorTotal;
    }

    /**
     * @inheritDoc
     */
    public function getHistorico()
    {
        return $this->codigoHistorico;
    }

    /**
     * @inheritDoc
     */
    public function setHistorico($iHistorico)
    {
        $this->codigoHistorico = $iHistorico;
    }
}
