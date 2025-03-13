<?php

namespace ECidade\Tributario\Divida\Model;

use DateTime;
use Instituicao;

class Inicial
{
    /**
     * @var integer
     */
    private $inicial;
    /**
     * @var integer
     */
    private $advogado;
    /**
     * @var integer
     */
    private $data;
    /**
     * @var integer
     */
    private $login;
    /**
     * @var integer
     */
    private $codlocal;
    /**
     * @var integer
     */
    private $codmov;
    /**
     * @var integer
     */
    private $instituicao;
    /**
     * @var integer
     */
    private $situacao;

    /**
     * @return int
     */
    public function getInicial()
    {
        return $this->inicial;
    }

    /**
     * @return int
     */
    public function getAdvogado()
    {
        return $this->advogado;
    }

    /**
     * @return Date
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return int
     */
    public function getCodlocal()
    {
        return $this->codlocal;
    }

    /**
     * @return int
     */
    public function getCodmov()
    {
        return $this->codmov;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->instituicao;
    }

    /**
     * @return int
     */
    public function getSituacao()
    {
        return $this->situacao;
    }

    //SET

    /**
     * @param int Inicial
     */
    public function setInicial($inicial)
    {
        $this->inicial = $inicial;
    }

    /**
     * @param int Advogado
     */
    public function setAdvogado($advogado)
    {
        $this->advogado = $advogado;
    }

    /**
     * @param date Data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param int Login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @param int Codlocal
     */
    public function setCodlocal($codlocal)
    {
        $this->codlocal = $codlocal;
    }

    /**
     * @param int Codmov
     */
    public function setCodmov($codmov)
    {
        $this->codmov = $codmov;
    }

    /**
     * @param int Instituicao
     */
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    /**
     * @param int Situacao
     */
    public function setSituacao($situacao)
    {
        $this->situacao = $situacao;
    }

    /**
     * @param  $state
     * @return Diversos
     * @throws \Exception
     */
    public static function fromState($state)
    {
        $self = new self();
        if (array_key_exists('v50_inicial', $state)) {
            $self->setInicial($state['v50_inicial']);
        }

        if (array_key_exists('v50_advog', $state)) {
            $self->setAdvogado($state['v50_advog']);
        }

        if (array_key_exists('v50_data', $state)) {
            $dataInscricao = new DateTime($state['v50_data']);
            $self->setData($dataInscricao);
        }

        if (array_key_exists('v50_id_login', $state)) {
            $self->setLogin($state['v50_id_login']);
        }

        if (array_key_exists('v50_codlocal', $state)) {
            $self->setCodlocal($state['v50_codlocal']);
        }

        if (array_key_exists('v50_codmov', $state)) {
            $self->setCodmov($state['v50_codmov']);
        }


        if (array_key_exists('v50_instit', $state)) {
            $instituicao = \InstituicaoRepository::getInstituicaoByCodigo($state['v50_instit']);
            $self->setInstituicao($instituicao);
        }

        if (array_key_exists('v50_situacao', $state)) {
            $self->setSituacao($state['v50_situacao']);
        }

        return $self;
    }
}
