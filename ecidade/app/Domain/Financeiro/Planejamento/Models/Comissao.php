<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use App\Domain\Patrimonial\Protocolo\Model\Cgm;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comissao
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl3_codigo
 * @property $pl3_cgm
 * @property $pl3_planejamento
 */
class Comissao extends Model
{
    const CREATED_AT = 'pl3_created_at';
    const UPDATED_AT = 'pl3_updated_at';

    protected $table = 'planejamento.comissao';

    protected $primaryKey = 'pl3_codigo';

    protected $guarded = ['pl3_codigo'];

    protected $fillable = ['pl3_cgm', 'pl3_planejamento'];

    public function planejamento()
    {
        return $this->belongsTo(Planejamento::class, 'pl3_planejamento', 'pl2_codigo');
    }

    public function cgm()
    {
        return $this->belongsTo(Cgm::class, 'pl3_cgm', 'z01_numcgm');
    }
}
