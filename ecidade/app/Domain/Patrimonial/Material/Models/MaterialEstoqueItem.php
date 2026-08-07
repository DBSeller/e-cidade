<?php

namespace App\Domain\Patrimonial\Material\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialEstoqueItem extends Model
{
    protected $table = 'material.matestoqueitem';
    protected $primaryKey = 'm71_codlanc';
    public $timestamps = false;

    public function estoque()
    {
        return $this->belongsTo(MaterialEstoque::class, 'm71_codmatestoque', 'm70_codigo');
    }
}
