<?php

/**
 * Class cl_conlancamretificacao
 * @property integer c135_sequencial
 * @property integer c135_codlaninclusao
 * @property integer c135_codlanestorno
 * @property integer c135_codlannovo
 */
class cl_conlancamretificacao extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('contabilidade.conlancamretificacao');
    }
}
