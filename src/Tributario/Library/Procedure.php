<?php 

namespace ECidade\Tributario\Library;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Library\Service;

abstract class Procedure extends Service
{
    protected $dataBase;

    public function __construct(DataBase $dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function getDataBase()
    {
        return $this->dataBase;
    }
}
