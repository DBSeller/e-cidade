<?php

namespace ECidade\Educacao\Secretaria\BNCC\Factory;

use ECidade\Educacao\Secretaria\BNCC\Interfaces\PlanilhaHabilidadeInterface;
use ECidade\Educacao\Secretaria\BNCC\Service\PlanilhaHabilidadeEnsinoFundamentalService;
use ECidade\Educacao\Secretaria\BNCC\Service\PlanilhaHabilidadeEnsinoInfantilService;
use ECidade\Educacao\Secretaria\BNCC\Service\PlanilhaHabilidadeReferencialGuacho;
use ECidade\Enum\Educacao\BNCC\EnsinoEnum;
use Exception;

/**
 * Class ImportaPlanilhaHabilidadeFactory
 */
class ImportaPlanilhaHabilidadeFactory
{
    /**
     * @param $tipo
     * @return PlanilhaHabilidadeInterface
     * @throws Exception
     */
    public static function porTipo($tipo)
    {
        switch ($tipo) {
            case EnsinoEnum::ENSINO_INFANTIL:
                return new PlanilhaHabilidadeEnsinoInfantilService();
                break;
            case EnsinoEnum::ENSINO_FUNDAMENTAL:
                return new PlanilhaHabilidadeEnsinoFundamentalService();
                break;
            case 'EF_REFERENCIAL_GAUCHO':
                return new PlanilhaHabilidadeReferencialGuacho(EnsinoEnum::ENSINO_FUNDAMENTAL);
                break;
            case 'EI_REFERENCIAL_GAUCHO':
                return new PlanilhaHabilidadeReferencialGuacho(EnsinoEnum::ENSINO_INFANTIL);
                break;
            case 'EM_REFERENCIAL_GAUCHO':
                return new PlanilhaHabilidadeReferencialGuacho(EnsinoEnum::ENSINO_MEDIO);
                break;
        }

        throw new Exception('Tipo não implementado.');
    }
}
