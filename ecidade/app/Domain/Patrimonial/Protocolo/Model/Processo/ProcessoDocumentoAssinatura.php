<?php


namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProcessoDocumentoAssinatura
 * @property int $p122_sequencial
 * @property int $p122_protprocessodocumento
 * @property int $p122_usuario
 * @property int $p122_documento_assinado_estorage
 * @property int $p122_documento_origem_estorage
 * @property string $p122_assinado_em format:Y-m-d H:i:s
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 *
 */
class ProcessoDocumentoAssinatura extends Model
{
    protected $table = 'protocolo.processo_documento_assinatura';
    protected $primaryKey = 'p122_sequencial';
    const CREATED_AT = 'p122_assinado_em';
    const UPDATED_AT = null;

    protected $fillable = [
        'p122_protprocessodocumento',
        'p122_usuario',
        'p122_documento_assinado_estorage',
        'p122_documento_origem_estorage',
    ];
}
