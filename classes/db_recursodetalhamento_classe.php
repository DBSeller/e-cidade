<?php

/**
 * Class cl_recursodetalhamento
 * @property integer o203_sequencial
 * @property integer o203_codigo
 * @property string o203_descricao
 * @property string o203_estado
 */
class cl_recursodetalhamento extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('orcamento.recursodetalhamento');
    }
}
