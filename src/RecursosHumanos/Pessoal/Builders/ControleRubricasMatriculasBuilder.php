<?php

namespace ECidade\RecursosHumanos\Pessoal\Builders;

use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasMatriculas;
use Instituicao;
use Servidor;

class ControleRubricasMatriculasBuilder
{
    /**
     * @var int
     */
    private $sequencial;

    /**
     * @var Instituicao
     */
    private $instituicao;

    /**
     * @var Servidor
     */
    private $servidor;

    /**
     * @var int $ano
     */
    private $ano;

    /**
     * @var int $mes
     */
    private $mes;

    /**
     * @var string
     */
    private $horasLiberadas;

    /**
     * @param int $sequencial
     */
    public function sequencial($sequencial)
    {
        $this->sequencial = $sequencial;
        return $this;
    }

    /**
     * @param Instituicao $instituicao
     * @return ControleRubricasMatriculasBuilder
     */
    public function instituicao(Instituicao $instituicao)
    {
        $this->instituicao = $instituicao;
        return $this;
    }

    /**
     * @param Servidor $servidor
     * @return ControleRubricasMatriculasBuilder
     */
    public function servidor($servidor)
    {
        $this->servidor = $servidor;
        return $this;
    }

    /**
     * @param int $ano
     * @return ControleRubricasMatriculasBuilder
     */
    public function ano($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    /**
     * @param int $mes
     * @return ControleRubricasMatriculasBuilder
     */
    public function mes($mes)
    {
        $this->mes = $mes;
        return $this;
    }

    /**
     * @param string $horasLiberadas
     * @return ControleRubricasMatriculasBuilder
     */
    public function horasLiberadas($horasLiberadas)
    {
        $this->horasLiberadas = $horasLiberadas;
        return $this;
    }

    /**
     * @return ControleRubricasMatriculas
     */
    public function build()
    {
        $controleHorasExtrasMatriculas = new ControleRubricasMatriculas();
        $controleHorasExtrasMatriculas->setSequencial($this->sequencial);
        $controleHorasExtrasMatriculas->setInstituicao($this->instituicao);
        $controleHorasExtrasMatriculas->setServidor($this->servidor);
        $controleHorasExtrasMatriculas->setAno($this->ano);
        $controleHorasExtrasMatriculas->setMes($this->mes);
        $controleHorasExtrasMatriculas->setHorasLiberadas($this->horasLiberadas);

        return $controleHorasExtrasMatriculas;
    }
}
