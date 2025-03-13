<?php

namespace ECidade\Financeiro\Slip\Retencao\Apropriacao;


class Apropriacao
{

    /**
     * Codigo do Slip
     * @var integer
     */
    private $slip;

    public function __construct($slip)
    {

        $this->slip = $slip;
    }


    /**
     * REalizar a apropriação das retenções extra orçamentarias
     */
    public function apropriar()
    {


    }


}