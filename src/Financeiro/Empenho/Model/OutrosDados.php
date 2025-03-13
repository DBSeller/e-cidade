<?php

namespace ECidade\Financeiro\Empenho\Model;

class OutrosDados
{

    private $codigo;
    private $empenho;
    private $outrosDados;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return mixed
     */
    public function getEmpenho()
    {
        return $this->empenho;
    }

    /**
     * @param mixed $empenho
     */
    public function setEmpenho($empenho)
    {
        $this->empenho = $empenho;
    }

    /**
     * @return \stdClass
     */
    public function getOutrosDados()
    {
        return $this->outrosDados;
    }

    /**
     * @param mixed $outrosDados
     */
    public function setOutrosDados($outrosDados)
    {
        $this->outrosDados = $outrosDados;
    }

    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('e171_numdadosemp', $state)) {
            $self->setCodigo($state['e171_numdadosemp']);
        }
        if (array_key_exists('e171_numemp', $state)) {
            $self->setEmpenho($state['e171_numemp']);
        }
        if (array_key_exists('e171_dados', $state)) {
            if (!empty($state['e171_dados'])) {
                $self->setOutrosDados(json_decode($state['e171_dados']));
            }
        }

        return $self;
    }
}
