<?php


namespace ECidade\Configuracao\Cadastro\Model;

class Modulo
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $nome;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $imagem;
    /**
     * @var boolean
     */
    private $possuiExercicio;
    /**
     * @var string
     */
    private $nomeManual;

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
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
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
    public function getImagem()
    {
        return $this->imagem;
    }

    /**
     * @param string $imagem
     */
    public function setImagem($imagem)
    {
        $this->imagem = $imagem;
    }

    /**
     * @return bool
     */
    public function getPossuiExercicio()
    {
        return $this->possuiExercicio;
    }

    /**
     * @param bool $possuiExercicio
     */
    public function setPossuiExercicio($possuiExercicio)
    {
        $this->possuiExercicio = $possuiExercicio;
    }

    /**
     * @return string
     */
    public function getNomeManual()
    {
        return $this->nomeManual;
    }

    /**
     * @param string $nomeManual
     */
    public function setNomeManual($nomeManual)
    {
        $this->nomeManual = $nomeManual;
    }

    /**
     * @param array $state
     * @return Modulo
     */
    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('id_item', $state)) {
            $self->setId($state['id_item']);
        }
        if (array_key_exists('nome_modulo', $state)) {
            $self->setNome($state['nome_modulo']);
        }
        if (array_key_exists('descr_modulo', $state)) {
            $self->setDescricao($state['descr_modulo']);
        }
        if (array_key_exists('imagem', $state)) {
            $self->setImagem($state['imagem']);
        }
        if (array_key_exists('temexerc', $state)) {
            $self->setPossuiExercicio($state['temexerc']);
        }
        if (array_key_exists('nome_manual', $state)) {
            $self->setNomeManual($state['nome_manual']);
        }

        return $self;
    }
}
