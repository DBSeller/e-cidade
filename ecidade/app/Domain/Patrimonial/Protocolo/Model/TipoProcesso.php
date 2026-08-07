<?php

namespace App\Domain\Patrimonial\Protocolo\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $p51_codigo
 * @property $p51_descr
 * @property $p51_dtlimite
 * @property $p51_instit
 * @property $p51_identificado
 * @property $p51_tipoprocgrupo
 * @property $p51_prottipodocumentoprocesso
 * @property $p51_linksaibamais
 * @property $p51_itemmenu
 * @property $p51_mensagem
 */
class TipoProcesso extends Model
{
    protected $table = 'protocolo.tipoproc';
    protected $primaryKey = 'p51_codigo';
    public $timestamps = false;

    public function atividades()
    {
        return $this->belongsToMany(
            AtividadeExecucao::class,
            'tipoprocesso_atividadeexecucao',
            'p115_tipoprocesso',
            'p115_atividadesexecucao'
        )->withPivot('p115_ordem')->orderBy('pivot_p115_ordem');
    }
}
