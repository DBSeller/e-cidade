<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo\AndamentoPadrao;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CamposDinamicos
 * @package App\Domain\Patrimonial\Protocolo\Model\AndamentoPadrao
 * @property int  p111_sequencial
 * @property int  p111_camposandpadrao
 * @property int  p111_codandam
 * @property int  p111_codcam
 * @property text p111_resposta
 */
class CamposDinamicosResposta extends Model
{
    protected $table = 'protocolo.camposandpadraoresposta';
    protected $primaryKey = 'p111_sequencial';
    public $timestamps = false;

    /**
     * @param int $p111_sequencial
     * @return this
     */
    public function setCodigo($p111_sequencial)
    {
        $this->p111_sequencial = $p111_sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->p111_sequencial;
    }

    /**
     * @param int $p111_camposandpadrao
     * @return int
     */
    public function setCamposandpadrao($p111_camposandpadrao)
    {
        $this->p111_camposandpadrao = $p111_camposandpadrao;
        return $this;
    }

    /**
     * @return int
     */
    public function getCamposandpadrao()
    {
        return $this->p111_camposandpadrao;
    }

    /**
     * @param int $p111_codandam
     * @return int
     */
    public function setCodandam($p111_codandam)
    {
        $this->p111_codandam = $p111_codandam;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodandam()
    {
        return $this->p111_codandam;
    }

    /**
     * @param int p111_codcam
     * @return this
     */
    public function setCodcam($p111_codcam)
    {
        $this->p111_codcam = $p111_codcam;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getCodcam()
    {
        return $this->p111_codcam;
    }

    /**
     * @param bool p111_resposta
     * @return this
     */
    public function setResposta($p111_resposta)
    {
        $this->p111_resposta = $p111_resposta;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getResposta()
    {
        return $this->p111_resposta;
    }
}
