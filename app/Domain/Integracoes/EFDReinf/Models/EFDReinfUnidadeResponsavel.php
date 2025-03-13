<?php

namespace App\Domain\Integracoes\EFDReinf\Models;

use Illuminate\Database\Eloquent\Model;

class EFDReinfUnidadeResponsavel extends Model
{
    protected $table = 'efdreinfunidaderesponsavel';
    protected $primaryKey = 'efd08_sequencial';
    public $timestamps = false;
    protected $fillable = ['efd08_cgm', 'efd08_instit'];
}
