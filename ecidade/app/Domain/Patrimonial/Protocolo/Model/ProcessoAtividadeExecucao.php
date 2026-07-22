<?php

namespace App\Domain\Patrimonial\Protocolo\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $p118_codigo
 * @property $p118_protprocesso
 * @property $p118_atividadesexecucao
 * @property $p118_ordem
 * @property AtividadeExecucao $atividade
 */
class ProcessoAtividadeExecucao extends Model
{
    protected $table = 'protocolo.processo_atividadesexecucao';
    protected $primaryKey = 'p118_codigo';
    public $timestamps = false;

    public static function fromState($state)
    {
        $processoAtividadeExecucao = new self();
        $processoAtividadeExecucao->p118_codigo = $state['p118_codigo'];
        $processoAtividadeExecucao->p118_protprocesso = $state['p118_protprocesso'];
        $processoAtividadeExecucao->p118_atividadesexecucao = $state['p118_atividadesexecucao'];
        $processoAtividadeExecucao->p118_ordem = $state['p118_ordem'];
        return $processoAtividadeExecucao;
    }

    public function atividade()
    {
        return $this->belongsTo(AtividadeExecucao::class, 'p118_atividadesexecucao', 'p114_codigo');
    }
}
