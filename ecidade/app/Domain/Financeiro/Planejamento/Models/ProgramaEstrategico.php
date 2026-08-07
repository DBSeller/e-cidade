<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use App\Domain\Configuracao\Usuario\Models\Usuario;
use App\Domain\Financeiro\Orcamento\Models\Programa;
use ECidade\Enum\Financeiro\Orcamento\TipologiaProgramaEnum;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class ProgramaEstrategico
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl9_codigo
 * @property $pl9_planejamento
 * @property $pl9_orcprograma
 * @property $pl9_anoorcamento
 * @property $pl9_valorbase
 * @property $created_at
 * @property $updated_at
 */
class ProgramaEstrategico extends Model
{
    protected $table = 'planejamento.programaestrategico';
    protected $primaryKey = 'pl9_codigo';

    private $storage = [];

    /**
     * @return Planejamento
     */
    public function getPlanejamento()
    {
        if (array_key_exists('planejamento', $this->storage)) {
            $this->storage['planejamento'] = $this->planejamento;
        }
        return $this->storage['planejamento'];
    }

    /**
     * Retorna os valores do programa estratégico
     * @return \Illuminate\Support\Collection
     */
    public function getValores()
    {
        if (!array_key_exists('valores', $this->storage)) {
            $this->storage['valores'] = Valor::where('pl10_chave', '=', $this->pl9_codigo)
                ->where('pl10_origem', '=', Valor::ORIGEM_PROGRAMA)
                ->orderBy('pl10_ano')
                ->get();
        }

        return $this->storage['valores'];
    }

    /**
     * Retorna o programa
     * @return Programa|Model|\Illuminate\Database\Query\Builder|mixed|null
     */
    public function getProgramaOrcamento()
    {
        if (!array_key_exists('programaOrcamento', $this->storage)) {
            $this->storage['programaOrcamento'] = Programa::
            where('o54_programa', '=', $this->pl9_orcprograma)
                ->where('o54_anousu', '=', $this->pl9_anoorcamento)
                ->first();
        }

        return $this->storage['programaOrcamento'];
    }

