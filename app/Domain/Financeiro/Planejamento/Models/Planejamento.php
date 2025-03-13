<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use ECidade\Enum\Financeiro\Planejamento\StatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Planejamento
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl2_codigo
 * @property $pl2_tipo
 * @property $pl2_codigo_pai
 * @property $pl2_ano_inicial
 * @property $pl2_ano_final
 * @property $pl2_ativo
 * @property $pl2_titulo
 * @property $pl2_base_calculo
 * @property $pl2_base_despesa
 * @property $pl2_composicao
 * @property $pl2_ementa
 * @property $pl2_missao
 * @property $pl2_visao
 * @property $pl2_valores
 * @property $pl2_created_at
 * @property $pl2_updated_at
 */
class Planejamento extends Model
{
    const CREATED_AT = 'pl2_created_at';
    const UPDATED_AT = 'pl2_updated_at';

    protected $table = 'planejamento.planejamento';

    protected $primaryKey = 'pl2_codigo';

    protected $guarded = ['pl2_codigo'];

    protected $dates = [
        'pl2_created_at',
        'pl2_updated_at',
    ];

    protected $casts = [
        'pl2_codigo' => 'integer',
        'pl2_tipo' => 'string',
        'pl2_codigo_pai' => 'integer',
        'pl2_ano_inicial' => 'integer',
        'pl2_ano_final' => 'integer',
        'pl2_ativo' => 'boolean',
        'pl2_titulo' => 'string',
        'pl2_base_calculo' => 'integer',
        'pl2_base_despesa' => 'integer',
        'pl2_composicao' => 'integer',
        'pl2_ementa' => 'string',
        'pl2_missao' => 'string',
        'pl2_visao' => 'string',
        'pl2_valores' => 'string',
    ];

    protected $storage = [];

    /**
     * Retorna os valores do programa estratégico
     * @return Collection
     */
    public function getPIB()
    {
        if (!array_key_exists('PIB', $this->storage)) {
            $this->storage['PIB'] = Valor::where('pl10_chave', '=', $this->pl2_codigo)
                ->where('pl10_origem', '=', Valor::ORIGEM_PIB)
                ->orderBy('pl10_ano')
                ->get();
        }

        return $this->storage['PIB'];
    }
    /**
     * @return Status
     */
    public function getStatus()
    {
        if (!array_key_exists('status', $this->storage)) {
            $this->storage['status'] = $this->status;
        }

        return $this->storage['status'];
    }

    /**
     * @return Planejamento
     */
    public function getPlanoPai()
    {
        if (!array_key_exists('planoPai', $this->storage)) {
            $this->storage['planoPai'] = $this->planoPai;
        }

        return $this->storage['planoPai'];
    }

    /**
     * @return Planejamento[]
     */
    public function getPlanosFilhos()
    {
        if (!array_key_exists('planosFilho', $this->storage)) {
            $this->storage['planosFilho'] = $this->planosFilho;
        }

        return $this->storage['planosFilho'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'pl2_status', 'pl1_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comissoes()
    {
        return $this->hasMany(Comissao::class, 'pl3_planejamento', 'pl2_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areasResultado()
    {
        return $this->hasMany(AreaResultado::class, 'pl4_planejamento', 'pl2_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planoPai()
    {
        return $this->belongsTo(Planejamento::class, 'pl2_codigo_pai', 'pl2_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planosFilho()
    {
        return $this->hasMany(Planejamento::class, 'pl2_codigo_pai', 'pl2_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fatorCorrecaoDespesa()
    {
        return $this->hasMany(FatorCorrecaoDespesa::class, 'pl7_planejamento', 'pl2_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fatorCorrecaoReceita()
    {
        return $this->hasMany(FatorCorrecaoReceita::class, 'planejamento_id', 'pl2_codigo')
            ->orderBy('orcfontes_id')
            ->orderBy('exercicio');
    }

    public function estimativaReceita()
    {
        return $this->hasMany(EstimativaReceita::class, 'planejamento_id', 'pl2_codigo')
            ->orderBy('orcfontes_id')
            ->orderBy('anoorcamento');
    }

    /**
     * @return mixed
     */
    public function filhosAtivos()
    {
        return $this->planosFilho()
            ->where('pl2_ativo', '=', 't')
            ->orderBy('pl2_created_at')
            ->get();
    }

    /**
     * @param $query
     * @param $tipo
     * @return mixed
     */
    public function scopePlanoAprovado($query, $tipo)
    {
        return $query->where('pl2_tipo', '=', $tipo)
            ->where('pl2_status', '=', StatusEnum::APROVADO)
            ->where('pl2_ativo', '=', 't');
    }

    /**
     * Calcula os exercícios do planejamento
     * @return array
     */
    public function execiciosPlanejamento()
    {
        $anos = [];
        for ($ano = $this->pl2_ano_inicial; $ano <= $this->pl2_ano_final; $ano++) {
            $anos[] = $ano;
        }

        return $anos;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programas()
    {
        return $this->hasMany(ProgramaEstrategico::class, 'pl9_planejamento', 'pl2_codigo')
            ->orderBy('pl9_orcprograma');
    }
}
