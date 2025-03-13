<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Entity;

use \DateTime;
use ECidade\Tributario\Library\Entity;

final class FiltroUnica extends Entity
{
    private $data;

    private $porcentagem;

    public function __construct(DateTime $data, $porcentagem)
    {
        $this->data = $data;
        $this->porcentagem = $porcentagem;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setPorcentagem($porcentagem)
    {
        $this->porcentagem = $porcentagem;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getPorcentagem()
    {
        return $this->porcentagem;
    }
}
