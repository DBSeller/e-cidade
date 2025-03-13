<?php

/**
 * Class cl_recursoidentificador
 * @property integer o202_sequencial
 * @property integer o202_codigo
 * @property string o202_descricao
 * @property string o202_estado
 */
class cl_recursoidentificador extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('orcamento.recursoidentificador');
    }
}
