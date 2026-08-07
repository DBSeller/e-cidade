<?php


namespace ECidade\Core\Relatorios\Layout\CSV;

use ECidade\Core\Relatorios\Interfaces\CampoDinamico;
use ECidade\Core\Relatorios\Layout\Layout;
use ECidade\File\Csv\Dumper\Dumper;

/**
 * Class RelatorioCsv
 * @package ECidade\Core\Relatorios\Layout\CSV
 */
abstract class RelatorioCsv implements Layout
{
    /**
     * @var Dumper
     */
    protected $dumperCsv;
    /**
     * Array com os dados a ser impressos
     * @var array
     */
    protected $dados = [];
    /**
     * @var CampoDinamico[]
     */
    protected $campos = [];

    public function __construct(Dumper $dumperCsv, $dados)
    {
        $this->dumperCsv = $dumperCsv;
        $this->dumperCsv->setCsvControl(';');
        $this->dados = $dados;
    }

    /**
     * @param string $fileName
     * @return string
     */
    abstract public function imprimir($fileName = null);

    /**
     * @param array $campos
     * @return Layout
     */
    public function setCampos(array $campos)
    {
        $this->campos = $campos;
        return $this;
    }
}
