<?php

namespace App\Domain\Financeiro\Planejamento\Relatorios\Anexos;

class XlsAnexoVIII extends Xls
{
    const DRIVE_KEY = '12qcNXRIoZGIJVynkT5argNmoxY27MSkEedCA1HcIiP8';
    const DRIVE_GID = 0;

    protected $saveAs = 'tmp/Anexo_VIII.xlsx';

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
