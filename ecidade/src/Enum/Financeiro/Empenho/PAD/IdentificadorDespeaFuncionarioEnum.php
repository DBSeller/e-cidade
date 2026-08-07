<?php

namespace ECidade\Enum\Financeiro\Empenho\PAD;

use ECidade\Enum\Enum;
use Exception;

class IdentificadorDespeaFuncionarioEnum extends Enum
{
    const NAO_APLICA = 'X';
    const FOLHA_PAGAMENTO = 'F';
    const INDENIZACOES = 'I';
    const OBRIGACAO_PATRONAL = 'O';

    public function name()
    {
        $data = array(
            self::NAO_APLICA => "NSA (Não se aplica)",
            self::FOLHA_PAGAMENTO => "Folha de pagamento",
            self::INDENIZACOES => "Indenizações não inclusas na folha depagamento",
            self::OBRIGACAO_PATRONAL => "Obrigações Patronais",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Identificador de despesa com funcionário inválido.');
        }

        return $data[$this->getValue()];
    }
}
