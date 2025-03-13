<?php

namespace ECidade\Tributario\Issqn\Model;

class ConfVencIssqnAvulso
{
    /**
     * Código da Receita
     * @var Integer
     */
    private $iReceita;

    /**
     * Código de Histórico de Débito
     * @var Integer
     */
    private $iHistDebito;

    /**
     * Código do Tipo de Débito
     * @var Integer
     */
    private $iTipoDebito;

    /**
     * Número de Dias de Vencimento
     * @var Integer
     */
    private $iDiaVenc;

    /** Ano Atual
     * @var Integer
     */
    private $iAnousu;

    /**
     * @return int
     */
    public function getIReceita()
    {
        return $this->iReceita;
    }

    /**
     * @param int $iReceita
     */
    public function setIReceita($iReceita)
    {
        $this->iReceita = $iReceita;
    }

    /**
     * @return int
     */
    public function getIHistDebito()
    {
        return $this->iHistDebito;
    }

    /**
     * @param int $iHistDebito
     */
    public function setIHistDebito($iHistDebito)
    {
        $this->iHistDebito = $iHistDebito;
    }

    /**
     * @return int
     */
    public function getITipoDebito()
    {
        return $this->iTipoDebito;
    }

    /**
     * @param int $iTipoDebito
     */
    public function setITipoDebito($iTipoDebito)
    {
        $this->iTipoDebito = $iTipoDebito;
    }

    /**
     * @return int
     */
    public function getIDiaVenc()
    {
        return $this->iDiaVenc;
    }

    /**
     * @param int $iDiaVenc
     */
    public function setIDiaVenc($iDiaVenc)
    {
        $this->iDiaVenc = $iDiaVenc;
    }

    /**
     * @return int
     */
    public function getIAnousu()
    {
        return $this->iAnousu;
    }

    /**
     * @param int $iAnousu
     */
    public function setIAnousu($iAnousu)
    {
        $this->iAnousu = $iAnousu;
    }
}
