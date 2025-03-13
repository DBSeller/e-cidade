<?php


namespace App\Domain\Financeiro\Planejamento\Models;

use App\Domain\Financeiro\Orcamento\Models\ProjetoAtividade;
use App\Domain\Financeiro\Orcamento\Models\Subtitulo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class Iniciativa
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $pl12_codigo
 * @property $pl12_orcprojativ
 * @property $pl12_anoorcamento
 * @property $pl12_programaestrategico
 * @property $pl12_origeminiciativa
 * @property $pl12_periodoacao
 * @property $pl12_valorbase
 * @property $created_at
 * @property $updated_at
 */
class Iniciativa extends Model
{
    protected $table = 'planejamento.iniciativaprojativ';
    protected $primaryKey = 'pl12_codigo';

    /**
     * @var Collection|Valor[]
     */
    protected $valores = [];
    /**
     * @var mixed
     */
    private $storage = [];

    /**
     * @return ProjetoAtividade
     */
    public function getIniciativaOrcamento()
    {
        if (!array_key_exists('iniciativaOrcamento', $this->storage)) {
            $this->storage['iniciativaOrcamento'] = ProjetoAtividade::
            where('o55_projativ', '=', $this->pl12_orcprojativ)
                ->where('o55_anousu', '=', $this->pl12_anoorcamento)
                ->first();
        }

        return $this->storage['iniciativaOrcamento'];
    }

    /**
     * @return MetasIniciativa[]
     */
    public function getMetas()
    {
        if (!array_key_exists('metas', $this->storage)) {
            $this->storage['metas'] = $this->metas;
        }

        return $this->storage['metas'];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $dados = parent::toArray();
        $iniciativaOrcamento = $this->getIniciativaOrcamento();
        $dados['acao'] = $iniciativaOrcamento->formataCodigo();
        $dados['descricao_acao'] = $iniciativaOrcamento->o55_descr;
        $dados['descricao_produto'] = $iniciativaOrcamento->produto->o22_descrprod;
        return $dados;
    }

    /**
     * @param Builder $query
     * @param integer $idProgramaEstrategico
     * @param integer $idAcao
     * @param null $id
     * @return Builder
     */
    public function scopeValidaAcaoJaCadastrada(Builder $query, $idProgramaEstrategico, $idAcao, $id = null)
    {
        return $query->where('pl12_programaestrategico', '=', $idProgramaEstrategico)
            ->where('pl12_orcprojativ', '=', $idAcao)
            ->when(!empty($id), function ($query) use ($id) {
                return $query->where('pl12_codigo', '!=', $id);
            });
    }

