<?php

namespace App\Domain\Patrimonial\Protocolo\Model;

use App\Domain\Patrimonial\Protocolo\Model\Processo\Processo;
use App\Domain\Patrimonial\Protocolo\Model\Processo\ProcessoDocumento;
use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property $p116_codigo
 * @property $p116_descricao
 * @property $p116_protprocesso
 * @property $p116_protprocessodocumento
 * @property $p116_atividade_atual
 * @property $p116_proxima_atividade
 * @property integer $p116_codigo_origem
 * @property $p116_data_criacao
 * @property $p116_data_modificacao
 * @property Processo $processo
 * @property ProcessoDocumento $processoDocumento
 * @property ProcessoAtividadeExecucao $atividadeAtual
 * @property ProcessoAtividadeExecucao $proximaAtividade
 * @property $p116_qrcode
 */
class DocumentoAndamento extends Model
{
    protected $table = 'protocolo.documentos_andamento';
    protected $primaryKey = 'p116_codigo';
    public $timestamps = false;

    public function atividadeAtual()
    {
        return $this->belongsTo(ProcessoAtividadeExecucao::class, 'p116_atividade_atual', 'p118_codigo')
            ->with('atividade');
    }
    public function proximaAtividade()
    {
        return $this->belongsTo(ProcessoAtividadeExecucao::class, 'p116_proxima_atividade', 'p118_codigo')
            ->with('atividade');
    }

    public function processo()
    {
        return $this->belongsTo(Processo::class, 'p116_protprocesso', 'p58_codproc');
    }

    public function processoDocumento()
    {
        return $this->belongsTo(ProcessoDocumento::class, 'p116_protprocessodocumento', 'p01_sequencial');
    }

    public static function fromState(array $state)
    {
        $documentoMovimentacao = new self();
        if (array_key_exists('p116_codigo', $state)) {
            $documentoMovimentacao->p116_codigo = $state['p116_codigo'];
        }
        if (array_key_exists('p116_descricao', $state)) {
            $documentoMovimentacao->p116_descricao = $state['p116_descricao'];
        }
        if (array_key_exists('p116_protprocesso', $state)) {
            $documentoMovimentacao->p116_protprocesso = $state['p116_protprocesso'];
        }
        if (array_key_exists('p116_protprocessodocumento', $state)) {
            $documentoMovimentacao->p116_protprocessodocumento = $state['p116_protprocessodocumento'];
        }
        if (array_key_exists('p116_atividade_atual', $state)) {
            $documentoMovimentacao->p116_atividade_atual = $state['p116_atividade_atual'];
        }
        if (array_key_exists('p116_proxima_atividade', $state)) {
            $documentoMovimentacao->p116_proxima_atividade = $state['p116_proxima_atividade'];
        }
        if (array_key_exists('p116_codigo_origem', $state)) {
            $documentoMovimentacao->p116_codigo_origem = $state['p116_codigo_origem'];
        }
        if (array_key_exists('p116_data_criacao', $state)) {
            $documentoMovimentacao->p116_data_criacao = $state['p116_data_criacao'];
        }
        if (array_key_exists('p116_data_modificacao', $state)) {
            $documentoMovimentacao->p116_data_modificacao = $state['p116_data_modificacao'];
        }
        if (array_key_exists('p116_qrcode', $state)) {
            $documentoMovimentacao->p116_qrcode = $state['p116_qrcode'];
        }
        return $documentoMovimentacao;
    }
}
