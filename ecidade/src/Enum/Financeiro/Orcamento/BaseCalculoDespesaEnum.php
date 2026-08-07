<?php

namespace ECidade\Enum\Financeiro\Orcamento;

use Exception;

class BaseCalculoDespesaEnum extends BaseCalculoEnum
{
    const REALIZADO_REESTIMADO_EMPENHADO = 3;
    const REALIZADO_REESTIMADO_LIQUIDADO = 4;
    const REALIZADO_REESTIMADO_PAGO = 5;

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = [
            self::SALDO_INICIAL => "Saldo Inicial",
            self::PREVISAO_ATUALIZADA => "Previsão Atualizada",
            self::REALIZADO_REESTIMADO_EMPENHADO => "Realizado e Reestimado - Empenhado",
            self::REALIZADO_REESTIMADO_LIQUIDADO => "Realizado e Reestimado - Liquidado",
            self::REALIZADO_REESTIMADO_PAGO => "Realizado e Reestimado - Pago",
        ];

        if (empty($data[$this->getValue()])) {
            throw new Exception('Base de cálulo inválida inválida.');
        }

        return $data[$this->getValue()];
    }
}
