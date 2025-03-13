<?php
namespace ECidade\Patrimonial\Compras\Service;

use ECidade\Patrimonial\Compras\Model\CampanhaPublicitaria;
use ECidade\Patrimonial\Compras\Repository\CampanhaPublicitariaRepository;

class CampanhaPublicitariaService
{
    /**
     * @var stdClass
     */

    private $parametros;

    /**
     * @var CampanhaPublicitariaRepository
     */


    private $repositorio;

    /**
     * @param stdClass parametros
     */
    public function __construct($parametros)
    {
        $this->parametros = $parametros;
        $this->repositorio = new CampanhaPublicitariaRepository();
    }

    public function inserirCampanhaPublicitaria()
    {
        $this->repositorio->save($this->parametros);
    }

    /**
     * @return CampanhaPublicitaria|false
     */
    public function buscarCampanhaPublicitaria()
    {
        return $this->repositorio->find($this->parametros->codigoMater);
    }
}
