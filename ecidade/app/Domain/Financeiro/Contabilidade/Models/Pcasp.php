<?php

namespace App\Domain\Financeiro\Contabilidade\Models;

use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property $id
 * @property $exercicio
 * @property $uniao
 * @property $conta
 * @property $nome
 * @property $funcao
 * @property $natureza
 * @property $sintetica
 * @property $indicador
 * @property $informacoescomplementares
 * @property $classe
 * @property $grupo
 * @property $subgrupo
 * @property $titulo
 * @property $subtitulo
 * @property $item
 * @property $subitem
 * @property $desdobramento1
 * @property $desdobramento2
 * @property $desdobramento3
 * @property $created_at
 * @property $updated_at
 *
 * @method Pcasp unidade()
 * @method Pcasp outrasContas()
 * @method Pcasp contasCaixa()
 * @method Pcasp excetoContasCaixa()
 * @method Pcasp contasBancarias()
 * @method Pcasp excetoContasBancarias()
 * @method Pcasp contasExtrasOrcamentarias()
 * @method Pcasp excetoContasExtrasOrcamentarias()
 */
class Pcasp extends Model
{
    protected $table = 'contabilidade.pcasp';

    public function toArray()
    {
        $estrutural = new Estrutural($this->conta);
        $data = parent::toArray();
        $data['mascara'] = $estrutural->getEstruturalComMascara();

        return $data;
    }

    public function contasEcidade()
    {
        return $this->belongsToMany(
            Conplano::class,
            'contabilidade.pcaspconplano',
            'pcasp_id',
            'conplano_codigo'
        );
    }

    /**
     * Objetivo é filtrar as contas do plano PCASP que sejam:
     * - contas do Ativo (grupo 1) não enquadradas como caixa, bancos ou operações extra orçamentárias;
     * - contas do Passivo (grupo 2) não enquadradas como operações extra orçamentárias;
     * - contas dos grupos 3,4,5,6,7 e 8.
     */
    public function scopeOutrasContas(Builder $query, $idMicroarea = null)
    {
        $query->excetoContasCaixa();
        $query->excetoContasBancarias();
        $query->excetoContasExtrasOrcamentarias();
    }

    /**
     * Filtra a(s) contas caixa
     * @param Builder $query
     * @param string $operador
     */
    public function scopeContasCaixa(Builder $query)
    {
        $query->where(function (Builder $query) {
            $query->orWhere('conta', 'like', "111110100%")
                ->orWhere('conta', 'like', "1112101%");
        });
    }

    /**
     * Filtra no plano PCASP as contas EXCLUINDO as contas caixa
     * @param Builder $query
     * @param string $operador
     */
    public function scopeExcetoContasCaixa(Builder $query)
    {
        $query->where(function (Builder $query) {
            $query->where('conta', 'not like', "111110100%")
                ->where('conta', 'not like', "1112101%");
        });
    }

    /**
     * Filtra as contas bancárias do PCASP
     * @param Builder $query
     * @param string $operador
     */
    public function scopeContasBancarias(Builder $query)
    {
        $query->where(function (Builder $query) {
            $query->orWhere('conta', 'like', "1111106%")
                ->orWhere('conta', 'like', "1111119%")
                ->orWhere('conta', 'like', "1111150%")
                ->orWhere('conta', 'like', "1111151%")
                ->orWhere('conta', 'like', "1111152%")
                ->orWhere('conta', 'like', "1111153%")
                ->orWhere('conta', 'like', "1111200%")
                ->orWhere('conta', 'like', "1112102%")
                ->orWhere('conta', 'like', "1112103%");
        });
    }

    public function scopeExcetoContasBancarias(Builder $query)
    {
        $query->where(function (Builder $query) {
            $query->where('conta', 'not like', "1111106%")
                ->where('conta', 'not like', "1111119%")
                ->where('conta', 'not like', "1111150%")
                ->where('conta', 'not like', "1111151%")
                ->where('conta', 'not like', "1111152%")
                ->where('conta', 'not like', "1111153%")
                ->where('conta', 'not like', "1111200%")
                ->where('conta', 'not like', "1112102%")
                ->where('conta', 'not like', "1112103%");
        });
    }

    /**
     * Filtra as contas extra-orçamentárias do PCASP
     * @param Builder $query
     * @param string $operador
     */
    public function scopeContasExtrasOrcamentarias(Builder $query)
    {
        $query->where(function (Builder $query) {
            $query->where(function (Builder $query) {
                $query->whereIn('indicador', ['F', 'F/P']);
            });
            $query->where(function (Builder $query) {
                $query->orWhere('conta', 'like', '1113%')
                    ->orWhere('conta', 'like', '1131%')
                    ->orWhere('conta', 'like', '1132%')
                    ->orWhere('conta', 'like', '1135%')
                    ->orWhere('conta', 'like', '113620101%')
                    ->orWhere('conta', 'like', '1136203%')
                    ->orWhere('conta', 'like', '1136301%')
                    ->orWhere('conta', 'like', '1136401%')
                    ->orWhere('conta', 'like', '1136503%')
                    ->orWhere('conta', 'like', '1138106%')
                    ->orWhere('conta', 'like', '1138108%')
                    ->orWhere('conta', 'like', '1138109%')
                    ->orWhere('conta', 'like', '1138110%')
                    ->orWhere('conta', 'like', '1138111%')
                    ->orWhere('conta', 'like', '1138117%')
                    ->orWhere('conta', 'like', '1138199%')
                    ->orWhere('conta', 'like', '11382%')
                    ->orWhere('conta', 'like', '11383%')
                    ->orWhere('conta', 'like', '11384%')
                    ->orWhere('conta', 'like', '11385%')
                    ->orWhere('conta', 'like', '12121%')
                    ->orWhere('conta', 'like', '2188%')
                    ->orWhere('conta', 'like', '2288%');
            });
        });
    }

    /**
     * Filtra no plano PCASP as contas EXCLUINDO as contas extra-orçamentárias
     * @param Builder $query
     * @param string $operador
     */
    public function scopeExcetoContasExtrasOrcamentarias(Builder $query)
    {
        $query->whereRaw("
            id not in (
              select extra.id
                from contabilidade.pcasp as extra
               where extra.uniao = pcasp.uniao
                 and extra.sintetica = pcasp.sintetica
                 and (    (extra.indicador in ('F', 'F/P'))
                      and (    extra.conta like '1113%'
                            or extra.conta like '1131%'
                            or extra.conta like '1132%'
                            or extra.conta like '1135%'
                            or extra.conta like '113620101%'
                            or extra.conta like '1136203%'
                            or extra.conta like '1136301%'
                            or extra.conta like '1136401%'
                            or extra.conta like '1136503%'
                            or extra.conta like '1138106%'
                            or extra.conta like '1138108%'
                            or extra.conta like '1138109%'
                            or extra.conta like '1138110%'
                            or extra.conta like '1138111%'
                            or extra.conta like '1138117%'
                            or extra.conta like '1138199%'
                            or extra.conta like '11382%'
                            or extra.conta like '11383%'
                            or extra.conta like '11384%'
                            or extra.conta like '11385%'
                            or extra.conta like '12121%'
                            or extra.conta like '2188%'
                            or extra.conta like '2288%'
                          )
                     )
        )");
    }
}
