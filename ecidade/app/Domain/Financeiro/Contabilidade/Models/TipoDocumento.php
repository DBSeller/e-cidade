<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoDocumento
 * @package App\Domain\Financeiro\Contabilidade\Models
 * @property integer $c57_sequencial
 * @property string $c57_descricao
 */
class TipoDocumento extends Model
{
    protected $table = 'contabilidade.conhistdoctipo';
    protected $primaryKey = 'c57_sequencial';
    public $timestamps = false;

    public function documentos()
    {
        return $this->hasMany(DocumentosContabeis::class, 'c53_tipo', 'c57_sequencial');
    }
}
