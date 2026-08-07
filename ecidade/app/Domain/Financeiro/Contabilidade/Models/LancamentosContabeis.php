<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LancamentosContabeis
 * @package App\Domain\Financeiro\Contabilidade\Models
 * @property integer $c70_codlan
 * @property integer $c70_anousu
 * @property string $c70_data
 * @property float $c70_valor
 */
class LancamentosContabeis extends Model
{
    protected $table = 'contabilidade.conlancam';
    protected $primaryKey = 'c70_codlan';
    public $timestamps = false;

    public function documentoLancamento()
    {
        return $this->hasOne(DocumentoLancamento::class, 'c71_codlan', 'c70_codlan');
    }

    public function valorLancamento()
    {
        return $this->hasMany(ValorLancamento::class, 'c69_codlan', 'c70_codlan');
    }
}
