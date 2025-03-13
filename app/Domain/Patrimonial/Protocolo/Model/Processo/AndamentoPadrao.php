<?php

namespace App\Domain\Patrimonial\Protocolo\Model\Processo;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AndamentoPadrao
 *
 * @package App\Domain\Patrimonial\Protocolo\Model\Processo
 * @property int p53_codigo
 * @property int p53_coddepto
 * @property int p53_dias
 * @property int p53_ordem
 *
 * @method static Builder|AndamentoPadrao ordem($ordem)
 * @method static Builder|AndamentoPadrao tipoProcesso($tipoProcesso)
 */
class AndamentoPadrao extends Model
{
    protected $table = 'protocolo.andpadrao';
    protected $primaryKey = 'p53_codigo';
    public $timestamps = false;

    /**
     * Filtra o andamento pela ordem
     * @param Builder $query
     * @param $ordem
     * @return Builder
     */
    public function scopeOrdem(Builder $query, $ordem)
    {
        return $query->where("p53_ordem", $ordem);
    }

    /**
     * Filtra o andamento pelo tipo do processo
     * @param Builder $query
     * @param $tipoProcesso
     * @return Builder
     */
    public function scopeTipoProcesso(Builder $query, $tipoProcesso)
    {
        return $query->where("p53_codigo", $tipoProcesso);
    }

    /**
     * @param int $p53_codigo
     * @return this
     */
    public function setCodigo($p53_codigo)
    {
        $this->p53_codigo = $p53_codigo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->p53_codigo;
    }

    /**
     * @param int $p53_coddepto
     * @return this
     */
    public function setDepartamento($p53_coddepto)
    {
        $this->p53_coddepto = $p53_coddepto;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepartamento()
    {
        return $this->p53_coddepto;
    }

    /**
     * @param int $p53_dias
     * @return this
     */
    public function setDias($p53_dias)
    {
        $this->p53_dias = $p53_dias;
        return $this;
    }

    /**
     * @return int
     */
    public function getDias()
    {
        return $this->p53_dias;
    }

    /**
     * @param int $p53_ordem
     * @return this
     */
    public function setOrdem($p53_ordem)
    {
        $this->p53_ordem = $p53_ordem;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdem()
    {
        return $this->p53_ordem;
    }
}
