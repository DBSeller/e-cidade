<?php

namespace App\Domain\Tributario\Arrecadacao\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Domain\Tributario\Arrecadacao\Models\Operacoesrealizadastef
 *
 * @property $k198_sequencial
 * @method static \Illuminate\Database\Eloquent\Builder|Operacoesrealizadastef numnov($numnov)
 * @method static \Illuminate\Database\Eloquent\Builder|Operacoesrealizadastef confirmadoAutorizadora($flag = "t")
 * @method static \Illuminate\Database\Eloquent\Builder|Operacoesrealizadastef desfeito($flag = "t")
 * @method static \Illuminate\Database\Eloquent\Builder|Operacoesrealizadastef grupo($grupo)
 * @method static \Illuminate\Database\Eloquent\Builder|Operacoesrealizadastef terminal($terminal)
 * @method static \Illuminate\Database\Eloquent\Builder|Operacoesrealizadastef confirmadoAuttar($flag = "t")
 * @method static \Illuminate\Database\Eloquent\Builder|Operacoesrealizadastef beetwenDataOperacao($dataInicio,$dataFim)
 */
class Operacoesrealizadastef extends Model
{
    protected $table = "operacoesrealizadastef";
    protected $primaryKey = "k198_sequencial";
    protected $with = ["operacoesTef"];
    public static $snakeAttributes = false;
    public $timestamps = false;

    /**
     * Adiciona join com as operações cadastradas
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operacoesTef()
    {
        return $this->belongsTo(Operacoestef::class, "k198_operacaotef", "k195_sequencial");
    }

    /**
     * Adiciona condição por numnov
     * @param $query
     * @param $numnov
     * @return mixed
     */
    public function scopeNumnov($query, $numnov)
    {
        return $query->where("k198_numnov", $numnov);
    }

    /**
     * Adiciona condição por confirmado
     * @param $query
     * @param string $flag
     * @return mixed
     */
    public function scopeConfirmadoAutorizadora($query, $flag = "t")
    {
        return $query->where("k198_confirmadoautorizadora", $flag);
    }

    /**
     * Adiciona condição por desfeito
     * @param $query
     * @param string $flag
     * @return mixed
     */
    public function scopeDesfeito($query, $flag = "t")
    {
        return $query->where("k198_desfeito", $flag);
    }

    /**
     * Adiciona condição por grupo
     * @param $query
     * @param $grupo
     * @return mixed
     */
    public function scopeGrupo($query, $grupo)
    {
        return $query->where("k198_grupo", $grupo);
    }

    /**
     * Adiciona condição por terminal
     * @param $query
     * @param $terminal
     * @return mixed
     */
    public function scopeTerminal($query, $terminal)
    {
        return $query->where("k198_terminal", $terminal);
    }

    /**
     * Adiciona condição por confirmado na auttar
     * @param $query
     * @param string $flag
     * @return mixed
     */
    public function scopeConfirmadoAuttar($query, $flag = "t")
    {
        return $query->where("k198_confirmado", $flag);
    }

    /**
     * Adiciona condição por range entre a data da operação
     * @param $query
     * @param $dataInicio
     * @param $dataFim
     * @return mixed
     */
    public function scopeBeetwenDataOperacao($query, $dataInicio, $dataFim)
    {
        return $query->whereRaw("TO_CHAR(k198_dataoperacao, 'YYYY-MM-DD') BETWEEN ? AND ?", [$dataInicio, $dataFim]);
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->k198_sequencial;
    }

