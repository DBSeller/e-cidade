<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArquivamentoAndamento
 *
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int p69_codarquiv
 * @property int p69_codandam
 * @property boolean p69_arquivado
 */
class ArquivamentoAndamento extends Model
{
    protected $table = 'protocolo.arqandam';
    protected $primaryKey = 'p69_codarquiv';
    public $timestamps = false;

    /**
     * @param int $p69_codarquiv
     * @return this
     */
    public function setArquivamento($p69_codarquiv)
    {
        $this->p69_codarquiv = $p69_codarquiv;
        return $this;
    }

    /**
     * @return int
     */
    public function getArquivamento()
    {
        return $this->p69_codarquiv;
    }

    /**
     * @param int $p69_codandam
     * @return this
     */
    public function setAndamento($p69_codandam)
    {
        $this->p69_codandam = $p69_codandam;
        return $this;
    }

    /**
     * @return int
     */
    public function getAndamento()
    {
        return $this->p69_codandam;
    }

    /**
     * @param boolean $p69_arquivado
     * @return this
     */
    public function setArquivado($p69_arquivado)
    {
        $this->p69_arquivado = $p69_arquivado;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getArquivado()
    {
        return $this->p69_arquivado;
    }
}
