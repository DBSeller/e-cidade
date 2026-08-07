<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 23/11/18
 * Time: 10:33
 */

namespace ECidade\Patrimonial\Protocolo\Modelo;


class Transferencia
{
    /**
     * @var integer
     */
    private $codigo;
    /**
     * @var \DateTime
     */
    private $data;
    /**
     * @var string
     */
    private $hora;
    /**
     * @var integer
     */
    private $usuarioEnvio;
    /**
     * @var integer
     */
    private $departamentoEnvio;
    /**
     * @var integer
     */
    private $usuarioRecebimento;
    /**
     * @var integer
     */
    private $departamentoRecebimento;

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
    public function getHora()
    {
        return $this->hora;
    }

    /**
     * @param string $hora
     */
    public function setHora($hora)
    {
        $this->hora = $hora;
    }

    /**
     * @return int
     */
    public function getUsuarioEnvio()
    {
        return $this->usuarioEnvio;
    }

    /**
     * @param int $usuarioEnvio
     */
    public function setUsuarioEnvio($usuarioEnvio)
    {
        $this->usuarioEnvio = $usuarioEnvio;
    }

    /**
     * @return int
     */
    public function getDepartamentoEnvio()
    {
        return $this->departamentoEnvio;
    }

    /**
     * @param int $departamentoEnvio
     */
    public function setDepartamentoEnvio($departamentoEnvio)
    {
        $this->departamentoEnvio = $departamentoEnvio;
    }

    /**
     * @return int
     */
    public function getUsuarioRecebimento()
    {
        return $this->usuarioRecebimento;
    }

    /**
     * @param int $usuarioRecebimento
     */
    public function setUsuarioRecebimento($usuarioRecebimento)
    {
        $this->usuarioRecebimento = $usuarioRecebimento;
    }

    /**
     * @return int
     */
    public function getDepartamentoRecebimento()
    {
        return $this->departamentoRecebimento;
    }

    /**
     * @param int $departamentoRecebimento
     */
    public function setDepartamentoRecebimento($departamentoRecebimento)
    {
        $this->departamentoRecebimento = $departamentoRecebimento;
    }

    public static function fromState(array $state)
    {
        $transferencia = new self();

        $transferencia->setCodigo($state['p62_codtran']);
        $transferencia->setData(new \DateTime($state['p62_dttran']));
        $transferencia->setUsuarioEnvio($state['p62_id_usuario']);
        $transferencia->setDepartamentoEnvio($state['p62_coddepto']);
        $transferencia->setDepartamentoRecebimento($state['p62_id_usorec']);
        $transferencia->setDepartamentoRecebimento($state['p62_coddeptorec']);
        $transferencia->setHora($state['p62_hora']);

        return null;
    }
}
