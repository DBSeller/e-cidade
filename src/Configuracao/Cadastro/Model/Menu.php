<?php


namespace ECidade\Configuracao\Cadastro\Model;

class Menu
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $help;
    /**
     * @var boolean
     */
    private $funcao;
    /**
     * @var integer
     */
    private $ativo;
    /**
     * @var integer
     */
    private $manutencao;
    /**
     * @var string
     */
    private $descricaoTecnica;
    /**
     * @var boolean
     */
    private $liberadoCliente;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return string
     */
    public function getHelp()
    {
        return $this->help;
    }

    /**
     * @param string $help
     */
    public function setHelp($help)
    {
        $this->help = $help;
    }

    /**
     * @return bool
     */
    public function isFuncao()
    {
        return $this->funcao;
    }

    /**
     * @param bool $funcao
     */
    public function setFuncao($funcao)
    {
        $this->funcao = $funcao;
    }

    /**
     * @return int
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * @param int $ativo
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    /**
     * @return int
     */
    public function getManutencao()
    {
        return $this->manutencao;
    }

    /**
     * @param int $manutencao
     */
    public function setManutencao($manutencao)
    {
        $this->manutencao = $manutencao;
    }

    /**
     * @return string
     */
    public function getDescricaoTecnica()
    {
        return $this->descricaoTecnica;
    }

    /**
     * @param string $descricaoTecnica
     */
    public function setDescricaoTecnica($descricaoTecnica)
    {
        $this->descricaoTecnica = $descricaoTecnica;
    }

    /**
     * @return bool
     */
    public function isLiberadoCliente()
    {
        return $this->liberadoCliente;
    }

    /**
     * @param bool $liberadoCliente
     */
    public function setLiberadoCliente($liberadoCliente)
    {
        $this->liberadoCliente = $liberadoCliente;
    }

    /**
     * @param array $state
     * @return Menu
     */
    public static function fromState(array $state)
    {
        $self = new self();
        if (isset($state['id_item'])) {
            $self->setId($state['id_item']);
        }
        if (isset($state['descricao'])) {
            $self->setDescricao($state['descricao']);
        }
        if (isset($state['help'])) {
            $self->setHelp($state['help']);
        }
        if (isset($state['funcao'])) {
            $self->setFuncao($state['funcao']);
        }
        if (isset($state['itemativo'])) {
            $self->setAtivo($state['itemativo']);
        }
        if (isset($state['manutencao'])) {
            $self->setManutencao($state['manutencao']);
        }
        if (isset($state['desctec'])) {
            $self->setDescricaoTecnica($state['desctec']);
        }
        if (isset($state['libcliente'])) {
            $self->setLiberadoCliente($state['libcliente'] == 't' ? true : false);
        }

        return $self;
    }
}
