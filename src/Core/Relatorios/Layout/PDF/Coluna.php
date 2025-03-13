<?php


namespace ECidade\Core\Relatorios\Layout\PDF;

use Exception;

/**
 * Class Coluna
 * @package ECidade\Core\Relatorios\Layout\PDF
 */
class Coluna
{
    /**
     * @var int
     */
    public $w = 0;

    /**
     * @var int
     */
    public $h = 4;

    /**
     * @var null
     */
    public $value = null;

    /**
     * @var string
     */
    public $border = '0';

    /**
     * @var string
     */
    public $align = 'L';

    /**
     * @var int
     */
    public $fill = 0;

    /**
     * @var int
     */
    public $ln = 0;

    /**
     * @var array
     */
    private $aBordasAceitas = array(0, 1, 'TBR', 'TBL', 'TB', 'BT', 'L', 'R', 'RL', 'LR' );

    /**
     * Ver documentação fpdf
     * - http://www.fpdf.org/en/doc/cell.htm
     * - http://www.fpdf.org/en/doc/multicell.htm
     * @param int $w
     * @param string $value
     * @param string $border
     * @param int $ln
     * @param string $align
     * @param int $fill
     * @param int $h
     * @throws Exception
     */
    public function set($w = 0, $value = '', $border = '0', $ln = 0, $align = 'L', $fill = 0, $h = 4)
    {
        if (!in_array($border, $this->aBordasAceitas)) {
            throw new Exception(sprintf(
                "Borda informada não implementada.\nUsar: %s",
                implode(', ', $this->aBordasAceitas)
            ));
        }

        $this->w      = $w;
        $this->h      = $h;
        $this->value  = $value;
        $this->border = $border;
        $this->align  = $align;
        $this->fill   = $fill;
        $this->ln     = $ln;
    }
}