    /**
     * @param int $k198_sequencial
     * @return Operacoesrealizadastef
     */
    public function setSequencial($k198_sequencial)
    {
        $this->k198_sequencial = $k198_sequencial;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumnov()
    {
        return $this->k198_numnov;
    }

    /**
     * @param int $k198_numnov
     * @return Operacoesrealizadastef
     */
    public function setNumnov($k198_numnov)
    {
        $this->k198_numnov = $k198_numnov;
        return $this;
    }

    /**
     * @return int
     */
    public function getNsu()
    {
        return $this->k198_nsu;
    }

    /**
     * @param int $k198_nsu
     * @return Operacoesrealizadastef
     */
    public function setNsu($k198_nsu)
    {
        $this->k198_nsu = $k198_nsu;
        return $this;
    }

    /**
     * @return float
     */
    public function getValor()
    {
        return $this->k198_valor;
    }

    /**
     * @param float $k198_valor
     * @return Operacoesrealizadastef
     */
    public function setValor($k198_valor)
    {
        $this->k198_valor = $k198_valor;
        return $this;
    }

    /**
     * @return int
     */
    public function getOperacaotef()
    {
        return $this->k198_operacaotef;
    }

    /**
     * @param int $k198_operacaotef
     * @return Operacoesrealizadastef
     */
    public function setOperacaotef($k198_operacaotef)
    {
        $this->k198_operacaotef = $k198_operacaotef;
        return $this;
    }

    /**
     * @return string
     */
    public function getBandeira()
    {
        return $this->k198_bandeira;
    }

    /**
     * @param string $k198_bandeira
     * @return Operacoesrealizadastef
     */
    public function setBandeira($k198_bandeira)
    {
        $this->k198_bandeira = $k198_bandeira;
        return $this;
    }

    /**
     * @return int
     */
    public function getParcela()
    {
        return $this->k198_parcela;
    }

    /**
     * @param int $k198_parcela
     * @return Operacoesrealizadastef
     */
    public function setParcela($k198_parcela)
    {
        $this->k198_parcela = $k198_parcela;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataoperacao()
    {
        return $this->k198_dataoperacao;
    }

    /**
     * @param string $k198_dataoperacao
     * @return Operacoesrealizadastef
     */
    public function setDataoperacao($k198_dataoperacao)
    {
        $this->k198_dataoperacao = $k198_dataoperacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfirmado()
    {
        return $this->k198_confirmado;
    }

    /**
     * @param string $k198_confirmado
     * @return Operacoesrealizadastef
     */
    public function setConfirmado($k198_confirmado)
    {
        $this->k198_confirmado = $k198_confirmado;
        return $this;
    }

    /**
     * @return string
     */
    public function getMensagemretorno()
    {
        return $this->k198_mensagemretorno;
    }

    /**
     * @param string $k198_mensagemretorno
     * @return Operacoesrealizadastef
     */
    public function setMensagemretorno($k198_mensagemretorno)
    {
        $this->k198_mensagemretorno = $k198_mensagemretorno;
        return $this;
    }

    /**
     * @return string
     */
    public function getDesfeito()
    {
        return $this->k198_desfeito;
    }

    /**
     * @param string $k198_desfeito
     * @return Operacoesrealizadastef
     */
    public function setDesfeito($k198_desfeito)
    {
        $this->k198_desfeito = $k198_desfeito;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigoaprovacao()
    {
        return $this->k198_codigoaprovacao;
    }

    /**
     * @param string $k198_codigoaprovacao
     * @return Operacoesrealizadastef
     */
    public function setCodigoaprovacao($k198_codigoaprovacao)
    {
        $this->k198_codigoaprovacao = $k198_codigoaprovacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getNsuautorizadora()
    {
        return $this->k198_nsuautorizadora;
    }

    /**
     * @param int $k198_nsuautorizadora
     * @return Operacoesrealizadastef
     */
    public function setNsuautorizadora($k198_nsuautorizadora)
    {
        $this->k198_nsuautorizadora = $k198_nsuautorizadora;
        return $this;
    }

    /**
     * @return string
     */
    public function getConcluidobaixabanco()
    {
        return $this->k198_concluidobaixabanco;
    }

    /**
     * @param string $k198_concluidobaixabanco
     * @return Operacoesrealizadastef
     */
    public function setConcluidobaixabanco($k198_concluidobaixabanco)
    {
        $this->k198_concluidobaixabanco = $k198_concluidobaixabanco;
        return $this;
    }

    /**
     * @return string
     */
    public function getCartao()
    {
        return $this->k198_cartao;
    }

    /**
     * @param string $k198_cartao
     * @return Operacoesrealizadastef
     */
    public function setCartao($k198_cartao)
    {
        $this->k198_cartao = $k198_cartao;
        return $this;
    }

    /**
     * @return string
     */
    public function getRetorno()
    {
        return $this->k198_retorno;
    }

    /**
     * @param string $k198_retorno
     * @return Operacoesrealizadastef
     */
    public function setRetorno($k198_retorno)
    {
        $this->k198_retorno = $k198_retorno;
        return $this;
    }

    /**
     * @return int
     */
    public function getGrupo()
    {
        return $this->k198_grupo;
    }

    /**
     * @param int $k198_grupo
     * @return Operacoesrealizadastef
     */
    public function setGrupo($k198_grupo)
    {
        $this->k198_grupo = $k198_grupo;
        return $this;
    }

    /**
     * @return int
     */
    public function getTerminal()
    {
        return $this->k198_terminal;
    }

    /**
     * @param int $k198_terminal
     * @return Operacoesrealizadastef
     */
    public function setTerminal($k198_terminal)
    {
        $this->k198_terminal = $k198_terminal;
        return $this;
    }

    /**
     * @return bool
     */
    public function getConfirmadoautorizadora()
    {
        return $this->k198_confirmadoautorizadora;
    }

    /**
     * @param bool $k198_confirmadoautorizadora
     * @return Operacoesrealizadastef
     */
    public function setConfirmadoautorizadora($k198_confirmadoautorizadora)
    {
        $this->k198_confirmadoautorizadora = $k198_confirmadoautorizadora;
        return $this;
    }
}
