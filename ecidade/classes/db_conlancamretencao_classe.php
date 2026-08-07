<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 16/10/18
 * Time: 12:17
 */

/**
 * Class cl_conlancamretencao
 *
 * @property int c127_sequencial
 * @property int c127_conlancam
 * @property int c127_retencaotiporec
 */
class cl_conlancamretencao extends DAOBasica
{
    /**
     * cl_conlancamretencao constructor.
     * @pr
     */
    public function __construct()
    {
        parent::__construct('contabilidade.conlancamretencao');
    }
}