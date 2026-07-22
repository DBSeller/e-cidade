<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;

class Operacoestef extends Model
{
    protected $table = "operacoestef";
    protected $primaryKey = "k195_sequencial";
    public static $snakeAttributes = false;
    public $timestamps = false;

    public function operacoesRealizadasTef()
    {
        return $this->hasMany(Operacoesrealizadastef::class, "k198_operacaotef", "k195_sequencial");
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->k195_sequencial;
    }

    /**
     * @param int $sequencial
     * @return Operacoestef
     */
    public function setSequencial($sequencial)
    {
        $this->k195_sequencial = $sequencial;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->k195_descricao;
    }

    /**
     * @param string $descricao
     * @return Operacoestef
     */
    public function setDescricao($descricao)
    {
        $this->k195_descricao = $descricao;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigoperacao()
    {
        return $this->k195_codigoperacao;
    }

    /**
     * @param int $codigoperacao
     * @return Operacoestef
     */
    public function setCodigoperacao($codigoperacao)
    {
        $this->k195_codigoperacao = $codigoperacao;
        return $this;
    }
}
