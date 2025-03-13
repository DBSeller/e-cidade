<?php

namespace App\Domain\Patrimonial\Protocolo\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $p114_codigo
 * @property $p114_atividade
 * @property $p114_status
 */
class AtividadeExecucao extends Model
{
    const GERAR = 1;
    const CONFERIR = 2;
    const ASSINAR = 3;
    const ARQUIVAR = 4;

    protected $table = 'protocolo.atividadesexecucao';
    protected $primaryKey = 'p114_codigo';
    public $timestamps = false;
}
