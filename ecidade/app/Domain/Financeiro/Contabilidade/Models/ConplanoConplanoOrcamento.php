<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @class ConplanoConplanoOrcamento
 *
 */
class ConplanoConplanoOrcamento extends Model
{
    protected $table = 'contabilidade.conplanoconplanoorcamento';
    protected $primaryKey = 'c72_sequencial';
    public $timestamps = false;
}
