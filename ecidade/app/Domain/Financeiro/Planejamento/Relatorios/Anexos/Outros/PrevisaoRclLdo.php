<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios\Anexos\Outros;

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\Xls;

class PrevisaoRclLdo extends Xls
{
    const DRIVE_KEY = '11pduWL95z8mbY9-PxC-952Hmk9_DyhL4cPmjGwl66hk';
    const DRIVE_GID = 0;

    protected $saveAs = 'tmp/previsao_rcl_ldo.xlsx';

    public function __construct()
    {
        parent::__construct(self::DRIVE_KEY, self::DRIVE_GID, false);
    }

    /**
     * @param $ano
     */
    public function setAnoReferencia($ano)
    {
        $this->setVariavel('ano_referencia', $ano);
        $this->setVariavel('ano_mais_um', $ano + 1);
        $this->setVariavel('ano_mais_dois', $ano + 2);
    }
}
