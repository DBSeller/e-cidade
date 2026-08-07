<?php

/**
 * Class cl_recursogrupo
 * @property integer o204_sequencial
 * @property integer o204_codigo
 * @property string o204_descricao
 * @property string o204_estado
 */
class cl_recursogrupo extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('orcamento.recursogrupo');
    }
}
