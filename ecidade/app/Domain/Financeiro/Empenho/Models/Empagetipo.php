<?php

namespace App\Domain\Financeiro\Empenho\Models;

use App\Domain\Financeiro\Tesouraria\Models\Saltes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @class Empagetipo
 * @property $e83_codtipo
 * @property $e83_descr
 * @property $e83_conta
 * @property $e83_codmod
 * @property $e83_convenio
 * @property $e83_sequencia
 * @property $e83_codigocompromisso
 */
class Empagetipo extends Model
{
    protected $table = 'empenho.empagetipo';
    protected $primaryKey = 'e83_codtipo';
    public $timestamps = false;

    public function saldoTesouraria()
    {
        return $this->hasOne(Saltes::class, 'k13_conta', 'e83_codtipo');
    }

    /**
     * @return integer
     */
    public static function nextCodigo()
    {
        return DB::select("select nextval('empagetipo_e83_codtipo_seq')")[0]->nextval;
    }
}
