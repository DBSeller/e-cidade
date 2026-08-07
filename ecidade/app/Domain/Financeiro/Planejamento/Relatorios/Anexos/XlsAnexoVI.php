<?php


namespace App\Domain\Financeiro\Planejamento\Relatorios\Anexos;

class XlsAnexoVI extends Xls
{
    const DRIVE_KEY = '1jOiuLhC2D3vxiJF_9krdOzfotjrgFSarW-6B2CAr4l4';
    const DRIVE_GID = 0;

    protected $saveAs = 'tmp/Anexo_VI.xlsx';

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
        $this->setVariavel('ano_menos_quatro', $ano - 4);
        $this->setVariavel('ano_menos_tres', $ano - 3);
        $this->setVariavel('ano_menos_dois', $ano - 2);
    }
}
