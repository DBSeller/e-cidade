<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class MovimentacaoAuditoria
 * @package App\Domain\Financeiro\Contabilidade\Models
 * @property float $c170_adicaoauditoria
 * @property float $c170_exclusaoauditoria
 * @property float $c170_resto
 * @property int $c170_anousu
 * @property int $c170_mes
 */
class MovimentacaoAuditoria extends Model
{
    protected $table = 'contabilidade.movimentacoesauditoria';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
}
