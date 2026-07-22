<?php

/**
 * Class cl_orcdotacaoplanoorcamentario
 * @property integer o155_sequencial
 * @property integer o155_coddot
 * @property integer o155_anousu
 * @property string  o155_titulo
 * @property float   o155_valor
 */
class cl_orcdotacaoplanoorcamentario extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('orcamento.orcdotacaoplanoorcamentario');
    }
}

