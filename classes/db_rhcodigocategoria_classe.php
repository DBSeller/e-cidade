<?php
/**
* Class cl_rhcodigocategoria
 * @property $rh255_codigo
 * @property $rh255_descricao
 */
class cl_rhcodigocategoria extends DAOBasica
{
    /**
     * cl_rhcodigocategoria constructor.
     */
    public function __construct()
    {
        parent::__construct('recursoshumanos.rhcodigocategoria');
    }
}
?>