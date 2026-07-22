<?php

namespace ECidade\Financeiro\Empenho\Enum;

use ECidade\Enum\Enum;

class TipoAquisicaoProducaoRural extends Enum
{
    const PF_GERAL = 1;
    const PF_PAA = 2;
    const PF_ISENTA = 4;
    const PF_PAA_ISENTA = 5;
    const PF_EXPORTACAO = 7;
    const PJ_PAA = 3;
    const PJ_PAA_ISENTA = 6;

    public function name()
    {
        $tpAqPF = 'Aquisição de produção de produtor rural pessoa física ou segurado especial em geral';
        $tpAqPJ = 'Aquisição de produção de produtor rural pessoa jurídica por entidade executora do PAA';

        $data =  [
            self::PF_GERAL => $tpAqPF,
            self::PF_PAA => $tpAqPF . ' por entidade executora do Programa de Aquisição de Alimentos - PAA',
            self::PF_ISENTA => $tpAqPF . ' Produção isenta (Lei 13.606/2018)',
            self::PF_PAA_ISENTA => $tpAqPF . ' por entidade executora do PAA - Produção isenta (Lei 13.606/2018)',
            self::PF_EXPORTACAO => $tpAqPF . ' para fins de exportação',
            self::PJ_PAA => $tpAqPJ,
            self::PJ_PAA_ISENTA => $tpAqPJ . ' Produção isenta (Lei 13.606/2018)'
        ];

        if (empty($data[$this->getValue()])) {
            throw new \Exception('Tipo de aquisição inválido.');
        }

        return $data[$this->getValue()];
    }
}
