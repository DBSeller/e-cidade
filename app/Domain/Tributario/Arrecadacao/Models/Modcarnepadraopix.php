<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domain\Tributario\Arrecadacao\Models\Modcarnepadraopixasso;

class Modcarnepadraopix extends Model
{
    protected $table      = "modcarnepadraopix";
    protected $primaryKey = "k48_sequencial_pix";
    protected $fillable   = [
        "k48_sequencial_pix",
        "k48_sequencial",
        "k48_ammpix"
    ];

    public function modcarnepadraopixasso()
    {
        return $this->hasMany(
            Modcarnepadraopixasso::class,
            "k48_sequencial",
            "k48_sequencial"
        );
    }
}
