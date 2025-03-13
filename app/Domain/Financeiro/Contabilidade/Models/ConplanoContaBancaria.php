<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use App\Domain\Configuracao\Banco\Models\ContaBancaria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @class ConplanoContaBancaria
 * @property $c56_sequencial
 * @property $c56_contabancaria
 * @property $c56_codcon
 * @property $c56_anousu
 * @property $c56_reduz
 */
class ConplanoContaBancaria extends Model
{
    protected $table = 'contabilidade.conplanocontabancaria';
    protected $primaryKey = 'c56_sequencial';
    public $timestamps = false;

    /**
     * @return integer
     */
    public static function nextCodigo()
    {
        return DB::select("select nextval('conplanocontabancaria_c56_sequencial_seq')")[0]->nextval;
    }

    public function contaBancaria()
    {
        return $this->hasOne(ContaBancaria::class, 'db83_sequencial', 'c56_contabancaria');
    }
}
