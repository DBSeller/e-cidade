<?php

namespace App\Domain\Tributario\Cadastro\Models;

use cl_iptubase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Domain\Tributario\Cadastro\Repositories\IptubaseRepository;
use Illuminate\Support\Str;

class ParametrosNumeroCadastral extends Model
{
    protected $table      = "parametrosnumerocadastral";
    protected $primaryKey = "j180_instit";
    protected $fillable = [
        'j180_separadormascara',
        'j180_configuracao'
    ];

    public $timestamps = false;

    public function montaNumero($instit, $matricula)
    {
      
        $numeroFormatado = "";
        $dados = $this->where('j180_instit', '=', $instit)->get()->first();
        $configuracao = json_decode($dados->j180_configuracao);
      
        foreach ($configuracao as $indice => $valor) {
            $campo = $this->buscaInformacaoCampo(trim($valor->campo), $matricula);
            $campo = str_pad(substr($campo, -$valor->tamanho), $valor->tamanho, '0', STR_PAD_LEFT);
         
            if ($indice == 0) {
                $numeroFormatado .= "{$campo}";
                continue;
            }

            $numeroFormatado .= "{$dados->j180_separadormascara}{$campo}";
        }
      
        return empty($numeroFormatado)?false:$numeroFormatado;
    }

    public function buscaInformacaoCampo($campo, $matricula)
    {

        $iptubaseRepository = new IptubaseRepository();
        $informacaoCampo    = $iptubaseRepository->getCamposDadosRegImovByMatric($matricula, $campo);
        return $informacaoCampo->{$campo};
    }

    public function existeParametro($instit)
    {
        return $this->where('j180_instit', '=', $instit)->count() > 0?true:false;
    }
}
