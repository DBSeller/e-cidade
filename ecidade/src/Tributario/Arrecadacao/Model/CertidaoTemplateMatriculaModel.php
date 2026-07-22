<?php


namespace ECidade\Tributario\Arrecadacao\Model;

/**
 * Class CertidaoTemplateMatriculaModel
 * @package ECidade\Tributario\Arrecadacao\Model
 */
class CertidaoTemplateMatriculaModel
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var int
     */
    private $matric;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return int
     */
    public function getMatric()
    {
        return $this->matric;
    }

    /**
     * @param int $matric
     */
    public function setMatric($matric)
    {
        $this->matric = $matric;
    }
}
