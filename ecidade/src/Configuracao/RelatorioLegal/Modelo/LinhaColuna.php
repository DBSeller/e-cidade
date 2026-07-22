<?php

namespace ECidade\Configuracao\RelatorioLegal\Modelo;

use ECidade\Configuracao\RelatorioLegal\Registry\ColunaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use Exception;

/**
 * Class LinhaColuna
 * @package ECidade\Configuracao\RelatorioLegal\Modelo
 */
class LinhaColuna
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var Linha
     */
    private $linha;
    /**
     * @var Relatorio
     */
    private $relatorio;
    /**
     * @var Coluna
     */
    private $coluna;
    /**
     * @var int
     */
    private $ordem;
    /**
     * @var int
     */
    private $periodo;
    /**
     * @var string
     */
    private $formula;

    /**
     * @param array $state
     * @return LinhaColuna
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('o116_sequencial', $state)) {
            $self->setSequencial($state['o116_sequencial']);
        }

        if (array_key_exists('o116_codparamrel', $state)) {
            $relatorio = RelatorioRegistry::get($state['o116_codparamrel']);

            $self->setRelatorio($relatorio);
            if (array_key_exists('o116_codseq', $state)) {
                $self->setLinha(LinhaRegistry::get($relatorio, $state['o116_codseq']));
            }
        }

        if (array_key_exists('o116_orcparamseqcoluna', $state)) {
            $self->setColuna(ColunaRegistry::get($state['o116_orcparamseqcoluna']));
        }

        if (array_key_exists('o116_ordem', $state)) {
            $self->setOrdem($state['o116_ordem']);
        }

        if (array_key_exists('o116_periodo', $state)) {
            $self->setPeriodo($state['o116_periodo']);
        }

        if (array_key_exists('o116_formula', $state)) {
            $self->setFormula($state['o116_formula']);
        }

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'linha' => $this->getLinha()->getLinha(),
            'ordemLinha' => $this->getLinha()->getOrdem(),
            'relatorio' => $this->getRelatorio()->getSequencial(),
            'coluna' => $this->getColuna()->toArray(),
            'ordem' => $this->getOrdem(),
            'periodo' => $this->getPeriodo(),
            'formula' => $this->getFormula()
        );
    }

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
     * @return Linha
     */
    public function getLinha()
    {
        return $this->linha;
    }

    /**
     * @param Linha $linha
     */
    public function setLinha(Linha $linha)
    {
        $this->linha = $linha;
    }

    /**
     * @return Relatorio
     */
    public function getRelatorio()
    {
        return $this->relatorio;
    }

    /**
     * @param Relatorio $relatorio
     */
    public function setRelatorio(Relatorio $relatorio)
    {
        $this->relatorio = $relatorio;
    }

    /**
     * @return Coluna
     */
    public function getColuna()
    {
        return $this->coluna;
    }

    /**
     * @param Coluna $coluna
     */
    public function setColuna(Coluna $coluna)
    {
        $this->coluna = $coluna;
    }

    /**
     * @return int
     */
    public function getOrdem()
    {
        return (int)$this->ordem;
    }

    /**
     * @param int $ordem
     */
    public function setOrdem($ordem)
    {
        $this->ordem = (int)$ordem;
    }

    /**
     * @return int
     */
    public function getPeriodo()
    {
        return (int)$this->periodo;
    }

    /**
     * @param int $periodo
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = (int)$periodo;
    }

    /**
     * @return string
     */
    public function getFormula()
    {
        return (string)$this->formula;
    }

    /**
     * @param string $formula
     */
    public function setFormula($formula)
    {
        $this->formula = (string)$formula;
    }
}
