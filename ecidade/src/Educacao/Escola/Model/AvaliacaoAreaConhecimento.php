<?php


namespace ECidade\Educacao\Escola\Model;

/**
 * Interface AvaliacaoAreaConhecimento
 * @package ECidade\Educacao\Escola\Model
 */
interface AvaliacaoAreaConhecimento
{
    /**
     * @return integer
     */
    public function getCodigo();

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo);

    /**
     * @return DiarioArea
     */
    public function getDiarioArea();

    /**
     * @param DiarioArea $diarioArea
     */
    public function setDiarioArea(DiarioArea $diarioArea);

    /**
     * @return float
     */
    public function getNota();

    /**
     * @param float $nota
     */
    public function setNota($nota);

    /**
     * @return string
     */
    public function getParecer();

    /**
     * @param string $parecer
     */
    public function setParecer($parecer);

    /**
     * @return string
     */
    public function getConceito();

    /**
     * @param string $conceito
     */
    public function setConceito($conceito);

    /**
     * @return bool
     */
    public function isAmparado();

    /**
     * @param bool $amparado
     */
    public function setAmparado($amparado);

    /**
     * @return AvaliacaoAreaConhecimento
     */
    public function getElementoAvaliacao();
}
