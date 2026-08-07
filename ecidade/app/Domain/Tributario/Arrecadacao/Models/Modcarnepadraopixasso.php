<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use App\Domain\Configuracao\Banco\Models\DBBancosPix;
use Illuminate\Database\Eloquent\Model;

class Modcarnepadraopixasso extends Model
{
    protected $table      = "modcarnepadraopixasso";
    protected $primaryKey = "sequencial";
    protected $fillable   = [
        "sequencial",
        "db90_codban",
        "k48_sequencial"
    ];

    public function getBanco()
    {
        return $this->hasOne(
            DBBancosPix::class,
            "db90_codban",
            "db90_codban"
        )->first();
    }
}
