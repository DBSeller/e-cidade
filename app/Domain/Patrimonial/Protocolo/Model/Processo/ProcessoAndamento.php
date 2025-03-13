<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transferencia
 *
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int p61_codandam
 * @property int p61_codproc
 * @property int p61_id_usuario
 * @property date p61_dtandam
 * @property string p61_despacho
 * @property int p61_coddepto
 * @property boolean p61_publico
 * @property string p61_hora
 */
class ProcessoAndamento extends Model
{
    protected $table = 'protocolo.procandam';
    protected $primaryKey = 'p61_codandam';
    public $timestamps = false;

    /**
     * @param int $p61_codandam
     * @return this
     */
    public function setCodigo($p61_codandam)
    {
        $this->p61_codandam = $p61_codandam;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->p61_codandam;
    }

    /**
     * @param int $p61_codproc
     * @return this
     */
    public function setProcesso($p61_codproc)
    {
        $this->p61_codproc = $p61_codproc;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcesso()
    {
        return $this->p61_codproc;
    }

    /**
     * @param int $p61_id_usuario
     * @return this
     */
    public function setUsuario($p61_id_usuario)
    {
        $this->p61_id_usuario = $p61_id_usuario;
        return $this;
    }

    /**
     * @return int
     */
    public function getUsuario()
    {
        return $this->p61_id_usuario;
    }

    /**
     * @param date $p61_dtandam
     * @return this
     */
    public function setData($p61_dtandam)
    {
        $this->p61_dtandam = $p61_dtandam;
        return $this;
    }

    /**
     * @return date
     */
    public function getData()
    {
        return $this->p61_dtandam;
    }

    /**
     * @param string $p61_despacho
     * @return this
     */
    public function setDespacho($p61_despacho)
    {
        $this->p61_despacho = $p61_despacho;
        return $this;
    }

    /**
     * @return string
     */
    public function getDespacho()
    {
        return $this->p61_despacho;
    }

    /**
     * @param int $p61_coddepto
     * @return this
     */
    public function setDepartamento($p61_coddepto)
    {
        $this->p61_coddepto = $p61_coddepto;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepartamento()
    {
        return $this->p61_coddepto;
    }

    /**
     * @param boolean $p61_publico
     * @return this
     */
    public function setPublico($p61_publico)
    {
        $this->p61_publico = $p61_publico;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublico()
    {
        return $this->p61_publico;
    }

    /**
     * @param string $p61_hora
     * @return this
     */
    public function setHora($p61_hora)
    {
        $this->p61_hora = $p61_hora;
        return $this;
    }

    /**
     * @return string
     */
    public function getHora()
    {
        return $this->p61_hora;
    }
}
