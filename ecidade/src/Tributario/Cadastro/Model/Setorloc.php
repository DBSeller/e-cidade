<?php

namespace ECidade\Tributario\Cadastro\Model;

class Setorloc
{
    /**
     * @var int
     */
    private $codigo;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var string
     */
    private $codigoProprio;

    /**
     * @var int
     */
    private $idbql;

    /**
     * @var string
     */
    private $quadra;

    /**
     * @var string
     */
    private $lote;

    public static function fromState(array $state)
    {
        $setorloc = new Setorloc();

        if (array_key_exists('j05_codigo', $state)) {
            $setorloc->setCodigo((int)$state['j05_codigo']);
        }
        if (array_key_exists('j05_descr', $state)) {
            $setorloc->setDescricao($state['j05_descr']);
        }
        if (array_key_exists('j05_codigoproprio', $state)) {
            $setorloc->setCodigoProprio($state['j05_codigoproprio']);
        }
        if (array_key_exists('j06_idbql', $state)) {
            $setorloc->setIdbql((int)$state['j06_idbql']);
        }
        if (array_key_exists('j06_quadraloc', $state)) {
            $setorloc->setQuadra($state['j06_quadraloc']);
        }
        if (array_key_exists('j06_lote', $state)) {
            $setorloc->setLote($state['j06_lote']);
        }

        return $setorloc;
    }

    /**
     * @return  string
     */
    public function getCodigoProprio()
    {
        return $this->codigoProprio;
    }

    /**
     * @param   string  $codigoProprio  j05_codigoproprio
     */
    public function setCodigoProprio($codigoProprio)
    {
        $this->codigoProprio = $codigoProprio;
    }

    /**
     * @return  string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param   string  $descricao  j05_descr
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     * @return  int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param   int  $codigo  j05_codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return  string
     */
    public function getQuadra()
    {
        return $this->quadra;
    }

    /**
     * @param   string  $quadra
     */
    public function setQuadra($quadra)
    {
        $this->quadra = $quadra;
    }

    /**
     * @return  int
     */
    public function getIdbql()
    {
        return $this->idbql;
    }

    /**
     * @param   int  $idbql
     */
    public function setIdbql($idbql)
    {
        $this->idbql = $idbql;
    }

    /**
     * @return  string
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * @param   string  $lote
     */
    public function setLote($lote)
    {
        $this->lote = $lote;
    }
}
