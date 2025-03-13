<?php

/**
 * Class cl_sliprecursocontas
 * @property integer c130_sequencial
 * @property integer c130_conlancam
 * @property integer c130_orctiporec
 * @property integer c130_conta
 * @property integer c130_anousu
 * @property string c130_natureza
 */
class cl_conlancamrecurso extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('contabilidade.conlancamrecurso');
    }

}