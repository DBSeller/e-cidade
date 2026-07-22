<?php 

namespace ECidade\Tributario\Library;

use ECidade\Tributario\Library\DataBase;

abstract class DataBaseRepository
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
