<?php

namespace ECidade\Tributario\Juridico\Inicial\Builder;

use ECidade\Tributario\Juridico\Inicial\HistoricoDesmembramento;

class HistoricoDesmembramentoBuilder
{
    /**
     * @var HistoricoDesmembramento
     */
    private $historicoDesmembramento;

    public function criarHistoricoDesmembramento()
    {
        $this->historicoDesmembramento = new HistoricoDesmembramento();

        return $this;
    }

    public function getHistoricoDesmembramento()
    {
        return $this->historicoDesmembramento;
    }

    public function addSequencial($sequencial)
    {
        $this->historicoDesmembramento->setSequencial($sequencial);

        return $this;
    }

    public function addInicialOld($inicialOld)
    {
        $this->historicoDesmembramento->setInicialOld($inicialOld);

        return $this;
    }

    public function addInicial($inicial)
    {
        $this->historicoDesmembramento->setInicial($inicial);

        return $this;
    }

    public function addCdaOld($cdaOld)
    {
        $this->historicoDesmembramento->setCdaOld($cdaOld);

        return $this;
    }

    public function addCda($cda)
    {
        $this->historicoDesmembramento->setCda($cda);

        return $this;
    }

    public function addData($data)
    {
        $this->historicoDesmembramento->setData($data);

        return $this;
    }

    public function addUsuario($usuario)
    {
        $this->historicoDesmembramento->setUsuario($usuario);

        return $this;
    }
}
