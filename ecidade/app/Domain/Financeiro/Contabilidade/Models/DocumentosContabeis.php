<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentosContabeis
 * @package App\Domain\Financeiro\Contabilidade\Models
 * @property integer $c53_coddoc
 * @property string $c53_descr
 * @property integer $c53_tipo
 */
class DocumentosContabeis extends Model
{
    protected $table = 'contabilidade.conhistdoc';
    protected $primaryKey = 'c53_coddoc';
    public $timestamps = false;

    public function tipo()
    {
        return $this->belongsTo(TipoDocumento::class, 'c53_tipo', 'c57_sequencial');
    }

    public function lancamentos()
    {
        return $this->hasMany(DocumentoLancamento::class, 'c71_coddoc', 'c53_coddoc');
    }
}
