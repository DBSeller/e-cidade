<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\Tributario\Arrecadacao\Models\Arretipopixasso;

class Arretipopix extends Model
{

    protected $table       = "arretipopix";
    protected $primaryKey = "codtipopix";
    protected $fillable    = [
        "codtipopix",
        "k00_tipo",
        "modsistema",
        "moddbpref",
        "dtini",
        "dtfim",
        "qtdemissao",
        "valorinicial",
        "valorfinal"
    ];

    public function arretipopixasso()
    {
        return $this->hasMany(
            Arretipopixasso::class,
            "k00_tipo",
            "k00_tipo"
        );
    }
}
