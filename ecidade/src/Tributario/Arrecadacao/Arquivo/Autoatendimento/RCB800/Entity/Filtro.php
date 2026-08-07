<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Entity;

use \DateTime;
use ECidade\Tributario\Library\Entity;
use ECidade\Tributario\Caixa\Entity\Lista;

final class Filtro extends Entity
{
    private $lista;

    private $dataVigenciaInicial;

    private $dataVigenciaFinal;
    
    private $producao;
    
    private $codigoConvenio;

    public function getLista()
    {
        return $this->lista;
    }

    public function setLista(Lista $lista)
    {
        $this->lista = $lista;
    }

    public function getDataVigenciaInicial()
    {
        return $this->dataVigenciaInicial;
    }

    public function setDataVigenciaInicial(DateTime $dataVigenciaInicial)
    {
        $this->dataVigenciaInicial = $dataVigenciaInicial;
    }

    public function getDataVigenciaFinal()
    {
        return $this->dataVigenciaFinal;
    }

    public function setDataVigenciaFinal(DateTime $dataVigenciaFinal)
    {
        $this->dataVigenciaFinal = $dataVigenciaFinal;
    }

    public function getProducao()
    {
        return $this->producao;
    }

    public function setProducao($producao)
    {
        $this->producao = $producao;
    }

    public function getCodigoConvenio()
    {
        return $this->codigoConvenio;
    }

    public function setCodigoConvenio($codigoConvenio)
    {
        $this->codigoConvenio = $codigoConvenio;
    }
}