<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use App\Domain\Patrimonial\Protocolo\Model\TipoProcesso;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Processo
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int p58_codproc
 * @property int p58_codigo
 * @property Date p58_dtproc
 * @property int p58_id_usuario
 * @property int p58_numcgm
 * @property string p58_requer
 * @property int p58_coddepto
 * @property int p58_codandam
 * @property string p58_obs
 * @property string p58_despacho
 * @property string p58_hora
 * @property boolean p58_interno
 * @property boolean p58_publico
 * @property int p58_instit
 * @property string p58_numero
 * @property int p58_ano
 * @property int p58_tipoprocesso
 * @property TipoProcesso $tipoProcesso
 */
class Processo extends Model
{
    const TIPO_PROCESSO_MANUAL = 1;
    const TIPO_PROCESSO_ELETRONICO = 2;
    const TIPO_PROCESSO_OUVIDORIA = 3;

    protected $table = 'protocolo.protprocesso';
    protected $primaryKey = 'p58_codproc';
    public $timestamps = false;

    /**
     * @param int $p58_codproc
     * @return this
     */
    public function setCodigoProcesso($p58_codproc)
    {
        $this->p58_codproc = $p58_codproc;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoProcesso()
    {
        return $this->p58_codproc;
    }

    /**
     * @param int $p58_codigo
     * @return this
     */
    public function setCodigo($p58_codigo)
    {
        $this->p58_codigo = $p58_codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->p58_codigo;
    }

    /**
     * @param DBDate $p58_dtproc
     * @return this
     */
    public function setData($p58_dtproc)
    {
        $this->p58_dtproc = $p58_dtproc;
        return $this;
    }

    /**
     * @return DBDate
     */
    public function getData()
    {
        return $this->p58_dtproc;
    }

    /**
     * @param int $p58_id_usuario
     * @return this
     */
    public function setUsuario($p58_id_usuario)
    {
        $this->p58_id_usuario = $p58_id_usuario;
        return $this;
    }

    /**
     * @return int
     */
    public function getUsuario()
    {
        return $this->p58_id_usuario;
    }

    /**
     * @param int $p58_numcgm
     * @return this
     */
    public function setCgm($p58_numcgm)
    {
        $this->p58_numcgm = $p58_numcgm;
        return $this;
    }

    /**
     * @return int
     */
    public function getCgm()
    {
        return $this->p58_numcgm;
    }

    /**
     * @param int $p58_requer
     * @return this
     */
    public function setRequerente($p58_requer)
    {
        $this->p58_requer = $p58_requer;
        return $this;
    }

    /**
     * @return int
     */
    public function getRequerente()
    {
        return $this->p58_requer;
    }

    /**
     * @param int $p58_coddepto
     * @return this
     */
    public function setDepartamento($p58_coddepto)
    {
        $this->p58_coddepto = $p58_coddepto;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepartamento()
    {
        return $this->p58_coddepto;
    }

    /**
     * @param int $p58_codandam
     * @return this
     */
    public function setCodigoAndamento($p58_codandam)
    {
        $this->p58_codandam = $p58_codandam;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoAndamento()
    {
        return $this->p58_codandam;
    }

    /**
     * @param int $p58_obs
     * @return this
     */
    public function setObservacao($p58_obs)
    {
        $this->p58_obs = $p58_obs;
        return $this;
    }

    /**
     * @return int
     */
    public function getObservacao()
    {
        return $this->p58_obs;
    }

    /**
     * @param int $p58_despacho
     * @return this
     */
    public function setDespacho($p58_despacho)
    {
        $this->p58_despacho = $p58_despacho;
        return $this;
    }

    /**
     * @return int
     */
    public function getDespacho()
    {
        return $this->p58_despacho;
    }

    /**
     * @param int $p58_hora
     * @return this
     */
    public function setHora($p58_hora)
    {
        $this->p58_hora = $p58_hora;
        return $this;
    }

    /**
     * @return int
     */
    public function getHora()
    {
        return $this->p58_hora;
    }

    /**
     * @param boolean $p58_interno
     * @return this
     */
    public function setInterno($p58_interno)
    {
        $this->p58_interno = $p58_interno;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getInterno()
    {
        return $this->p58_interno;
    }

    /**
     * @param boolean $p58_publico
     * @return this
     */
    public function setPublico($p58_publico)
    {
        $this->p58_publico = $p58_publico;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getPublico()
    {
        return $this->p58_publico;
    }

    /**
     * @param boolean $p58_instit
     * @return this
     */
    public function setInstituicao($p58_instit)
    {
        $this->p58_instit = $p58_instit;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getInstituicao()
    {
        return $this->p58_instit;
    }

    /**
     * @param boolean $p58_numero
     * @return this
     */
    public function setNumero($p58_numero)
    {
        $this->p58_numero = $p58_numero;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getNumero()
    {
        return $this->p58_numero;
    }

    /**
     * @param boolean $p58_ano
     * @return this
     */
    public function setAno($p58_ano)
    {
        $this->p58_ano = $p58_ano;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getAno()
    {
        return $this->p58_ano;
    }

    /**
     * @param integer tipoprocesso
     * @return this
     */
    public function setTipoProcesso($p58_tipoprocesso)
    {
        $this->p58_tipoprocesso = $p58_tipoprocesso;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTipoProcesso()
    {
        return $this->p58_tipoprocesso;
    }

    public function tipoProcesso()
    {
        return $this->belongsTo(TipoProcesso::class, 'p58_codigo', 'p51_codigo');
    }
}
