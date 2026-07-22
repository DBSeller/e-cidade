<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios\Anexos\Outros;

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\Xls;

class PrevisaoRclLoa extends Xls
{
    const DRIVE_KEY = '1Cdo4Z2pYo_iTfKq2tvtWgYE5lI1Ma_pcvzTn6SBCsRs';
    const DRIVE_GID = 0;

    protected $saveAs = 'tmp/previsao_rcl_loa.xlsx';

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
    }
}
