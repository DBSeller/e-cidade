<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 14/06/18
 * Time: 17:29
 */

namespace ECidade\Tributario\Juridico\ProcessoEletronico;


/**
 * Documento de um processo eletronico
 * Class Documento
 * @package ECidade\Tributario\Juridico\ProcessoEletronico
 */
class Documento
{

    const INICIAL = 1;

    const MANDADO_CITACAO = 2;

    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var string
     */
    private $conteudo;

    /**
     * @var string
     */
    private $caminho;

    /**
     * @var \DateTime
     */
    private $data;

    /**
     * @var string
     */
    private $nome;

    /**
     * Tipo do Documento
     * @var integer
     */
    private $tipo;

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return string
     */
    public function getConteudo()
    {
        return $this->conteudo;
    }

    /**
     * @param string $conteudo
     */
    public function setConteudo($conteudo)
    {
        $this->conteudo = $conteudo;
    }

    /**
     * @return string
     */
    public function getCaminho()
    {
        return $this->caminho;
    }

    /**
     * @param string $caminho
     */
    public function setCaminho($caminho)
    {
        $this->caminho = $caminho;
    }

    /**
     * @return \DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param \DateTime $data
     */
    public function setData($data)
    {
        $this->data = $data;
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
     * @return int
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param int $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }




}