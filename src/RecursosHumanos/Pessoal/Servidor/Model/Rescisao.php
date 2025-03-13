<?php
namespace ECidade\RecursosHumanos\Pessoal\Servidor\Model;

class Rescisao
{

    /**
     * Servidort rescindido
     * @var \Servidor
     */
    private $servidor;

    /**
     * Competencia da rescisao
     * @var \DBCompetencia
     */
    private $competencia;

    /**
     * Data da rescisao
     * @var \DateTime
     */
    private $data;

    /**
     * Código da Rescisao
     * @var string
     */
    private $codigo;

    /**
     * @return \Servidor
     */
    public function getServidor()
    {
        return $this->servidor;
    }

    /**
     * Servdor rescindido
     * @param \Servidor $servidor
     */
    public function setServidor(\Servidor $servidor)
    {
        $this->servidor = $servidor;
    }

    /**
     * @return \DBCompetencia
     */
    public function getCompetencia()
    {
        return $this->competencia;
    }

    /**
     * @param \DBCompetencia $competencia
     */
    public function setCompetencia(\DBCompetencia $competencia)
    {
        $this->competencia = $competencia;
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
    public function setData(\DateTime $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param string $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }
}
