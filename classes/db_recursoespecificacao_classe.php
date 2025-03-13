<?php

/**
 * Class cl_recursoespecificacao
 * @property integer o205_sequencial
 * @property integer o205_codigo
 * @property string o205_descricao
 * @property string o205_estado
 */
class cl_recursoespecificacao extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('orcamento.recursoespecificacao');
    }
}
