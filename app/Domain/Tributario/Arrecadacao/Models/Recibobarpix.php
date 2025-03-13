<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

class Recibobarpix extends Model
{
    /**
     * Tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'caixa.recibobarpix';
    protected $primaryKey = ['k00_numpre', 'k00_numpar', 'k00_codbar'];
    public $incrementing = false;
    public $timestamps = false;
}
