<?php

namespace App\Domain\Configuracao\Banco\Models;

use App\Domain\Tributario\Arrecadacao\Models\Arretipopixasso;
use Illuminate\Database\Eloquent\Model;

class DBBancosPix extends Model
{
    protected $table      = "db_bancos_pix";
    protected $primaryKey = "db90_codban_pix";
    protected $fillable  = [
        "db90_codban_pix",
        "db90_codban",
        "db90_tipo_ambiente",
        "db90_login",
        "db90_senha",
        "db90_chave_api",
        "db90_chave_pix",
        "db90_cnpj_municipio",
        "db90_cnpj",
        "db90_numconv"
    ];

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    public function dadosBanco()
    {
        $this->dadosBanco = $this->hasOne(
            DBBancos::class,
            "db90_codban",
            "db90_codban"
        )->first();
    }

    public function arretipopixasso()
    {
        return $this->hasMany(
            Arretipopixasso::class,
            "db90_codban",
            "db90_codban"
        );
    }
}
