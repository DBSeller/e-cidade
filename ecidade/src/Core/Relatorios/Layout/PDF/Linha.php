<?php

namespace ECidade\Core\Relatorios\Layout\PDF;

use Exception;

/**
 * Class Linha
 * @package ECidade\Core\Relatorios\Layout\PDF
 */
class Linha
{
    /**
     * @var bool
     */
    public $chamarMetodo = false;

    /**
     * @var null
     */
    public $nomeMetodo = null;

    /**
     * @var Coluna[]
     */
    public $colunas = array();

    /**
     * @var bool
     */
    public $multiCell = false;

    /**
     * @var bool
     */
    public $bold = false;

    /**
     * @var int
     */
    public $alturaLinha = 4;

    /**
     * @param int $w
     * @param null $value
     * @param string $border
     * @param int $ln
     * @param string $align
     * @param int $fill
     * @param int $h
     *
     * @return Linha
     * @throws Exception
     */
    public function addColuna($w = 0, $value = null, $border = '1', $ln = 0, $align = 'L', $fill = 0, $h = 4)
    {
        $coluna = new Coluna();
        $coluna->set($w, $value, $border, $ln, $align, $fill, $h);
        $this->colunas[] = $coluna;
        return $this;
    }

    public function addColunaCampo($colunaCampo)
    {
        $coluna = new ColunaCampo($colunaCampo);
        $this->colunas[] = $coluna;
        return $this;
    }



    /**
     * Informa se a linha será impressa com multicell
     * @param  boolean $multiCell
     * @return Linha
     */
    public function multicell($multiCell)
    {
        $this->multiCell = $multiCell;
        return $this;
    }

    /**
     * Informa se a linha deverá ser impressa em negrito
     * @param boolean $bold
     * @return Linha
     */
    public function bold($bold)
    {
        $this->bold = $bold;
        return $this;
    }

    /**
     * Informa se a altura da linha... usado só em celulas MultiCell por enquanto
     * @param integer  $alturaLinha
     * @return Linha
     */
    public function alturaLinha($alturaLinha = 4)
    {
        $this->alturaLinha = $alturaLinha;
        return $this;
    }

    /**
     * Informa um metodo a ser executado
     * @param string $sNomeMetodo metodo a ser executado
     * @return Linha
     */
    public function informaMetodo($sNomeMetodo)
    {
        $this->chamarMetodo = true;
        $this->nomeMetodo  = $sNomeMetodo;
        return $this;
    }

    /**
     * @return Coluna[]
     */
    public function getColunas()
    {
        return $this->colunas;
    }

    public function getWidth()
    {
        $total = 0;
        foreach ($this->colunas as $coluna) {
            $total += $coluna->w;
        }

        return $total;
    }
}
