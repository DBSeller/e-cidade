<?php


/**
 * Class cl_arquivo_tef
 * @property $id
 * @property $linha_tef_id
 * @property $conlancam_id
 */
class cl_linha_tef_processado extends DAOBasica
{
    public function __construct()
    {
        parent::__construct('caixa.linha_tef_processado');
        $this->setSalvarAccount(false);
    }
}
