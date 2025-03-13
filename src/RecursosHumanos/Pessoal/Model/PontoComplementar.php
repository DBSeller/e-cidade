<?php

namespace ECidade\RecursosHumanos\Pessoal\Model;

use ECidade\RecursosHumanos\Pessoal\Interfaces\PontoModel;
use Exception;

/**
 * @package ECidade\RecursosHumanos\Pessoal\Model
 */
class PontoComplementar implements PontoModel
{
    /**
     * @var int
     */
    private $ano;
    /**
     * @var int
     */
    private $mes;
    /**
     * @var int
     */
    private $matricula;
    /**
     * @var double
     */
    private $valor;
    /**
     * @var int
     */
    private $quantidade;
    /**
     * @var int
     */
    private $lotacao;
    /**
     * @var string
     */
    private $dataLimite;
    /**
     * @var int
     */
    private $instituicao;
    /**
     * @var string
     */
    private $rubrica;

    /**
     * Pontocom constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param array $state Linha do banco de dados
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $pontoComplementar = new self();
        if (empty($state)) {
            return false;
        }
        $pontoComplementar->setAno($state["r47_anousu"]);
        $pontoComplementar->setMes($state["r47_mesusu"]);
        $pontoComplementar->setMatricula($state["r47_regist"]);
        $pontoComplementar->setRubrica($state["r47_rubric"]);
        $pontoComplementar->setValor($state["r47_valor"]);
        $pontoComplementar->setInstituicao($state["r47_instit"]);
        $pontoComplementar->setDataLimite($state["r47_datlim"]);
        $pontoComplementar->setLotacao($state["r47_lotac"]);
        $pontoComplementar->setQuantidade($state["r47_quant"]);

        return $pontoComplementar;
    }

    /**
     * @param int $instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param int $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param int $mes
     */
    public function setMes($mes)
    {
        $this->mes = $mes;
    }

    /**
     * @return int
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param int $matricula
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    /**
     * @return int
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return int
     */
    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * @param int $quantidade
     */
    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    /**
     * @return int
     */
    public function getLotacao()
    {
        return $this->lotacao;
    }

    /**
     * @param int $lotacao
     */
    public function setLotacao($lotacao)
    {
        $this->lotacao = $lotacao;
    }

    /**
     * @return string
     */
    public function getDataLimite()
    {
        return $this->dataLimite;
    }

    /**
     * @param string $dataLimite
     */
    public function setDataLimite($dataLimite)
    {
        $this->dataLimite = $dataLimite;
    }

    /**
     * @return string
     */
    public function getRubrica()
    {
        return $this->rubrica;
    }

    /**
     * @param string $rubrica
     */
    public function setRubrica($rubrica)
    {
        $this->rubrica = $rubrica;
    }
}
