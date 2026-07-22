<?php

namespace ECidade\Tributario\Juridico\CartorioExtrajudicial\Model;

class CartorioExtraTipo
{
    /**
     * @var integer
     */
    private $sequencial;

    /**
     * @var integer
     */
    private $cartorioextra;

    /**
     * @var integer
     */
    private $tiposcartorioextra;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     * @return self
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;

        return $this;
    }

    /**
     * Get the value of cartorioextra
     *
     * @return  integer
     */
    public function getCartorioextra()
    {
        return $this->cartorioextra;
    }

    /**
     * Set the value of cartorioextra
     *
     * @param  integer  $cartorioextra
     *
     * @return  self
     */
    public function setCartorioextra($cartorioextra)
    {
        $this->cartorioextra = $cartorioextra;

        return $this;
    }

    /**
     * Get the value of tiposcartorioextra
     *
     * @return  integer
     */
    public function getTiposcartorioextra()
    {
        return $this->tiposcartorioextra;
    }

    /**
     * Set the value of tiposcartorioextra
     *
     * @param  integer  $tiposcartorioextra
     *
     * @return  self
     */
    public function setTiposcartorioextra($tiposcartorioextra)
    {
        $this->tiposcartorioextra = $tiposcartorioextra;

        return $this;
    }
}
