<?php

namespace ECidade\RecursosHumanos\Pessoal\Model;

use ECidade\RecursosHumanos\Pessoal\Interfaces\PontoModel;
use Exception;

/**
 * Class RubricasUsuario
 * @package ECidade\RecursosHumanos\Pessoal\Model
 */
class PontoFixo implements PontoModel
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
     * PontoFx constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param array $state Linha do banco de dados
     * @return PontoFx
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $pontoFixo = new self();
        if (empty($state)) {
            return false;
        }
        $pontoFixo->setAno($state["r90_anousu"]);
        $pontoFixo->setMes($state["r90_mesusu"]);
        $pontoFixo->setMatricula($state["r90_regist"]);
        $pontoFixo->setRubrica($state["r90_rubric"]);
        $pontoFixo->setValor($state["r90_valor"]);
        $pontoFixo->setInstituicao($state["r90_instit"]);
        $pontoFixo->setDataLimite($state["r90_datlim"]);
        $pontoFixo->setLotacao($state["r90_lotac"]);
        $pontoFixo->setQuantidade($state["r90_quant"]);

        return $pontoFixo;
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
