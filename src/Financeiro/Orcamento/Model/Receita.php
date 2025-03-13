<?php

namespace ECidade\Financeiro\Orcamento\Model;

class Receita
{
    private $ano;
    private $reduzido;
    private $idFonte;
    private $idRecurso;
    private $valor;
    private $lancada;
    private $idInstituicao;
    private $caracteriscaPeculiar;
    private $dataCriacao;
    private $idOrgao;
    private $idUnidade;
    private $esferaOrcamentaria;


    /**
     * @return integer
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param integer $ano
     * @return Receita
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    /**
     * @return integer
     */
    public function getReduzido()
    {
        return $this->reduzido;
    }

    /**
     * @param integer $reduzido
     * @return Receita
     */
    public function setReduzido($reduzido)
    {
        $this->reduzido = $reduzido;
        return $this;
    }

    /**
     * @return integer
    */
    public function getIdFonte()
    {
        return $this->idFonte;
    }

    /**
     * @param integer $idFonte
     * @return Receita
     */
    public function setIdFonte($idFonte)
    {
        $this->idFonte = $idFonte;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTipoRecurso()
    {
        return $this->idRecurso;
    }

    /**
     * @param integer $idTipoRecurso
     * @return Receita
     */
    public function setTipoRecurso($idTipoRecurso)
    {
        $this->idRecurso = $idTipoRecurso;
        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param float $valor
     * @return Receita
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return bool
     */
    public function getStatusReceita()
    {
        return $this->lancada;
    }

    /**
     * @param bool $lancada
     * @return Receita
     */
    public function setStatusReceita($lancada)
    {
        $this->lancada = $lancada;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdInstituicao()
    {
        return $this->idInstituicao;
    }

    /**
     * @param integer $idInstituicao
     * @return Receita
     */
    public function setIdInstituicao($idInstituicao)
    {
        $this->idInstituicao = $idInstituicao;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaracteriscaPeculiar()
    {
        return $this->caracteriscaPeculiar;
    }

    /**
     * @param string $caracteriscaPeculiar
     * @return Receita
     */
    public function setCaracteriscaPeculiar($caracteriscaPeculiar)
    {
        $this->caracteriscaPeculiar = $caracteriscaPeculiar;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    /**
     * @param string $dataCriacao
     * @return Receita
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdOrgao()
    {
        return $this->idOrgao;
    }

    /**
     * @param integer $idOrgao
     * @return Receita
     */
    public function setIdOrgao($idOrgao)
    {
        $this->idOrgao = $idOrgao;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdUnidade()
    {
        return $this->idUnidade;
    }

    /**
     * @param integer $idUnidade
     * @return Receita
     */
    public function setIdUnidade($idUnidade)
    {
        $this->idUnidade = $idUnidade;
        return $this;
    }

    /**
     * @return integer
     */
    public function getEsferaOrcamentaria()
    {
        return $this->esferaOrcamentaria;
    }

    /**
     * @param integer $esferaOrcamentaria
     * @return Receita
     */
    public function setEsferaOrcamentaria($esferaOrcamentaria)
    {
        $this->esferaOrcamentaria = $esferaOrcamentaria;
        return $this;
    }
    /**
     * @param array $state
     * @return Receita
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('o70_anousu', $state)) {
            $self->setAno($state['o70_anousu']);
        }
        if (array_key_exists('o70_codrec', $state)) {
            $self->setReduzido($state['o70_codrec']);
        }
        if (array_key_exists('o70_codfon', $state)) {
            $self->setIdFonte($state['o70_codfon']);
        }
        if (array_key_exists('o70_codigo', $state)) {
            $self->setTipoRecurso($state['o70_codigo']);
        }
        if (array_key_exists('o70_valor', $state)) {
            $self->setValor($state['o70_valor']);
        }
        if (array_key_exists('o70_reclan', $state)) {
            $self->setStatusReceita($state['o70_reclan']);
        }
        if (array_key_exists('o70_instit', $state)) {
            $self->setIdInstituicao($state['o70_instit']);
        }
        if (array_key_exists('o70_concarpeculiar', $state)) {
            $self->setCaracteriscaPeculiar($state['o70_concarpeculiar']);
        }
        if (array_key_exists('o70_datacriacao', $state)) {
            $self->setDataCriacao($state['o70_datacriacao']);
        }
        if (array_key_exists('o70_orcorgao', $state)) {
            $self->setIdOrgao($state['o70_orcorgao']);
        }
        if (array_key_exists('o70_orcunidade', $state)) {
            $self->setIdUnidade($state['o70_orcunidade']);
        }
        if (array_key_exists('o70_esferaorcamentaria', $state)) {
            $self->setEsferaOrcamentaria($state['o70_esferaorcamentaria']);
        }
        return $self;
    }
}
