<?php

namespace App\Domain\Configuracao\Banco\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @class DBBancos
 * @property $db90_codban
 * @property $db90_descr
 * @property $db90_digban
 * @property $db90_abrev
 * @property $db90_logo
 */
class DBBancos extends Model
{
    protected $table = "db_bancos";
    protected $primaryKey = 'db90_codban';
    public $timestamps = false;
    protected $keyType = 'string';

    public function dadosBancoPix()
    {
        $this->dadosBanco = $this->hasOne(
            DBBancosPix::class,
            "db90_codban",
            "db90_codban"
        );
    }
}
