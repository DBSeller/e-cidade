<?php

/**
 * Class cl_orcdotacaoplanoorcamentario
 * @property integer o156_sequencial
 * @property integer o156_linhaspacto
 * @property integer o156_orcdotacaoplanoorcamentario
 * @property float   o156_valor
 */
class cl_planoorcamentariolinhapacto extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('orcamento.planoorcamentariolinhapacto');
    }
}

