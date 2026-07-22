<?php


namespace ECidade\Enum\Educacao\BNCC;

use ECidade\Enum\Enum;
use Exception;

/**
 * Class TipoBaseCurricular
 * @package ECidade\Enum\Educacao\BNCC
 */
class TipoBaseCurricularEnum extends Enum
{
    const BNCC_PADRAO = 1;
    const BNCC_COMENTADA = 2;
    const REFERENCIAL_CURRICULAR_ESTADUAL = 3;

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = array(
            self::BNCC_PADRAO => "BNCC Padrão",
            self::BNCC_COMENTADA => "BNCC Comentada",
            self::REFERENCIAL_CURRICULAR_ESTADUAL => "Referencial Curricular Estadual",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Tipo de base da BNCC não encontrado.');
        }

        return $data[$this->getValue()];
    }


    /**
     * Retorna os valores com os nomes
     * @return array
     * @throws Exception
     */
    public static function toArrayWithNames()
    {
        $tipos = self::values();
        $return = array();
        foreach ($tipos as $tipo) {
            $return[] = array(
                'value' => $tipo->value(),
                'name' => $tipo->name()
            );
        }

        return $return;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function toJson()
    {
        return array(
            'value' => $this->value(),
            'name' => $this->name()
        );
    }
}
