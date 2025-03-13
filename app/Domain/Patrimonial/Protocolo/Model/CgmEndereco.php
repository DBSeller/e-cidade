<?php
namespace App\Domain\Patrimonial\Protocolo\Model;

use Illuminate\Database\Eloquent\Model;
use App\Domain\Configuracao\Endereco\Model\Endereco;

class CgmEndereco extends Model
{
    protected $table = 'patrimonio.cgmendereco';
    protected $primaryKey = 'z07_sequencial';
    public $timestamps = false;

    public function endereco()
    {
        return $this->belongsTo(Endereco::class, "z07_endereco");
    }
}
