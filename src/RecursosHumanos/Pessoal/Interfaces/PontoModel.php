<?php


namespace ECidade\RecursosHumanos\Pessoal\Interfaces;

interface PontoModel
{
    /**
     * PontoModel constructor.
     */
    public function __construct();

    /**
     * @param array $state Linha do banco de dados
     * @return PontoFs
     * @throws Exception
     */
    public static function fromState(array $state);

    /**
     * @param int $instituicao
     */
    public function setInstituicao($instituicao);

    /**
     * @return int
     */
    public function getInstituicao();

    /**
     * @param int $ano
     */
    public function setAno($ano);

    /**
     * @return int
     */
    public function getAno();

    /**
     * @param int $mes
     */
    public function setMes($mes);

    /**
     * @return int
     */
    public function getMes();

    /**
     * @param int $matricula
     */
    public function setMatricula($matricula);
   

    /**
     * @return int
     */
    public function getMatricula();

    /**
     * @return float
     */
    public function getValor();

    /**
     * @param float $valor
     */
    public function setValor($valor);

    /**
     * @return int
     */
    public function getQuantidade();

    /**
     * @param int $quantidade
     */
    public function setQuantidade($quantidade);

    /**
     * @return int
     */
    public function getLotacao();

    /**
     * @param int $lotacao
     */
    public function setLotacao($lotacao);

    /**
     * @return string
     */
    public function getDataLimite();

    /**
     * @param string $dataLimite
     */
    public function setDataLimite($dataLimite);

    /**
     * @return string
     */
    public function getRubrica();

    /**
     * @param string $rubrica
     */
    public function setRubrica($rubrica);
}
