<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Arquivamento
 *
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int p67_codproc
 * @property Date p67_dtarq
 * @property text p67_historico
 * @property int p67_id_usuario
 * @property int p67_coddepto
 * @property int p67_codarquiv
 */
class Arquivamento extends Model
{
    protected $table = 'protocolo.procarquiv';
    protected $primaryKey = 'p67_codarquiv';
    public $timestamps = false;

    /**
     * @param int $p67_codarquiv
     * @return this
     */
    public function setCodigo($p67_codarquiv)
    {
        $this->p67_codarquiv = $p67_codarquiv;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->p67_codarquiv;
    }

    /**
     * @param int $p67_codproc
     * @return this
     */
    public function setProcesso($p67_codproc)
    {
        $this->p67_codproc = $p67_codproc;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcesso()
    {
        return $this->p67_codproc;
    }

    /**
     * @param int $p67_dtarq
     * @return this
     */
    public function setData($p67_dtarq)
    {
        $this->p67_dtarq = $p67_dtarq;
        return $this;
    }

    /**
     * @return int
     */
    public function getData()
    {
        return $this->p67_dtarq;
    }

    /**
     * @param int $p67_historico
     * @return this
     */
    public function setHistorico($p67_historico)
    {
        $this->p67_historico = $p67_historico;
        return $this;
    }

    /**
     * @return int
     */
    public function getHistorico()
    {
        return $this->p67_historico;
    }

    /**
     * @param int $p67_id_usuario
     * @return this
     */
    public function setUsuario($p67_id_usuario)
    {
        $this->p67_id_usuario = $p67_id_usuario;
        return $this;
    }

    /**
     * @return int
     */
    public function getUsuario()
    {
        return $this->p67_id_usuario;
    }

    /**
     * @param int $p67_coddepto
     * @return this
     */
    public function setDepartamento($p67_coddepto)
    {
        $this->p67_coddepto = $p67_coddepto;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepartamento()
    {
        return $this->p67_coddepto;
    }
}
