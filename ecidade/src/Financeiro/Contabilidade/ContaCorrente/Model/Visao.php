<?php
namespace ECidade\Financeiro\Contabilidade\ContaCorrente\Model;

/**
 * Class Visao
 * @package ECidade\Financeiro\Contabilidade\ContaCorrente\Model
 */
class Visao
{

    /**
     * @var integer
     */
    private $codigo;

    /**
     * @var integer
     */
    private $codigoItemMenu;

    /**
     * @var string
     */
    private $nome;

    /**
     * @var string
     */
    private $filtrosJson;


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
     * @return int
     */
    public function getCodigoItemMenu()
    {
        return $this->codigoItemMenu;
    }

    /**
     * @param int $codigoItemMenu
     */
    public function setCodigoItemMenu($codigoItemMenu)
    {
        $this->codigoItemMenu = $codigoItemMenu;
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
    public function getFiltrosJson()
    {
        return $this->filtrosJson;
    }

    /**
     * @param string $filtros
     */
    public function setFiltrosJson($filtros)
    {
        $this->filtrosJson = $filtros;
    }

    /**
     * @return \stdClass
     */
    public function getFiltros()
    {
        return \JSON::create()->parse($this->filtrosJson);
    }
}
