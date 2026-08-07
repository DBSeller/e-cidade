<?php


namespace ECidade\Educacao\Escola\Censo\MatriculaInicial;

use ECidade\Educacao\Escola\Censo\Censo;
use stdClass;

/**
 * Class ImportarInterface
 * @package ECidade\Educacao\Escola\Censo\MatriculaInicial
 */
interface ImportarInterface
{
    /**
     * @param Censo $censo
     */
    public function setCenso(Censo $censo);

    /**
     * @param stdClass $file
     * @return boolean
     */
    public function importarINEP(stdClass $file);
}
