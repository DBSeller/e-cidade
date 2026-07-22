<?php

namespace App\Domain\Configuracao\Banco\Models;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    protected $table = 'configuracoes.bancoagencia';
    protected $primaryKey = 'db89_sequencial';
    public $timestamps = false;

    public function contas()
    {
        return $this->hasMany(ContaBancaria::class, 'db83_bancoagencia', 'db89_sequencial');
    }

    public function banco()
    {
        return $this->belongsTo(DBBancos::class, 'db89_db_bancos', 'db90_codban');
    }
}
