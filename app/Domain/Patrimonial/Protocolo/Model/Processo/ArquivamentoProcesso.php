<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ArquivamentoProcesso
 *
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int p68_codarquiv
 * @property int p68_codproc
 */
class ArquivamentoProcesso extends Model
{
    protected $table = 'protocolo.arqproc';
    protected $primaryKey = 'p68_codarquiv';
    public $timestamps = false;

    /**
     * @param int $p68_codarquiv
     * @return this
     */
    public function setArquivamento($p68_codarquiv)
    {
        $this->p68_codarquiv = $p68_codarquiv;
        return $this;
    }

    /**
     * @return int
     */
    public function getArquivamento()
    {
        return $this->p68_codarquiv;
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
}