    /**
     * Filtra o órgão e unidade
     *
     * @param Builder $query
     * @param array $idsUnidades array de string onde o valor é órgão-unidade
     * @param bool $contem
     */
    public function scopeFiltrarOrgaoUnidade(Builder $query, array $idsUnidades, $contem = true)
    {
        $operador = $contem ? 'in' : 'not in';

        $filtroUnidades = [];
        foreach ($idsUnidades as $unidade) {
            $data = explode('-', $unidade);
            $filtroUnidades[] = sprintf(
                '(pl20_orcorgao %s (%s) and pl20_orcunidade %s (%s))',
                $operador,
                $data[0],
                $operador,
                $data[1]
            );
        }

        $where = '(' . implode(' or ', $filtroUnidades) . ')';

        $query->whereRaw("
            exists (
                select 1
                  from detalhamentoiniciativa
                 where pl20_iniciativaprojativ = pl12_codigo
                 and {$where}
        )");
    }

    /**
     * Filtra a função
     *
     * @param Builder $query
     * @param array $idsFuncoes array de string onde o valor é o codigo da função
     * @param bool $contem
     */
    public function scopeFiltrarFuncao(Builder $query, array $idsFuncoes, $contem = true)
    {
        $operador = $contem ? 'in' : 'not in';

        $lista = implode(', ', $idsFuncoes);
        $query->whereRaw("
            exists (
                        select 1
                          from detalhamentoiniciativa
                         where pl20_iniciativaprojativ = pl12_codigo
                            and pl20_orcfuncao {$operador} ({$lista})
                        )
            ");
    }

    /**
     * Filtra a subfunção
     *
     * @param Builder $query
     * @param array $idsSubFuncoes array de string onde o valor é o codigo da subfunção
     * @param bool $contem
     */
    public function scopeFiltrarSubFuncao(Builder $query, array $idsSubFuncoes, $contem = true)
    {
        $operador = $contem ? 'in' : 'not in';
        $lista = implode(', ', $idsSubFuncoes);
        $query->whereRaw("
            exists (
                select 1
                          from detalhamentoiniciativa
                         where pl20_iniciativaprojativ = pl12_codigo
                            and pl20_orcsubfuncao {$operador} ({$lista})
                        )
            ");
    }

    /**
     * Filtra o elemento
     *
     * @param Builder $query
     * @param array $idsSubFuncoes array de string onde o valor é o codigo do elemento
     * @param bool $contem
     */
    public function scopeFiltrarElemento(Builder $query, array $idsElementos, $contem = true)
    {
        $operador = $contem ? 'in' : 'not in';

        $lista =  "'".implode("', '", $idsElementos) . "'";
        $query->whereRaw("
            exists (
                select 1
                  from detalhamentoiniciativa
                  join orcelemento on (o56_codele, o56_anousu) = (pl20_orcelemento, pl20_anoorcamento)
                 where pl20_iniciativaprojativ = pl12_codigo
                    and o56_elemento {$operador} ({$lista})
                )
            ");
    }

    /**
     * Filtra o recurso
     *
     * @param Builder $query
     * @param array $idsSubFuncoes array de string onde o valor é o codigo do recurso
     * @param bool $contem
     */
    public function scopeFiltrarRecurso(Builder $query, array $idsERecursos, $contem = true)
    {
        $operador = $contem ? 'in' : 'not in';
        $lista =  implode(", ", $idsERecursos);
        $query->whereRaw("
            exists (
                select 1
                  from detalhamentoiniciativa
                 where pl20_iniciativaprojativ = pl12_codigo
                    and pl20_recurso {$operador} ({$lista})
            )
        ");
    }

    public function scopeFiltrarInstituicoes(Builder $query, array $instituicoes)
    {
        $lista =  implode(", ", $instituicoes);
        $query->whereRaw("
            exists (
                select 1
                  from detalhamentoiniciativa
                 where pl20_iniciativaprojativ = pl12_codigo
                    and pl20_instituicao in ({$lista})
            )
        ");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function programaEstrategico()
    {
        return $this->belongsTo(ProgramaEstrategico::class, 'pl12_programaestrategico', 'pl9_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'pl12_periodoacao', 'pl14_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function origem()
    {
        return $this->belongsTo(Origem::class, 'pl12_origeminiciativa', 'pl13_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function metas()
    {
        return $this->hasMany(MetasIniciativa::class, 'iniciativaprojativ_id', 'pl12_codigo')
            ->orderBy('exercicio');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|DetalhamentoDespesa[]
     */
    public function detalhamentoDespesa()
    {
        return $this->hasMany(DetalhamentoDespesa::class, 'pl20_iniciativaprojativ', 'pl12_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function regionalizacoes()
    {
        return $this->belongsToMany(
            Subtitulo::class,
            'planejamento.iniciativaprojativppasubtitulolocalizador',
            'pl25_iniciativaprojativ',
            'pl25_ppasubtitulolocalizadorgasto'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function abrangencias()
    {
        return $this->belongsToMany(
            Abrangencia::class,
            'planejamento.abrangenciainiciativaprojativ',
            'pl19_iniciativaprojativ',
            'pl19_abrangencia'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function objetivos()
    {
        return $this->belongsToMany(
            ObjetivoProgramaEstrategico::class,
            "planejamento.iniciativaobjetivosprogramaestrategico",
            "pl16_iniciativaprojativ",
            "pl16_objetivosprogramaestrategico"
        );
    }
}
