<?php

namespace ECidade\Lib\Database;

use ECidade\V3\Datasource\Database;

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
