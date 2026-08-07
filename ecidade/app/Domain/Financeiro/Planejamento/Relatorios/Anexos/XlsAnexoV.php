<?php


namespace App\Domain\Financeiro\Planejamento\Relatorios\Anexos;

/**
 * Class XlsAnexoV
 * @package App\Domain\Financeiro\Planejamento\Relatorios\Anexos
 */
class XlsAnexoV extends Xls
{
    const DRIVE_KEY = '1_oVvJFnCW7oE_MqnMl8r1iaM2pKpq-NAWAL5OatWRjM';
    const DRIVE_GID = 0;

    protected $saveAs = 'tmp/Anexo_V.xlsx';

    public function __construct()
    {
        parent::__construct(self::DRIVE_KEY, self::DRIVE_GID);
    }

    /**
     * @param $ano
     */
    public function setAnoReferencia($ano)
    {
        $this->setVariavel('ano_referencia', 'Ano de referência: ' . $ano);
        $this->setVariavel('ano_menos_dois', $ano - 2);
        $this->setVariavel('ano_menos_tres', $ano - 3);
        $this->setVariavel('ano_menos_quatro', $ano - 4);
    }
}
