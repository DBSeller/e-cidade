<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo\AndamentoPadrao;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CamposDinamicos
 * @package App\Domain\Patrimonial\Protocolo\Model\AndamentoPadrao
 * @property int p110_sequencial
 * @property int p110_andpadrao_codigo
 * @property int p110_andpadrao_ordem
 * @property int p110_codcam
 * @property bool p110_obrigatorio
 */
class CamposDinamicos extends Model
{
    protected $table = 'protocolo.camposandpadrao';
    protected $primaryKey = 'p110_sequencial';
    public $timestamps = false;

    /**
     * @param int $p110_sequencial
     * @return this
     */
    public function setCodigo($p110_sequencial)
    {
        $this->p110_sequencial = $p110_sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->p110_sequencial;
    }


    /**
     * @param int p110_andpadrao_codigo
     * @return this
     */
    public function setAndpadraoCodigo($p110_andpadrao_codigo)
    {
        $this->p110_andpadrao_codigo = $p110_andpadrao_codigo;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getAndpadraoCodigo()
    {
        return $this->p110_andpadrao_codigo;
    }

    /**
     * @param int p110_andpadrao_ordem
     * @return this
     */
    public function setAndpadraoOrdem($p110_andpadrao_ordem)
    {
        $this->p110_andpadrao_ordem = $p110_andpadrao_ordem;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getAndpadraoOrdem()
    {
        return $this->p110_andpadrao_ordem;
    }

    /**
     * @param int p110_codcam
     * @return this
     */
    public function setCodcam($p110_codcam)
    {
        $this->p110_codcam = $p110_codcam;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getCodcam()
    {
        return $this->p110_codcam;
    }

    /**
     * @param bool p110_obrigatorio
     * @return this
     */
    public function setObrigatorio($p110_obrigatorio)
    {
        $this->p110_obrigatorio = $p110_obrigatorio;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getObrigatorio()
    {
        return $this->p110_obrigatorio;
    }
}
