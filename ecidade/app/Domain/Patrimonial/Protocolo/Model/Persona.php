<?php

namespace App\Domain\Patrimonial\Protocolo\Model;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'protocolo.persona';
    protected $primaryKey = 'p120_sequencial';
    public $timestamps = false;
    protected $fillable = [
        'p120_descricao',
        'p120_objetivo'
    ];


    public function getSequecial()
    {
        return $this->attributes['p120_sequencial'];
    }

    public function setSequecial($p120_sequencial)
    {
        $this->attributes['p120_sequencial'] = $p120_sequencial;
    }

    public function getDescricao()
    {
        return $this->attributes['p120_descricao'];
    }


    public function setDescricao($p120_descricao)
    {
        $this->attributes['p120_descricao'] = $p120_descricao;
    }


    public function getObjetivo()
    {
        return $this->attributes['p120_objetivo'];
    }

    public function setObjetivo($p120_objetivo)
    {
        $this->p120_objetivo = $p120_objetivo;
    }
}
