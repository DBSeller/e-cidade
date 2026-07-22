<?php

/**
 * Class cl_sliprecursocontas
 * @property integer k181_sequencial
 * @property integer k181_slip
 * @property integer k181_recursocredito
 * @property integer k181_recursodebito
 */
class cl_slipcustasclassificacao extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('caixa.slipcustasclassificacao');
    }

}