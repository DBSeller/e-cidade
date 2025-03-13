<?php

/**
 * Class cl_visaocontacorrente
 * @property integer c131_sequencial
 * @property integer c131_db_itensmenu
 * @property string c131_nome
 * @property string c131_filtros
 */
class cl_visaocontacorrente extends DAOBasica
{

    public function __construct()
    {
        parent::__construct('contabilidade.visaocontacorrente');
    }

}
