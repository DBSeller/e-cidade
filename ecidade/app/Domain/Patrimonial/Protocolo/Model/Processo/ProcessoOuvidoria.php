<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcessoOuvidoria
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 *
 * @property int ov09_sequencial
 * @property int ov09_protprocesso
 * @property int ov09_ouvidoriaatendimento
 * @property boolean ov09_principal
 */
class ProcessoOuvidoria extends Model
{
    protected $table = 'ouvidoria.processoouvidoria';
    protected $primaryKey = 'ov09_sequencial';
    public $timestamps = false;

    /**
     * @param int $ov09_sequencial
     * @return this
     */
    public function setCodigo($ov09_sequencial)
    {
        $this->ov09_sequencial = $ov09_sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->ov09_sequencial;
    }

    /**
     * @param int $ov09_protprocesso
     * @return this
     */
    public function setProcesso($ov09_protprocesso)
    {
        $this->ov09_protprocesso = $ov09_protprocesso;
        return $this;
    }

    /**
     * @return int
     */
    public function getProcesso()
    {
        return $this->ov09_protprocesso;
    }

    /**
     * @param int $ov09_ouvidoriaatendimento
     * @return this
     */
    public function setAtendimento($ov09_ouvidoriaatendimento)
    {
        $this->ov09_ouvidoriaatendimento = $ov09_ouvidoriaatendimento;
        return $this;
    }

    /**
     * @return int
     */
    public function getAtendimento()
    {
        return $this->ov09_ouvidoriaatendimento;
    }

    /**
     * @param Boolean $ov09_principal
     * @return this
     */
    public function setPrincipal($ov09_principal)
    {
        $this->ov09_principal = $ov09_principal;
        return $this;
    }

    /**
     * @return Boolean
     */
    public function getPrincipal()
    {
        return $this->ov09_principal;
    }
}
