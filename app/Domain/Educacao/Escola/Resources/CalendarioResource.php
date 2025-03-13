<?php

namespace App\Domain\Educacao\Escola\Resources;

class CalendarioResource
{
    /**
     * @param array $calendarios
     * @return array
     */
    public static function toArray(array $calendarios)
    {
        $data = array();
        foreach ($calendarios as $calendario) {
            $data[] = (object) array(
                'id' => $calendario->ed52_i_codigo,
                'descricao' => trim($calendario->ed52_c_descr)
            );
        }
        return $data;
    }
}
