<?php


namespace ECidade\Enum\Financeiro\Planejamento;

use ECidade\Enum\Enum;

/**
 * Class StatusEnum
 * @package ECidade\Financeiro\Planejamento
 */
class StatusEnum extends Enum
{
    const EM_DESENVOLVIMENTO = 1;
    const ENCAMINHADO_PODER_LEGISLATIVO = 2;
    const APROVADO_EMENDAS = 3;
    const APROVADO = 4;
    const RETIFICADO = 5;
}
