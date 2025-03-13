<?php

namespace App\Domain\Patrimonial\Protocolo\Model;

use App\Domain\Configuracao\Usuario\Models\Usuario;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property $p117_codigo
 * @property $p117_documento_andamento
 * @property $p117_id_usuario
 * @property $p117_protprocessodocumento
 * @property $p117_processo_atividadesexecucao
 * @property $p117_data
 * @property ProcessoAtividadeExecucao $processoAtividadeExecucao
 * @property boolean $p117_devolucao
 * @property boolean $p117_invalida
 */
class DocumentoMovimentacao extends Model
{
    protected $table = 'protocolo.documentos_movimentacao';
    protected $primaryKey = 'p117_codigo';
    public $timestamps = false;

    public static function fromState(array $state)
    {
        $documentoMovimentacao = new self();
        $documentoMovimentacao->p117_codigo = $state['p117_codigo'];
        $documentoMovimentacao->p117_documento_andamento = $state['p117_documento_andamento'];
        $documentoMovimentacao->p117_id_usuario = $state['p117_id_usuario'];
        $documentoMovimentacao->p117_protprocessodocumento = $state['p117_protprocessodocumento'];
        $documentoMovimentacao->p117_processo_atividadesexecucao = $state['p117_processo_atividadesexecucao'];
        $documentoMovimentacao->p117_data = $state['p117_data'];
        $documentoMovimentacao->p117_devolucao = $state['p117_devolucao'] == 't';
        $documentoMovimentacao->p117_invalida = $state['p117_invalida'] == 't';

        return $documentoMovimentacao;
    }

    public function processoAtividadeExecucao()
    {
        return $this->belongsTo(ProcessoAtividadeExecucao::class, 'p117_processo_atividadesexecucao', 'p118_codigo');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'id_usuario', 'p117_id_usuario');
    }
}
