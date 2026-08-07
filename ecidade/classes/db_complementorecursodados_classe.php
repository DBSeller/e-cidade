<?php

/**
 * Class cl_complementorecursodados
 * @property integer o201_sequencial
 * @property integer o201_origem
 * @property integer o201_codigo
 * @property integer o201_complementofonterecurso
 */
class cl_complementorecursodados extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('orcamento.complementorecursodados');
    }
}
