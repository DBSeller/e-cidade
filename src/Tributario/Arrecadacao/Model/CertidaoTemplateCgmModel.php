<?php


namespace ECidade\Tributario\Arrecadacao\Model;

/**
 * Class CertidaoTemplateCgmModel
 * @package ECidade\Tributario\Arrecadacao\Model
 */
class CertidaoTemplateCgmModel
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var int
     */
    private $numcgm;

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
    public function getNumcgm()
    {
        return $this->numcgm;
    }

    /**
     * @param int $numcgm
     */
    public function setNumcgm($numcgm)
    {
        $this->numcgm = $numcgm;
    }
}
