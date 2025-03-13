<?php
namespace ECidade\Configuracao\Opcao\Model;

use test\Mockery\ProxyMockingTest;

class Opcao
{


    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $nome;

    /**
     * @var $ano
     */
    protected $ano;

    /**
     * @var mixed
     */
    protected $valor;

    /**
     * @var integer
     */
    protected $instituicao;


    public function __construct()
    {
    }

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
     * @return mixed
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param mixed $ano
     */
    public function setAno($ano = null)
    {
        $this->ano = $ano;
    }

    /**
     * @return mixed
     */
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * @param mixed $valor
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @param int $instituicao
     */
    public function setInstituicao($instituicao = null)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->valor;
    }
}
