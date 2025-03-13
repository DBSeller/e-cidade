<?php


namespace ECidade\Financeiro\Orcamento\Model;

use ECidade\Financeiro\Orcamento\Registry\ComplementoRegistry;

/**
 * Class Recurso
 * @package ECidade\Financeiro\Orcamento\Model
 */
class Recurso
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $codigoTribunal;
    /**
     * @var string
     */
    private $finalidade;
    /**
     * @var integer
     */
    private $tipo;
    /**
     * @var string
     */
    private $dataLimite;
    /**
     * @var integer
     */
    private $idEstruturaValor;
    /**
     * @var string
     */
    private $siconfi;
    /**
     * @var integer
     */
    private $loaIdentificadorUso;
    /**
     * @var integer
     */
    private $loaTipo;
    /**
     * @var integer
     */
    private $loaGrupo;
    /**
     * @var string
     */
    private $loaEspecificacao;

    /**
     * @var Complemento
     */
    private $complemento;
    /**
     * @var string
     */
    private $recurso;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Recurso
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     * @return Recurso
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigoTribunal()
    {
        return $this->codigoTribunal;
    }

    /**
     * @param string $codigoTribunal
     * @return Recurso
     */
    public function setCodigoTribunal($codigoTribunal)
    {
        $this->codigoTribunal = $codigoTribunal;
        return $this;
    }

    /**
     * @return string
     */
    public function getFinalidade()
    {
        return $this->finalidade;
    }

    /**
     * @param string $finalidade
     * @return Recurso
     */
    public function setFinalidade($finalidade)
    {
        $this->finalidade = $finalidade;
        return $this;
    }

    /**
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param int $tipo
     * @return Recurso
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataLimite()
    {
        return $this->dataLimite;
    }

    /**
     * @param string $dataLimite
     * @return Recurso
     */
    public function setDataLimite($dataLimite)
    {
        $this->dataLimite = $dataLimite;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdEstruturaValor()
    {
        return $this->idEstruturaValor;
    }

    /**
     * @param int $idEstruturaValor
     * @return Recurso
     */
    public function setIdEstruturaValor($idEstruturaValor)
    {
        $this->idEstruturaValor = $idEstruturaValor;
        return $this;
    }

    /**
     * @return string
     */
    public function getSiconfi()
    {
        return $this->siconfi;
    }

    /**
     * @param string $siconfi
     * @return Recurso
     */
    public function setSiconfi($siconfi)
    {
        $this->siconfi = $siconfi;
        return $this;
    }

    /**
     * @return int
     */
    public function getLoaIdentificadorUso()
    {
        return $this->loaIdentificadorUso;
    }

    /**
     * @param int $loaIdentificadorUso
     * @return Recurso
     */
    public function setLoaIdentificadorUso($loaIdentificadorUso)
    {
        $this->loaIdentificadorUso = $loaIdentificadorUso;
        return $this;
    }

    /**
     * @return int
     */
    public function getLoaTipo()
    {
        return $this->loaTipo;
    }

    /**
     * @param int $loaTipo
     * @return Recurso
     */
    public function setLoaTipo($loaTipo)
    {
        $this->loaTipo = $loaTipo;
        return $this;
    }

    /**
     * @return int
     */
    public function getLoaGrupo()
    {
        return $this->loaGrupo;
    }

    /**
     * @param int $loaGrupo
     * @return Recurso
     */
    public function setLoaGrupo($loaGrupo)
    {
        $this->loaGrupo = $loaGrupo;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoaEspecificacao()
    {
        return $this->loaEspecificacao;
    }

    /**
     * @param string $loaEspecificacao
     * @return Recurso
     */
    public function setLoaEspecificacao($loaEspecificacao)
    {
        $this->loaEspecificacao = $loaEspecificacao;
        return $this;
    }

    /**
     * @return Complemento
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param Complemento $complemento
     * @return Recurso
     */
    public function setComplemento(Complemento $complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }


    /**
     * @return string
     */
    public function getRecurso()
    {
        return $this->recurso;
    }

    /**
     * @param string $recurso
     * @return Recurso
     */
    public function setRecurso($recurso)
    {
        $this->recurso = $recurso;
        return $this;
    }

    public static function fromState(array $state)
    {
            $self = new self();
        if (array_key_exists('o15_codigo', $state)) {
            $self->setCodigo($state['o15_codigo']);
        }
        if (array_key_exists('o15_descr', $state)) {
            $self->setDescricao($state['o15_descr']);
        }
        if (array_key_exists('o15_codtri', $state)) {
            $self->setCodigoTribunal($state['o15_codtri']);
        }
        if (array_key_exists('o15_finali', $state)) {
            $self->setFinalidade($state['o15_finali']);
        }
        if (array_key_exists('o15_tipo', $state)) {
            $self->setTipo($state['o15_tipo']);
        }
        if (array_key_exists('o15_datalimite', $state)) {
            $self->setDataLimite($state['o15_datalimite']);
        }
        if (array_key_exists('o15_db_estruturavalor', $state)) {
            $self->setIdEstruturaValor($state['o15_db_estruturavalor']);
        }
        if (array_key_exists('o15_codigosiconfi', $state)) {
            $self->setSiconfi($state['o15_codigosiconfi']);
        }
        if (array_key_exists('o15_loaidentificadoruso', $state)) {
            $self->setLoaIdentificadorUso($state['o15_loaidentificadoruso']);
        }
        if (array_key_exists('o15_loatipo', $state)) {
            $self->setLoaTipo($state['o15_loatipo']);
        }
        if (array_key_exists('o15_loagrupo', $state)) {
            $self->setLoaGrupo($state['o15_loagrupo']);
        }
        if (array_key_exists('o15_loaespecificacao', $state)) {
            $self->setLoaEspecificacao($state['o15_loaespecificacao']);
        }
        if (array_key_exists('o15_complemento', $state)) {
            $self->setComplemento(ComplementoRegistry::get($state['o15_complemento']));
        }
        if (array_key_exists('o15_recurso', $state)) {
            $self->setRecurso($state['o15_recurso']);
        }

        return $self;
    }
}
