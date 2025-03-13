<?php

namespace ECidade\Tributario\Arrecadacao\Service;

use ECidade\Tributario\Arrecadacao\Model\ReciboAvulso as ReciboAvulsoModel;
use ECidade\Tributario\Arrecadacao\Repository\ReciboAvulso as ReciboAvulsoRepository;

use Exception;

class ReciboAvulso
{
    /**
     * @var ReciboAvulsoRepository
     */
    private $repositorio;

    /**
     * ReciboAvulso constructor.
     * @param ReciboAvulsoRepository $reciboAvulsoRepository
     */
    public function __construct(ReciboAvulsoRepository $reciboAvulsoRepository)
    {
        $this->repositorio = $reciboAvulsoRepository;
    }

    /**
     * @param ReciboAvulsoModel $reciboAvulsoModel
     * @throws Exception
     */
    public function save(ReciboAvulsoModel $reciboAvulsoModel)
    {
        $this->repositorio->save($reciboAvulsoModel);
    }
}
