<?php

/**
 * Class cl_conlancamcomplementorecurso
 * @property integer o201_sequencial
 * @property integer o201_codlan
 * @property integer o201_complemento
 */
class cl_conlancamcomplementorecurso extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('contabilidade.conlancamcomplementorecurso');
    }
}
