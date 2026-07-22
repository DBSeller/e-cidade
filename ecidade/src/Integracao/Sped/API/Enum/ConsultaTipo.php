<?php

namespace ECidade\Integracao\Sped\API\Enum;

use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;

final class ConsultaTipo
{
    const ES_RETORNO_CONTRIBUICOES_SOCIAIS_TRABALHADOR = 'S5001';
    const ES_RETORNO_IMPOSTO_RENDA_FONTE = 'S5002';
    const ES_RETORNO_FGTS_TRABALHADOR = 'S5003';
    const ES_RETORNO_CONTRIBUICOES_SOCIAIS_CONTRIBUINTE = 'S5011';
    const ES_RETORNO_IRRF_CONTRIBUINTE = 'S5012';
    const ES_RETORNO_FGTS_CONSOLIDADAS = 'S5013';

    const EFD_RETORNO_TRIBUTO_POR_EVENTO = 'R5001';
    const EFD_RETORNO_TRIBUTO_POR_PERIODO = 'R5011';

    public static function tipos($tipo = null, $integracao = null)
    {
        $tiposESocial = array(
            self::ES_RETORNO_CONTRIBUICOES_SOCIAIS_TRABALHADOR => 'S-5001 - Informações das contribuições sociais por trabalhador',
            self::ES_RETORNO_IMPOSTO_RENDA_FONTE => 'S-5002 - Imposto de Renda Retido na Fonte',
            self::ES_RETORNO_FGTS_TRABALHADOR => 'S-5003 - Informações do FGTS por Trabalhador',
            self::ES_RETORNO_CONTRIBUICOES_SOCIAIS_CONTRIBUINTE => 'S-5011 - Informações das contribuições sociais consolidadas por contribuinte',
            self::ES_RETORNO_IRRF_CONTRIBUINTE => 'S-5012 - Informações do IRRF consolidadas por contribuinte',
            self::ES_RETORNO_FGTS_CONSOLIDADAS => 'S-5013 - Informações do FGTS consolidadas por contribuinte'
        );
        $tiposEFD = array(
            self::EFD_RETORNO_TRIBUTO_POR_EVENTO => 'R-5001 - Informações de bases e tributos por evento',
            self::EFD_RETORNO_TRIBUTO_POR_PERIODO => 'R-5011 - Informações de bases e tributos consolidadas por período de apuração'
        );
        $tipos = array();
        if (!empty($integracao)) {
            if ($integracao == Tipo::EFD_REINF) {
                $tipos = $tiposEFD;
            } else if ($integracao == Tipo::ESOCIAL) {
                $tipos = $tiposESocial;
            }
        }
        if (count($tipos) == 0) {
            $tipos = array_merge($tiposESocial, $tiposEFD);
        }

        if (!empty($tipo) && !empty($tipos[$tipo])) {
            $tipos = $tipos[$tipo];
        }

        return $tipos;
    }

    public static function getDeParaEventosRetorno($strRetorno)
    {
        switch ($strRetorno) {
            case 'S-5001':
            case 'S-5003':
                return 'S-1200, S-2299, S-2399';
            case 'S-5002':
                return 'S-1210';
            case 'S-5011':
            case 'S-5012':
            case 'S-5013':
                return 'S-1295, S-1299';
            case 'R-5011':
                return 'R-2099';
            case 'R-5001':
                return 'R-2010, R-2020, R-2030, R-2040, R-2050, R-2060, R-3010';
            default:
                return false;
        }
    }
}
