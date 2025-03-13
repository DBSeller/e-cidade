<?php

namespace ECidade\Tributario\Library;

use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Library\DataBase;

abstract class Repository extends DataBaseRepository
{
    protected $dao;

    public function __construct(DataBase $dataBase, $dao)
    {
        parent::__construct($dataBase);

        $this->dao = $dao;
    }

    public function getDao()
    {
        return $this->dao;
    }
}
