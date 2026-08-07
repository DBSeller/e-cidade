<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 20/03/18
 * Time: 15:23
 */

namespace ECidade\Tributario\Cadastro\Iptu\Recadastramento\Entity;


class HistoricoOcorrenciaMatricula
{

    /**
     * @var  $sequencial
     */
    private $sequencial;

    /**
     * @var $matric
     */
    private $matric;

    /**
     * @var $histocorrencia
     */
    private $histocorrencia;

    /**
     * @return mixed
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param mixed $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return mixed
     */
    public function getMatric()
    {
        return $this->matric;
    }

    /**
     * @param mixed $matric
     */
    public function setMatric($matric)
    {
        $this->matric = $matric;
    }

    /**
     * @return mixed
     */
    public function getHistocorrencia()
    {
        return $this->histocorrencia;
    }

    /**
     * @param mixed $histocorrencia
     */
    public function setHistocorrencia($histocorrencia)
    {
        $this->histocorrencia = $histocorrencia;
    }

}