<?php

namespace App\Domain\Configuracao\Usuario\Models;

use Illuminate\Database\Eloquent\Model;

class AssinantesDocumentos extends Model
{
    protected $table = 'configuracoes.assinantesdocumentos';
    protected $primaryKey = "db67_codigo";
    protected $maps = [
        'db67_codigo'     => 'codigo',
        'db67_cpf_cnpj'   => 'cpf_cnpj',
        'db67_id_usuario' => 'id_usuario',
        'db67_nome'       => 'nome',
        'db67_permissao'  => 'permissao',
        'db67_tipo'       => 'tipo'
    ];
    protected $append = ['codigo', 'cpf_cnpj', 'id_usuario', 'nome', 'permissao', 'tipo'];
    public $timestamps = false;

    public function getCodigoAttribute()
    {
        return $this->attributes['db67_codigo'];
    }

    public function getCpfCnpjAttribute()
    {
        return $this->attributes['db67_cpf_cnpj'];
    }

    public function getIdUsuarioAttribute()
    {
        return $this->attributes['db67_id_usuario'];
    }

    public function getNomeAttribute()
    {
        return $this->attributes['db67_nome'];
    }

    public function getPermissaoAttribute()
    {
        return $this->attributes['db67_permissao'];
    }

    public function getTipoAttribute()
    {
        return $this->attributes['db67_tipo'];
    }
}
