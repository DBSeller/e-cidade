<?php

namespace App\Domain\Configuracao\Configuracao\Model;

use App\Domain\Configuracao\Departamento\Models\Departamento;
use App\Domain\Configuracao\Instituicao\Model\DBConfig;
use Illuminate\Database\Eloquent\Model;

class Organograma extends Model
{
    protected $table = 'configuracoes.db_organograma';
    protected $primaryKey = 'db122_sequencial';
    public $incrementing = true;
    public $timestamps = false;

    public function departamento()
    {
        return $this->hasOne(Departamento::class, 'coddepto', 'db122_depart');
    }

    public function departamentoFilho()
    {
        return $this->hasOne(Departamento::class, 'coddepto', 'db122_departfilho');
    }

    public function instituicao()
    {
        return $this->hasOne(DBConfig::class, 'codigo', 'db122_instit');
    }
}
