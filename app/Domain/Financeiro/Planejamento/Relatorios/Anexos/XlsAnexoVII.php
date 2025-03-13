<?php


namespace App\Domain\Financeiro\Planejamento\Relatorios\Anexos;

/**
 * Class XlsAnexoVII
 * @package App\Domain\Financeiro\Planejamento\Relatorios\Anexos
 */
class XlsAnexoVII extends Xls
{
    const DRIVE_KEY = '1SqcKMAzD3m1Qf3MJJBVHfCUVYkjGmTczvxnS5auCBn4';
    const DRIVE_GID = 0;

    protected $saveAs = 'tmp/Anexo_VII.xlsx';

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
        $this->setVariavel('ano_menos_tres', $ano - 3);
        $this->setVariavel('ano_mais_um', $ano + 1);
        $this->setVariavel('ano_mais_dois', $ano + 2);
    }
}