    /**
     * @return ObjetivoEstrategico
     */
    public function getObjetivos()
    {
        if (!array_key_exists('objetivos', $this->storage)) {
            $this->storage['objetivos'] = $this->objetivos;
        }
        return $this->storage['objetivos'];
    }
    /**
     * @return IndicadorProgramaEstrategico
     */
    public function getIndicadores()
    {
        if (!array_key_exists('indicadores', $this->storage)) {
            $this->storage['indicadores'] = $this->indicadores;
        }
        return $this->storage['indicadores'];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function toArray()
    {
        $this->orgaos;
        $programa = parent::toArray();
        $programaOrcamento = $this->getProgramaOrcamento();

        $programa['programa'] = $programaOrcamento->formataCodigo();
        $programa['tipologia'] = $programaOrcamento->getTipologia()->name();
        $programa['descricao'] = $programaOrcamento->o54_descr;
        $programa['valores'] = [];
        if (array_key_exists('valores', $this->storage)) {
            $programa['valores'] = $this->getValores()->toArray();
        }

        return $programa;
    }

    /**
     * Valida se o usuário possui permissões nos órgãos dos programas
     * @param $query
     * @param $idUsuario
     * @param $ano
     * @throws Exception
     */
    public function scopeValidaPermissaoUsuario($query, $idUsuario, $ano)
    {
        if (empty($idUsuario) || empty($ano)) {
            throw new Exception(
                'Para filtrar os programas em que o usuário tem permissão, você deve informar o ID do usuário.'
            );
        }

        $idsOrgaos = Usuario::getOrgaosLiberadoUsuario($idUsuario, $ano);

        $query->whereExists(function ($query) use ($idsOrgaos) {
            $query->select(DB::raw(1))
                ->from('planejamento.orgaoprogramaestregico')
                ->whereRaw('pl27_programaestrategico = pl9_codigo')
                ->whereIn('pl27_orcorgao', $idsOrgaos);
        });
    }

    /**
     * Retorna apenas os programas temáticos/finalisticos
     * @param Builder $query
     */
    public function scopeApenasProgramasTematicos(Builder $query)
    {
        $query->whereExists(function ($query) {
            $tipologia = [TipologiaProgramaEnum::PROGRAMAS_FINALISTICOS, TipologiaProgramaEnum::PROGRAMAS_TEMATICOS];
            $query->select(DB::raw(1))
                ->from('orcamento.orcprograma')
                ->whereRaw('o54_programa = pl9_orcprograma')
                ->whereRaw('o54_anousu = pl9_anoorcamento')
                ->whereIn('o54_tipoprograma', $tipologia);
        });
    }
    /**
     * Retorna apenas os programas temáticos/finalisticos
     * @param Builder $query
     */
    public function scopeApenasProgramasGestao(Builder $query)
    {
        $query->whereExists(function ($query) {
            $tipologia = [TipologiaProgramaEnum::PROGRAMAS_APOIO_POLITICAS, TipologiaProgramaEnum::PROGRAMAS_GESTAO];
            $query->select(DB::raw(1))
                ->from('orcamento.orcprograma')
                ->whereRaw('o54_programa = pl9_orcprograma')
                ->whereRaw('o54_anousu = pl9_anoorcamento')
                ->whereIn('o54_tipoprograma', $tipologia);
        });
    }

    /**
     * Filtra um órgão
     * @param Builder $query
     * @param integer $idOrgao
     */
    public function scopePossuiOrgao(Builder $query, $idOrgao)
    {
        $query->whereExists(function ($query) use ($idOrgao) {
            $query->select(DB::raw(1))
                ->from('planejamento.orgaoprogramaestregico')
                ->whereRaw('pl27_programaestrategico = pl9_codigo')
                ->where('pl27_orcorgao', '=', $idOrgao);
        });
    }

    /**
     * Filtra uma lista de órgãos
     * @param Builder $query
     * @param array $idsOrgaos
     */
    public function scopePossuiOrgaos(Builder $query, array $idsOrgaos)
    {
        $query->whereExists(function ($query) use ($idsOrgaos) {
            $query->select(DB::raw(1))
                ->from('planejamento.orgaoprogramaestregico')
                ->whereRaw('pl27_programaestrategico = pl9_codigo')
                ->whereIn('pl27_orcorgao', $idsOrgaos);
        });
    }

    public function scopeNaoPossuiOrgaos(Builder $query, array $idsOrgaos)
    {
        $lista = implode(', ', $idsOrgaos);
        $query->whereRaw("
        not exists(
            select 1
              from planejamento.orgaoprogramaestregico
             where pl27_programaestrategico = pl9_codigo
               and pl27_orcorgao in ({$lista}))
        ");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planejamento()
    {
        return $this->belongsTo(Planejamento::class, 'pl9_planejamento', 'pl2_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function iniciativas()
    {
        return $this->hasMany(Iniciativa::class, 'pl12_programaestrategico', 'pl9_codigo')
            ->orderBy('pl12_orcprojativ');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orgaos()
    {
        return $this->hasMany(OrgaoPrograma::class, 'pl27_programaestrategico', 'pl9_codigo')
            ->orderBy('pl27_orcorgao');
    }

    /**
     * objetivos do programa estratégico
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function objetivos()
    {
        return $this->hasMany(ObjetivoProgramaEstrategico::class, 'pl11_programaestrategico', 'pl9_codigo')
            ->with('metas')
            ->orderBy('pl11_numero');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function indicadores()
    {
        return $this->hasMany(IndicadorProgramaEstrategico::class, 'pl22_programaestrategico', 'pl9_codigo')
            ->with('indicador')
            ->orderBy('pl22_codigo');
    }

    /**
     * Areas de resultado
     */
    public function areasResultado()
    {
        return $this->belongsToMany(
            AreaResultado::class,
            'planejamento.arearesultadoprograma',
            'programaestrategico_id',
            'arearesultado_id'
        );
    }

    /**
     * Objetivos estratégicos vinculado ao programa estratégico
     */
    public function objetivosEstrategicos()
    {
        return $this->belongsToMany(
            ObjetivoEstrategico::class,
            'planejamento.objetivoestrategicoprograma',
            'pl6_programaestrategico',
            'pl6_objetivoestrategico'
        );
    }
}
