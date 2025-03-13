<?php


namespace ECidade\Tributario\Arrecadacao\Model;

/**
 * Class CertidaoTemplateInscricaoModel
 * @package ECidade\Tributario\Arrecadacao\Model
 */
class CertidaoTemplateInscricaoModel
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var int
     */
    private $inscr;

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
    public function getInscr()
    {
        return $this->inscr;
    }

    /**
     * @param int $inscr
     */
    public function setInscr($inscr)
    {
        $this->inscr = $inscr;
    }
}
