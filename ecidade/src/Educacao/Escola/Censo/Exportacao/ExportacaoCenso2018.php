<?php

namespace ECidade\Educacao\Escola\Censo\Exportacao;

use ECidade\Educacao\Escola\Censo\Validacao\DadosAluno2018;
use ECidade\Educacao\Escola\Censo\Validacao\DadosDocente2018;
use ECidade\Educacao\Escola\Censo\Validacao\DadosEscola2018;
use ECidade\Educacao\Escola\Censo\Validacao\DadosTurma2018;
use ExportacaoCenso2017;

/**
 * Class ExportacaoCenso2018
 * @package ECidade\Educacao\Escola\Censo\Exportacao
 */
class ExportacaoCenso2018 extends ExportacaoCenso2017
{
    /**
     * ExportacaoCenso2018 constructor.
     * @param $escola
     * @param $ano
     */
    public function __construct($escola, $ano)
    {
        $this->iCodigoEscola = $escola;
        $this->iAnoCenso = $ano;
        $this->iCodigoLayout = 303;
    }

    /**
     * @return bool
     */
    protected function validarDadosEscola()
    {
        return DadosEscola2018::validarDados($this);
    }

    /**
     * @return bool
     */
    protected function validarDadosAluno()
    {
        return DadosAluno2018::validarDados($this);
    }

    /**
     * @return bool
     */
    protected function validarDadosDocente()
    {
        return DadosDocente2018::validarDados($this);
    }

    /**
     * @return bool
     */
    protected function validarDadosTurma()
    {
        return DadosTurma2018::validarDados($this);
    }
}
