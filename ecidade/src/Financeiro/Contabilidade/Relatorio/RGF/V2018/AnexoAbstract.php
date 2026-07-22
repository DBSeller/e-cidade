<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018;

use PDFDocument;
use Periodo;
use stdClass;

/**
 * Class AnexoAbstract
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RGF\V2018
 */
abstract class AnexoAbstract
{
    /**
     * @var array
     */
    protected $instituicoes;
    /**
     * @var Periodo
     */
    protected $periodo;
    /**
     * @var int
     */
    protected $ano;

    public abstract function processar();

    public abstract function definirParametros(stdClass $parametros);

    /**
     * @param array $instituicoes
     */
    public function definirInstituicoes(array $instituicoes)
    {
        $this->instituicoes = $instituicoes;
    }

    /**
     * @param Periodo $periodo
     */
    public function definirPeriodo(Periodo $periodo)
    {
        $this->periodo = $periodo;
    }

    public function definirAno($ano)
    {
        $this->ano = $ano;
    }

    public function getInstituicoes()
    {
        return $this->instituicoes;
    }

    public function getAno()
    {
        return $this->ano;
    }

    public function getPeriodo()
    {
        return $this->periodo;
    }
}
