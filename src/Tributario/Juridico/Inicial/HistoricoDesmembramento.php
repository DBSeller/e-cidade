<?php

namespace ECidade\Tributario\Juridico\Inicial;

use DateTime;

/**
 * Class HistoricoDesmembramento
 * @package ECidade\Tributario\Juridico\Inicial
 */
class HistoricoDesmembramento
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var int
     */
    private $inicialOld;
    /**
     * @var int
     */
    private $inicial;
    /**
     * @var int
     */
    private $cdaOld;
    /**
     * @var int
     */
    private $cda;
    /**
     * @var DateTime
     */
    private $data;
    /**
     * @var int
     */
    private $usuario;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
    }

    /**
     * @return int
     */
    public function getInicialOld()
    {
        return (int)$this->inicialOld;
    }

    /**
     * @param int $inicialOld
     */
    public function setInicialOld($inicialOld)
    {
        $this->inicialOld = (int)$inicialOld;
    }

    /**
     * @return int
     */
    public function getInicial()
    {
        return (int)$this->inicial;
    }

    /**
     * @param int $inicial
     */
    public function setInicial($inicial)
    {
        $this->inicial = (int)$inicial;
    }

    /**
     * @return int
     */
    public function getCdaOld()
    {
        return (int)$this->cdaOld;
    }

    /**
     * @param int $cdaOld
     */
    public function setCdaOld($cdaOld)
    {
        $this->cdaOld = (int)$cdaOld;
    }

    /**
     * @return int
     */
    public function getCda()
    {
        return (int)$this->cda;
    }

    /**
     * @param int $cda
     */
    public function setCda($cda)
    {
        $this->cda = (int)$cda;
    }

    /**
     * @return DateTime
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $data
     */
    public function setData($data)
    {
        $this->data = new DateTime($data);
    }

    /**
     * @return int
     */
    public function getUsuario()
    {
        return (int)$this->usuario;
    }

    /**
     * @param int $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = (int)$usuario;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $entidade = array(
            'v37_inicial_old' => $this->inicialOld,
            'v37_inicial' => $this->inicial,
            'v37_cda_old' => $this->cdaOld,
            'v37_cda' => $this->cda,
            'v37_usuario' => $this->usuario
        );

        if ($this->sequencial) {
            $entidade['v37_sequencial'] = $this->sequencial;
        }

        if ($this->data) {
            $entidade['v37_data'] = $this->data->format('Y-m-d');
        }

        return $entidade;
    }
}
