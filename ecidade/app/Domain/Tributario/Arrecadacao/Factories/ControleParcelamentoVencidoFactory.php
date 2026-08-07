<?php

namespace App\Domain\Tributario\Arrecadacao\Factories;

use App\Domain\Tributario\Arrecadacao\Services\AntecipaDataVencidaService;
use App\Domain\Tributario\Arrecadacao\Services\AntecipaVencimentoDataTermoService;
use App\Domain\Tributario\Arrecadacao\Services\AnulacaoParcelamento;

class ControleParcelamentoVencidoFactory
{
    const ANTECIPA_MAIOR_DATA_VENCIDA = 1;
    const ANTECIPA_MENOR_DATA_VENCIDA = 2;
    const ANTECIPA_VENCIMENTO_DATA_TERMO = 3;
    const ANULACAO_PARCELAMENTO = 4;

    /**
     *
     * @param integer $acao
     * @return App\Domain\Tributario\Arrecadacao\Contracts\AcaoControleParcelamento
     */
    public static function getAcaoService($acao)
    {
        switch ($acao) {
            case self::ANTECIPA_MAIOR_DATA_VENCIDA:
                return new AntecipaDataVencidaService(self::ANTECIPA_MAIOR_DATA_VENCIDA);
                break;
            case self::ANTECIPA_MENOR_DATA_VENCIDA:
                return new AntecipaDataVencidaService(self::ANTECIPA_MENOR_DATA_VENCIDA);
                break;
            case self::ANTECIPA_VENCIMENTO_DATA_TERMO:
                return new AntecipaVencimentoDataTermoService(self::ANTECIPA_VENCIMENTO_DATA_TERMO);
            case self::ANULACAO_PARCELAMENTO:
                return new AnulacaoParcelamento(self::ANULACAO_PARCELAMENTO);
            default:
                throw new \Exception('Erro ao processar aчуo. Regra de vencimento nуo cadastrada!');
                break;
        }
    }
}
