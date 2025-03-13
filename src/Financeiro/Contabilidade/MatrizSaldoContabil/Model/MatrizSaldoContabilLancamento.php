<?php

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model;

use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Registry\MatrizSaldoContabilRegistry;
use Exception;

class MatrizSaldoContabilLancamento
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var MatrizSaldoContabil
     */
    private $matrizSaldoContabil;
    /**
     * @var string
     */
    private $estrutural;
    /**
     * @var string
     */
    private $atributos;
    /**
     * @var float
     */
    private $beginningBalance;
    /**
     * @var float
     */
    private $periodChangeDebit;
    /**
     * @var float
     */
    private $periodChangeCredit;
    /**
     * @var float
     */
    private $endingBalance;
    /**
     * @var string
     */
    private $naturezaInicial;

    /**
     * @var string
     */
    private $naturezaFinal;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
    }

    /**
     * @return MatrizSaldoContabil
     */
    public function getMatrizSaldoContabil()
    {
        return $this->matrizSaldoContabil;
    }

    /**
     * @param MatrizSaldoContabil $matrizSaldoContabil
     */
    public function setMatrizSaldoContabil(MatrizSaldoContabil $matrizSaldoContabil)
    {
        $this->matrizSaldoContabil = $matrizSaldoContabil;
    }

    /**
     * @return string
     */
    public function getEstrutural()
    {
        return (string)$this->estrutural;
    }

    /**
     * @param string $estrutural
     */
    public function setEstrutural($estrutural)
    {
        $this->estrutural = (string)$estrutural;
    }

    /**
     * @return string
     */
    public function getAtributos()
    {
        return (string)$this->atributos;
    }

    /**
     * @param string $atributos
     */
    public function setAtributos($atributos)
    {
        $this->atributos = (string)$atributos;
    }

    /**
     * @return float
     */
    public function getBeginningBalance()
    {
        return (float)$this->beginningBalance;
    }

    /**
     * @param float $beginningBalance
     */
    public function setBeginningBalance($beginningBalance)
    {
        $this->beginningBalance = (float)$beginningBalance;
    }

    /**
     * @return float
     */
    public function getPeriodChangeDebit()
    {
        return (float)$this->periodChangeDebit;
    }

    /**
     * @param float $periodChangeDebit
     */
    public function setPeriodChangeDebit($periodChangeDebit)
    {
        $this->periodChangeDebit = (float)$periodChangeDebit;
    }

    /**
     * @return float
     */
    public function getPeriodChangeCredit()
    {
        return (float)$this->periodChangeCredit;
    }

    /**
     * @param float $periodChangeCredit
     */
    public function setPeriodChangeCredit($periodChangeCredit)
    {
        $this->periodChangeCredit = (float)$periodChangeCredit;
    }

    /**
     * @return float
     */
    public function getEndingBalance()
    {
        return (float)$this->endingBalance;
    }

    /**
     * @param float $endingBalance
     */
    public function setEndingBalance($endingBalance)
    {
        $this->endingBalance = (float)$endingBalance;
    }

    /**
     * @return string
     */
    public function getNaturezaInicial()
    {
        return (string)$this->naturezaInicial;
    }

    /**
     * @param string $naturezaInicial
     */
    public function setNaturezaInicial($naturezaInicial)
    {
        $this->naturezaInicial = (string)$naturezaInicial;
    }

    /**
     * @return string
     */
    public function getNaturezaFinal()
    {
        return $this->naturezaFinal;
    }

    /**
     * @param string $naturezaFinal
     */
    public function setNaturezaFinal($naturezaFinal)
    {
        $this->naturezaFinal = $naturezaFinal;
    }



    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'matrizSaldoContabil' => $this->getMatrizSaldoContabil()->toArray(),
            'estrutural' => $this->getEstrutural(),
            'atributos' => $this->getAtributos(),
            'beginningBalance' => $this->getBeginningBalance(),
            'periodChangeDebit' => $this->getPeriodChangeDebit(),
            'periodChangeCredit' => $this->getPeriodChangeCredit(),
            'endingBalance' => $this->getEndingBalance(),
            'naturezaInicial' => $this->getNaturezaInicial(),
            'naturezaFinal' => $this->getNaturezaFinal()
        );
    }

    /**
     * @param array $state
     * @return MatrizSaldoContabilLancamento
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('c133_sequencial', $state['c133_sequencial'])) {
            $self->setSequencial($state['c133_sequencial']);
        }

        if (array_key_exists('c133_matriz_saldo_contabil', $state['c133_matriz_saldo_contabil'])) {
            $self->setMatrizSaldoContabil(MatrizSaldoContabilRegistry::get($state['c133_matriz_saldo_contabil']));
        }

        if (array_key_exists('c133_estrutural', $state['c133_estrutural'])) {
            $self->setEstrutural($state['c133_estrutural']);
        }

        if (array_key_exists('c133_atributos', $state['c133_atributos'])) {
            $self->setAtributos($state['c133_atributos']);
        }

        if (array_key_exists('c133_beginning_balance', $state['c133_beginning_balance'])) {
            $self->setBeginningBalance($state['c133_beginning_balance']);
        }

        if (array_key_exists('c133_period_change_debit', $state['c133_period_change_debit'])) {
            $self->setPeriodChangeDebit($state['c133_period_change_debit']);
        }

        if (array_key_exists('c133_period_change_credit', $state['c133_period_change_credit'])) {
            $self->setPeriodChangeCredit($state['c133_period_change_credit']);
        }

        if (array_key_exists('c133_ending_balance', $state['c133_ending_balance'])) {
            $self->setEndingBalance($state['c133_ending_balance']);
        }

        if (array_key_exists('c133_natureza', $state['c133_natureza'])) {
            $self->setNaturezaInicial($state['c133_natureza']);
        }

        if (array_key_exists('c133_natureza_final', $state['c133_natureza_final'])) {
            $self->setNaturezaFinal($state['c133_natureza_final']);
        }

        return $self;
    }
}
