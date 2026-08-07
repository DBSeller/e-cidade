<?php

namespace ECidade\Patrimonial\Protocolo\Modelo;

use DateTime;

/**
 * Class AndamentoProcessoInterno
 * @package ECidade\Patrimonial\Protocolo\Modelo
 */
class AndamentoProcessoInterno
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $idAndamento;

    /**
     * @var int
     */
    private $idUsuario;

    /**
     * @var DateTime
     */
    private $data;

    /**
     * @var string
     */
    private $hora;

    /**
     * @var boolean
     */
    private $publico;

    /**
     * @var string
     */
    private $despacho;

    /**
     * @var boolean
     */
    private $transitoInterno;

    /**
     * @var int
     */
    private $idTipoDespacho;

    /**
     * @param string $codigoAutorizacao
     */
    public function __construct($id = null)
    {
        if ($id) {
            $dao = db_utils::getDao('procandamint');
            $sql = $dao->sql_query_file($id);

            $rs = $dao->sql_record($sql);

            $this::fromState($rs);
        }
    }

    /**
     * Get the value of id
     *
     * @return  int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  int  $id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of idProcesso
     *
     * @return  int
     */
    public function getIdAndamento()
    {
        return $this->idAndamento;
    }

    /**
     * Set the value of idProcesso
     *
     * @param  int  $idProcesso
     *
     * @return  self
     */
    public function setIdAndamento($idAndamento)
    {
        $this->idAndamento = $idAndamento;

        return $this;
    }

    /**
     * Get the value of idUsuario
     *
     * @return  int
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     *
     * @param  int  $idUsuario
     *
     * @return  self
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of data
     *
     * @return  Date
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the value of data
     *
     * @param  Date  $data
     *
     * @return  self
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get the value of hora
     *
     * @return  string
     */
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * Set the value of hora
     *
     * @param  string  $hora
     *
     * @return  self
     */
    public function setHora($hora)
    {
        $this->hora = $hora;

        return $this;
    }

    /**
     * Get the value of publico
     *
     * @return  boolean
     */
    public function isPublico()
    {
        return $this->publico;
    }

    /**
     * Set the value of publico
     *
     * @param  boolean  $publico
     *
     * @return  self
     */
    public function setPublico($publico)
    {
        $this->publico = $publico;

        return $this;
    }

    /**
     * Get the value of despacho
     *
     * @return  string
     */
    public function getDespacho()
    {
        return $this->despacho;
    }

    /**
     * Set the value of despacho
     *
     * @param  string  $despacho
     *
     * @return  self
     */
    public function setDespacho($despacho)
    {
        $this->despacho = $despacho;

        return $this;
    }

    /**
     * Get the value of isTransitoInterno
     *
     * @return  boolean
     */
    public function isTransitoInterno()
    {
        return $this->transitoInterno;
    }

    /**
     * Set the value of isTransitoInterno
     *
     * @param  boolean  $isTransitoInterno
     *
     * @return  self
     */
    public function setTransitoInterno($TransitoInterno)
    {
        $this->transitoInterno = $TransitoInterno;

        return $this;
    }

    /**
     * Get the value of idTipoDespacho
     *
     * @return  int
     */
    public function getIdTipoDespacho()
    {
        return $this->idTipoDespacho;
    }

    /**
     * Set the value of idTipoDespacho
     *
     * @param  int  $idTipoDespacho
     *
     * @return  self
     */
    public function setIdTipoDespacho($idTipoDespacho)
    {
        $this->idTipoDespacho = $idTipoDespacho;

        return $this;
    }

    /**
     * fromState
     *
     * @param  array $state
     * @return AndamentoProcessoInterno
     */
    public function fromState($state)
    {
        $andamentoInterno = new self();

        if (array_key_exists('p78_sequencial', $state)) {
            $andamentoInterno->setId((int)$state['p78_sequencial']);
        }

        if (array_key_exists('p78_codandam', $state)) {
            $andamentoInterno->setIdAndamento((int)$state['p78_codandam']);
        }

        if (array_key_exists('p78_data', $state)) {
            $andamentoInterno->setData(new DateTime($state['p78_data']));
        }

        if (array_key_exists('p78_hora', $state)) {
            $andamentoInterno->setHora($state['p78_hora']);
        }

        if (array_key_exists('p78_usuario', $state)) {
            $andamentoInterno->setIdUsuario((int)$state['p78_usuario']);
        }

        if (array_key_exists('p78_despacho', $state)) {
            $andamentoInterno->setDespacho((int)$state['p78_despacho']);
        }

        if (array_key_exists('p78_publico', $state)) {
            $andamentoInterno->setPublico((boolean)$state['p78_publico']);
        }

        if (array_key_exists('p78_transint', $state)) {
            $andamentoInterno->setTransitoInterno((boolean)$state['p78_transint']);
        }

        if (array_key_exists('p78_tipodespacho', $state)) {
            $andamentoInterno->setIdTipoDespacho((int)$state['p78_tipodespacho']);
        }

        return $andamentoInterno;
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'p78_sequencial' => $this->getId(),
            'p78_codandam' => $this->getIdAndamento(),
            'p78_data' => !is_null($this->getData()) ? $this->getData()->format('Y-m-d') : null,
            'p78_hora' => $this->getHora,
            'p78_usuario' => $this->getIdUsuario(),
            'p78_despacho' => $this->getDespacho(),
            'p78_publico' => $this->isPublico(),
            'p78_transint' => $this->isTransitoInterno(),
            'p78_tipodespacho' => $this->getIdTipoDespacho()
        );
    }
}
