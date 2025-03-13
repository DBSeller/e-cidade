<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 27/11/17
 * Time: 17:14
 */

namespace ECidade\RecursosHumanos\RH\Efetividade\Model;


class JornadaAlternativa
{

    /**
     * @var \Servidor
     */
    protected $servidor;

    /**
     * @var \DateTime
     */
    protected $data;


    /**
     * @var integer
     */
    Protected $jornada;

    /**
     * @return \Servidor
     */
    public function getServidor()
    {
        return $this->servidor;
    }

    /**
     * @param \Servidor $servidor
     */
    public function setServidor($servidor)
    {
        $this->servidor = $servidor;
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
     * @return int
     */
    public function getJornada()
    {
        return $this->jornada;
    }

    /**
     * @param int $jornada
     */
    public function setJornada($jornada)
    {
        $this->jornada = $jornada;
    }



}