<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transferencia
 *
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int p64_codandam
 * @property int p64_codtran
 */
class ProcessoTransferenciaAndamento extends Model
{
    protected $table = 'protocolo.proctransand';
    protected $primaryKey = 'p64_codandam';
    // protected $primaryKey = ['p64_codandam', 'p64_codtran'];
    public $timestamps = false;

    /**
     * @param int $p64_codandam
     * @return this
     */
    public function setCodigoAndamento($p64_codandam)
    {
        $this->p64_codandam = $p64_codandam;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoAndamento()
    {
        return $this->p64_codandam;
    }

    /**
     * @param int $p64_codtran
     * @return this
     */
    public function setCodigoTransferencia($p64_codtran)
    {
        $this->p64_codtran = $p64_codtran;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoTransferencia()
    {
        return $this->p64_codtran;
    }
}
