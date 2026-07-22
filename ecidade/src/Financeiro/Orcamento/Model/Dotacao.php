<?php


namespace ECidade\Financeiro\Orcamento\Model;

class Dotacao
{
    private $ano;
    private $codigoDotacao;
    private $idOrgao;
    private $idUnidade;
    private $idSubfuncao;
    private $idProjeto;
    private $idRecurso;
    private $idFuncao;
    private $idPrograma;
    private $idElemento;
    private $valor;
    private $idInstituicao;
    private $localizadorGastos;
    private $caracteristicaPeculiar;
    private $esferaOrcamentaria;
    private $dataCriacao;

    /**
     * @return integer
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param integer $ano
     * @return Dotacao
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCodigoDotacao()
    {
        return $this->codigoDotacao;
    }

    /**
     * @param integer $codigoDotacao
     * @return Dotacao
     */
    public function setCodigoDotacao($codigoDotacao)
    {
        $this->codigoDotacao = $codigoDotacao;
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
     * @return Dotacao
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
     * @return Dotacao
     */
    public function setIdUnidade($idUnidade)
    {
        $this->idUnidade = $idUnidade;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdSubfuncao()
    {
        return $this->idSubfuncao;
    }

    /**
     * @param integer $idSubfuncao
     * @return Dotacao
     */
    public function setIdSubfuncao($idSubfuncao)
    {
        $this->idSubfuncao = $idSubfuncao;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdProjeto()
    {
        return $this->idProjeto;
    }

    /**
     * @param integer $idProjeto
     * @return Dotacao
     */
    public function setIdProjeto($idProjeto)
    {
        $this->idProjeto = $idProjeto;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdRecurso()
    {
        return $this->idRecurso;
    }

    /**
     * @param integer $idRecurso
     * @return Dotacao
     */
    public function setIdRecurso($idRecurso)
    {
        $this->idRecurso = $idRecurso;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdFuncao()
    {
        return $this->idFuncao;
    }

    /**
     * @param integer $idFuncao
     * @return Dotacao
     */
    public function setIdFuncao($idFuncao)
    {
        $this->idFuncao = $idFuncao;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdPrograma()
    {
        return $this->idPrograma;
    }

    /**
     * @param integer $idPrograma
     * @return Dotacao
     */
    public function setIdPrograma($idPrograma)
    {
        $this->idPrograma = $idPrograma;
        return $this;
    }

    /**
     * @return integer
     */
    public function getIdElemento()
    {
        return $this->idElemento;
    }

    /**
     * @param integer $idElemento
     * @return Dotacao
     */
    public function setIdElemento($idElemento)
    {
        $this->idElemento = $idElemento;
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
     * @return Dotacao
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
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
     * @return Dotacao
     */
    public function setIdInstituicao($idInstituicao)
    {
        $this->idInstituicao = $idInstituicao;
        return $this;
    }

    /**
     * @return integer
     */
    public function getLocalizadorGastos()
    {
        return $this->localizadorGastos;
    }

    /**
     * @param integer $localizadorGastos
     * @return Dotacao
     */
    public function setLocalizadorGastos($localizadorGastos)
    {
        $this->localizadorGastos = $localizadorGastos;
        return $this;
    }

    /**
     * @return string
     */
    public function getCaracteristicaPeculiar()
    {
        return $this->caracteristicaPeculiar;
    }

    /**
     * @param string $caracteristicaPeculiar
     * @return Dotacao
     */
    public function setCaracteristicaPeculiar($caracteristicaPeculiar)
    {
        $this->caracteristicaPeculiar = $caracteristicaPeculiar;
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
     * @return Dotacao
     */
    public function setEsferaOrcamentaria($esferaOrcamentaria)
    {
        $this->esferaOrcamentaria = $esferaOrcamentaria;
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
     * @return Dotacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
        return $this;
    }

    /**
     * @param array $state
     * @return Dotacao
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('o58_anousu', $state)) {
            $self->setAno($state['o58_anousu']);
        }
        if (array_key_exists('o58_coddot', $state)) {
            $self->setCodigoDotacao($state['o58_coddot']);
        }
        if (array_key_exists('o58_orgao', $state)) {
            $self->setIdOrgao($state['o58_orgao']);
        }
        if (array_key_exists('o58_unidade', $state)) {
            $self->setIdUnidade($state['o58_unidade']);
        }
        if (array_key_exists('o58_subfuncao', $state)) {
            $self->setIdSubfuncao($state['o58_subfuncao']);
        }
        if (array_key_exists('o58_projativ', $state)) {
            $self->setIdProjeto($state['o58_projativ']);
        }
        if (array_key_exists('o58_codigo', $state)) {
            $self->setIdRecurso($state['o58_codigo']);
        }
        if (array_key_exists('o58_funcao', $state)) {
            $self->setIdFuncao($state['o58_funcao']);
        }
        if (array_key_exists('o58_programa', $state)) {
            $self->setIdPrograma($state['o58_programa']);
        }
        if (array_key_exists('o58_codele', $state)) {
            $self->setIdElemento($state['o58_codele']);
        }
        if (array_key_exists('o58_valor', $state)) {
            $self->setValor($state['o58_valor']);
        }
        if (array_key_exists('o58_instit', $state)) {
            $self->setIdInstituicao($state['o58_instit']);
        }
        if (array_key_exists('o58_localizadorgastos', $state)) {
            $self->setLocalizadorGastos($state['o58_localizadorgastos']);
        }
        if (array_key_exists('o58_datacriacao', $state)) {
            $self->setDataCriacao($state['o58_datacriacao']);
        }
        if (array_key_exists('o58_concarpeculiar', $state)) {
            $self->setCaracteristicaPeculiar($state['o58_concarpeculiar']);
        }
        if (array_key_exists('o58_esferaorcamentaria', $state)) {
            $self->setEsferaOrcamentaria($state['o58_esferaorcamentaria']);
        }

        return $self;
    }
}
