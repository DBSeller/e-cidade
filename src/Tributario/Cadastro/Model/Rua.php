<?php


namespace ECidade\Tributario\Cadastro\Model;

use DateTime;
use ECidade\Tributario\Cadastro\Registry\RuaRegistry;
use ECidade\Tributario\Cadastro\Registry\RuasTipoRegistry;

/**
 * Class Rua
 * @package ECidade\Tributario\Cadastro\Model
 */
class Rua
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
     * @var RuasTipo
     */
    private $tipo;
    /**
     * @var boolean
     */
    private $rural = false;
    /**
     * @var string
     */
    private $lei;
    /**
     * @var DateTime
     */
    private $dataLei;
    /**
     * @var string
     */
    private $bairro;
    /**
     * @var string
     */
    private $observacao;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     * @return Rua
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
     * @return Rua
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return RuasTipo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param RuasTipo $tipo
     * @return Rua
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRural()
    {
        return $this->rural;
    }

    /**
     * @param bool $rural
     * @return Rua
     */
    public function setRural($rural)
    {
        $this->rural = $rural;
        return $this;
    }

    /**
     * @return string
     */
    public function getLei()
    {
        return $this->lei;
    }

    /**
     * @param string $lei
     * @return Rua
     */
    public function setLei($lei)
    {
        $this->lei = $lei;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDataLei()
    {
        return $this->dataLei;
    }

    /**
     * @param DateTime $dataLei
     * @return Rua
     */
    public function setDataLei(DateTime $dataLei)
    {
        $this->dataLei = $dataLei;
        return $this;
    }

    /**
     * @return string
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * @param string $bairro
     * @return Rua
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * @param string $observacao
     * @return Rua
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
        return $this;
    }

    public static function fromState(array $state)
    {
        $self = new self();
        if (array_key_exists('j14_codigo', $state)) {
            $self->setCodigo($state['j14_codigo']);
        }
        if (array_key_exists('j14_nome', $state)) {
            $self->setNome($state['j14_nome']);
        }
        if (array_key_exists('j14_tipo', $state)) {
            $self->setTipo(RuasTipoRegistry::get($state['j14_tipo']));
        }
        if (array_key_exists('j14_rural', $state)) {
            $self->setRural($state['j14_rural'] === 't');
        }
        if (array_key_exists('j14_lei', $state)) {
            $self->setLei($state['j14_lei']);
        }
        if (array_key_exists('j14_dtlei', $state) && !empty($state['j14_dtlei'])) {
            $self->setDataLei(DateTime::createFromFormat('Y-m-d', $state['j14_dtlei']));
        }
        if (array_key_exists('j14_bairro', $state)) {
            $self->setBairro($state['j14_bairro']);
        }
        if (array_key_exists('j14_obs', $state)) {
            $self->setObservacao($state['j14_obs']);
        }

        RuaRegistry::set($self);

        return $self;
    }
}
