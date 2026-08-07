<?php


namespace ECidade\Enum\Educacao\Escola;

use ECidade\Enum\Enum;
use Exception;

/**
 * Class FormaObtencaoEnum
 * @package ECidade\Enum\Educacao\Escola
 */
class FormaObtencaoEnum extends Enum
{
    const ATRIBUIDO = 'AT';
    const MAIOR_NIVEL = 'MC';
    const ULTIMO_NIVEL = 'UC';
    const MEDIA_ARITMETICA = 'ME';
    const MEDIA_PONDERADA = 'MP';
    const SOMA = 'SO';
    const MAIOR_NOTA = 'MN';
    const ULTIMA_NOTA = 'UN';
    const APROVACAO_PERIODOS = 'AP';

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = array(
            self::ATRIBUIDO => "Atribuído",
            self::MAIOR_NIVEL => "Maior Nível",
            self::ULTIMO_NIVEL => "Último Nível",
            self::MEDIA_ARITMETICA => "Média Aritmética",
            self::MEDIA_PONDERADA => "Média Ponderada",
            self::SOMA => "Soma",
            self::MAIOR_NOTA => "Maior Nota",
            self::ULTIMA_NOTA => "Última Nota",
            self::APROVACAO_PERIODOS => "Aprovação por Período",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Forma de Obtenção não encontrada.');
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
}
