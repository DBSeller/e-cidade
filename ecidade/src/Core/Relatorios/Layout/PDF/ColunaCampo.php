<?php


namespace ECidade\Core\Relatorios\Layout\PDF;

use ECidade\Core\Relatorios\Interfaces\CampoDinamico;

/**
 * Class ColunaCampo
 * @package ECidade\Core\Relatorios\Layout\PDF
 */
class ColunaCampo extends Coluna
{
    /**
     * @var CampoDinamico
     */
    protected $campo;

    /**
     * ColunaCampo constructor.
     * @param $campo
     */
    public function __construct(CampoDinamico $campo)
    {
        $this->campo = $campo;
        $this->w = $campo->getWidth();
    }

    /**
     * @return CampoDinamico
     */
    public function getCampo()
    {
        return $this->campo;
    }
}
