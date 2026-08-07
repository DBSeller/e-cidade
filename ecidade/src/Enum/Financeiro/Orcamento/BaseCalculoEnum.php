<?php

namespace ECidade\Enum\Financeiro\Orcamento;

use ECidade\Enum\Enum;
use Exception;

class BaseCalculoEnum extends Enum
{
    const SALDO_INICIAL = 1;
    const PREVISAO_ATUALIZADA = 2;
    const REALIZADO_REESTIMADO = 3;

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = [
            self::SALDO_INICIAL => "Saldo Inicial",
            self::PREVISAO_ATUALIZADA => "Previsão Atualizada",
            self::REALIZADO_REESTIMADO => "Realizado e Reestimado",
        ];

        if (empty($data[$this->getValue()])) {
            throw new Exception('Base de cálulo inválida inválida.');
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
        $tipos = static::values();
        $return = [];
        foreach ($tipos as $tipo) {
            $return[$tipo->value()] = [
                'value' => $tipo->value(),
                'name' => $tipo->name()
            ];
        }

        sort($return);
        return $return;
    }
}
