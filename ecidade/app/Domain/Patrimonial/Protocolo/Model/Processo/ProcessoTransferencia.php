<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transferencia
 *
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int p63_codtran
 * @property int p63_codproc
 */
class ProcessoTransferencia extends Model
{
    protected $table = 'protocolo.proctransferproc';
    // TODO: Implementar uma forma de usar chaves compostas no laravel
    protected $primaryKey = 'p63_codproc';
    // protected $primaryKey = ['p63_codproc', 'p63_codtran'];
    public $timestamps = false;

    /**
     * @param int $p63_codtran
     * @return this
     */
    public function setCodigoTransferencia($p63_codtran)
    {
        $this->p63_codtran = $p63_codtran;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoTransferencia()
    {
        return $this->p63_codtran;
    }

    /**
     * @param int $p63_codproc
     * @return this
     */
    public function setCodigoProcesso($p63_codproc)
    {
        $this->p63_codproc = $p63_codproc;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoProcesso()
    {
        return $this->p63_codproc;
    }
}
