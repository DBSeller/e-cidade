<?php


namespace ECidade\Educacao\Escola\Model;

use ECidade\Educacao\Escola\Registry\AreaConhecimentoRegistry;
use ECidade\Educacao\Escola\Registry\ComponenteCurricularRegistry;
use Exception;

/**
 * Class ComponenteCurricular
 * @package ECidade\Educacao\Escola\Model
 */
class ComponenteCurricular
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var string
     */
    private $nome;
    /**
     * @var string
     */
    private $sigla;
    /**
     * @var AreaConhecimento
     */
    private $areaConhecimento;
    /*
     * @var string
     */
    private $nomeCompleto;

    /**
     * @var CensoDisciplina[]
     */
    private $censoDisciplina = [];

    /**
     * @var string
     */
    private $corHtml;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return ComponenteCurricular
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
        return $this;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     * @return ComponenteCurricular
     */
    public function setNome($nome)
    {
        $this->nome = trim($nome);
        return $this;
    }

    /**
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * @param string $sigla
     * @return ComponenteCurricular
     */
    public function setSigla($sigla)
    {
        $this->sigla = trim($sigla);
        return $this;
    }

    /**
     * @return AreaConhecimento
     */
    public function getAreaConhecimento()
    {
        return $this->areaConhecimento;
    }

    /**
     * @param AreaConhecimento $areaConhecimento
     * @return ComponenteCurricular
     */
    public function setAreaConhecimento($areaConhecimento)
    {
        $this->areaConhecimento = $areaConhecimento;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomeCompleto()
    {
        return $this->nomeCompleto;
    }

    /**
     * @param mixed $nomeCompleto
     * @return ComponenteCurricular
     */
    public function setNomeCompleto($nomeCompleto)
    {
        $this->nomeCompleto = trim($nomeCompleto);
        return $this;
    }

    /**
     * @return CensoDisciplina[]
     */
    public function getCensoDisciplina()
    {
        return $this->censoDisciplina;
    }

    /**
     * @param CensoDisciplina[] $censoDisciplina
     * @return ComponenteCurricular
     */
    public function setCensoDisciplina($censoDisciplina)
    {
        $this->censoDisciplina = $censoDisciplina;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCorHtml()
    {
        return $this->corHtml;
    }

    /**
     * @param mixed $corHtml
     * @return ComponenteCurricular
     */
    public function setCorHtml($corHtml)
    {
        $this->corHtml = trim($corHtml);
        return $this;
    }

    /**
     * @param array $state
     * @return ComponenteCurricular
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('ed232_i_codigo', $state)) {
            $self->setCodigo($state['ed232_i_codigo']);
        }
        if (array_key_exists('ed232_c_descr', $state)) {
            $self->setNome($state['ed232_c_descr']);
        }
        if (array_key_exists('ed232_c_abrev', $state)) {
            $self->setSigla($state['ed232_c_abrev']);
        }
        if (array_key_exists('ed232_areaconhecimento', $state) && !empty($state['ed232_areaconhecimento'])) {
            $self->setAreaConhecimento(AreaConhecimentoRegistry::get($state['ed232_areaconhecimento']));
        }
        if (array_key_exists('ed232_c_descrcompleta', $state)) {
            $self->setNomeCompleto($state['ed232_c_descrcompleta']);
        }
        if (array_key_exists('ed232_corhtml', $state)) {
            $self->setCorHtml($state['ed232_corhtml']);
        }

        ComponenteCurricularRegistry::set($self);
        return $self;
    }

    /**
     * @param CensoDisciplina $censoDisciplina
     */
    public function addCensoDisciplina(CensoDisciplina $censoDisciplina)
    {
        $this->censoDisciplina[] = $censoDisciplina;
    }
}
