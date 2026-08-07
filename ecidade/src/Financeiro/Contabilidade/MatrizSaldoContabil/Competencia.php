<?php


namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil;

class Competencia extends \DBCompetencia
{

    /**
     * Adiciona Mês de competência
     *
     * @param $iMes
     * @throws \ParameterException
     */
    protected function setMes($iMes)
    {
        $iMes = $iMes + 0;
        if ($iMes < 1 || $iMes > 13) {
            throw new \ParameterException("Mês da competência inválido.");
        }

        $this->iMes = str_pad($iMes, 2, "0", STR_PAD_LEFT);
    }
}
