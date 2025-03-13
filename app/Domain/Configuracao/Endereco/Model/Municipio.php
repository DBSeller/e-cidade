<?php

namespace App\Domain\Configuracao\Endereco\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Builder|Municipio sistemaExterno($codigoSistemaExterno, $codigoExterno)
 */
class Municipio extends Model
{
    protected $table = 'configuracoes.cadendermunicipio';
    protected $primaryKey = 'db72_sequencial';
    protected $with = ["estado"];
    public $timestamps = false;

    public function estado()
    {
        return $this->belongsTo(Estado::class, "db72_cadenderestado");
    }

    public function scopeSistemaExterno(Builder $query, $codigoSistemaExterno, $codigoExterno)
    {
        $subquery = function ($query) use ($codigoSistemaExterno, $codigoExterno) {
            $query->select("db125_cadendermunicipio")
                  ->from("cadendermunicipiosistema")
                  ->where("db125_db_sistemaexterno", $codigoSistemaExterno)
                  ->where("db125_codigosistema", $codigoExterno);
        };

        return $query->where("db72_sequencial", $subquery);
    }
}
