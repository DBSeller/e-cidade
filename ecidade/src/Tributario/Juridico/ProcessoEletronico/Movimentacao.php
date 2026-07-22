<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 18/06/18
 * Time: 16:25
 */

namespace ECidade\Tributario\Juridico\ProcessoEletronico;


class Movimentacao
{

    /**
     * @var \DateTime
     */
    protected $data;

    /**
     * @var string
     */
    protected $texto;

    /**
     * @var string
     */
    protected $protocolo;

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
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * @param string $texto
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;
    }

    /**
     * @return string
     */
    public function getProtocolo()
    {
        return $this->protocolo;
    }

    /**
     * @param string $protocolo
     */
    public function setProtocolo($protocolo)
    {
        $this->protocolo = $protocolo;
    }




}