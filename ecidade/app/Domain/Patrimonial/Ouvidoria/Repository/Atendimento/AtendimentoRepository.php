<?php

namespace App\Domain\Patrimonial\Ouvidoria\Repository\Atendimento;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Ouvidoria\Model\Atendimento\Atendimento;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter\ListagemProcessos as FiltroListagemProcessos;
use App\Domain\Patrimonial\Ouvidoria\Repository\Atendimento\Contracts\AtendimentoRepository as RepositoryInterface;

use Exception;

/**
 * Classe abstrata para Repositorys. Usa eloquent
 *
 * @var string
 */
final class AtendimentoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = Atendimento::class;

    /**
     * Função que busca os processos da ouvidoria que não tem um atendimento vinculado
     *
     * @param FiltroListagemProcessos $filtroProcesso
     * @return EloquentCollection|Paginator
     */
    public function buscarProcessosOuvidoria(FiltroListagemProcessos $filtroProcesso)
    {
        $query = $this->newQuery();

        $query->select(
            "ov01_sequencial as sequencial",
            "ov01_tipoprocesso as tipo_processo",
            "p51_descr as tipo_processo_descricao",
            "ov02_nome as solicitante",
            DB::raw("ov01_numero||'/'||ov01_anousu as processo"),
            "ov01_dataatend as data",
            "ov01_numero as numero",
            "ov01_anousu as ano"
        );

        $this->joinsProcessoOuvidoria($query);
        $this->whereProcessoOuvidoria($query, $filtroProcesso);

        $query->orderBy('sequencial');

        return $this->doQuery($query);
    }

    /**
     * Função que busca os dados de uma solicitação da ouvidoria
     *
     * @param FiltroListagemProcessos $filtroProcesso
     * @return EloquentCollection|Paginator
     */
    public function buscarSolicitacaoOuvidoria(FiltroListagemProcessos $filtroProcesso)
    {
        $query = $this->newQuery();

        $query->select(
            "ov01_sequencial as sequencial",
            "ov01_tipoprocesso as tipo_processo",
            "ov02_sequencial",
            DB::raw("ov01_numero||'/'||ov01_anousu as processo"),
            "ov33_informacoesprocesso as metadados",
            "p43_formareclamacao as formareclamacao",
            "ov01_numero as numero_processo",
            "ov01_anousu as ano_processo",
            "ov01_depart as departamento",
            "ov33_client_atendimento_id"
        );

        $this->joinsProcessoOuvidoria($query);
        $this->whereProcessoOuvidoria($query, $filtroProcesso);

        return $query->first();
    }

    /**
     * @throws Exception
     */
    public function buscarSolicitacaoOuvidoriaLegacy(FiltroListagemProcessos $filtroProcesso)
    {
        $ouvidoriaAtendimento = new \cl_ouvidoriaatendimento();

        return $ouvidoriaAtendimento->buscarSolicitacaoOuvidoria($this->whereProcessoOuvidoriaLegacy($filtroProcesso));
    }

    /**
     * Função auxiliar que monta os joins usados nos processos da ouvidoria
     *
     * @param EloquentQueryBuilder|QueryBuilder $query
     * @return EloquentCollection|Paginator
     */
    private function joinsProcessoOuvidoria($query)
    {
        $query->join('ouvidoriaatendimentoprocessoeletronico', 'ov33_ouvidoriaatendimento', 'ov01_sequencial')
            ->join('tipoproc', 'p51_codigo', 'ov01_tipoprocesso')
            ->leftJoin('ouvidoriaatendimentocidadao', 'ov10_ouvidoriaatendimento', 'ov01_sequencial')
            ->leftJoin('tipoprocformareclamacao', 'p43_tipoproc', 'ov01_tipoprocesso')
            ->leftJoin('cidadao', function ($join) {
                $join->on([
                    ['ov02_sequencial', '=', 'ov10_cidadao'],
                    ['ov02_seq', '=', 'ov10_seq']
                ]);
            })->leftJoin('processoouvidoria', 'ov09_ouvidoriaatendimento', 'ov01_sequencial');
    }

    /**
     * Função auxiliar que monta os joins usados nos processos da ouvidoria
     *
     * @param EloquentQueryBuilder|QueryBuilder $query
     * @param FiltroListagemProcessos $filtroProcesso
     * @return EloquentCollection|Paginator
     */
    private function whereProcessoOuvidoria($query, $filtroProcesso)
    {
        if (!empty($filtroProcesso->getSequencial())) {
            $query->where('ov01_sequencial', '=', $filtroProcesso->getSequencial());
        }

        if (!empty($filtroProcesso->getCodigoInstituicao())) {
            $query->where('ov01_instit', '=', $filtroProcesso->getCodigoInstituicao());
        }

        if (!empty($filtroProcesso->getCodigoDepartamento())) {
            $query->whereIn('p51_codigo', function ($query) use ($filtroProcesso) {
                $query->select('p41_tipoproc')
                    ->from('tipoprocdepto')
                    ->where('p41_coddepto', '=', $filtroProcesso->getCodigoDepartamento());
            });
        }

        if (!empty($filtroProcesso->getCodigoProcessoProtocolo())) {
            $query->where('ov09_protprocesso', '=', $filtroProcesso->getCodigoProcessoProtocolo());
        } else {
            $query->where('ov09_ouvidoriaatendimento', null);
        }

        if (!empty($filtroProcesso->getDataInicio())) {
            if ($filtroProcesso->getDataInicio() instanceof \DBDate) {
                $query->where("ov01_dataatend", ">=", $filtroProcesso->getDataInicio()->getDate());
            } else {
                $query->where("ov01_dataatend", ">=", $filtroProcesso->getDataInicio());
            }
        }

        if (!empty($filtroProcesso->getDataFim())) {
            if ($filtroProcesso->getDataFim() instanceof \DBDate) {
                $query->where("ov01_dataatend", "<=", $filtroProcesso->getDataFim()->getDate());
            } else {
                $query->where("ov01_dataatend", "<=", $filtroProcesso->getDataFim());
            }
        }

        if (!empty($filtroProcesso->getNumeroProcesso())) {
            $query->where('ov01_numero', '=', $filtroProcesso->getNumeroProcesso());
        }

        if (!empty($filtroProcesso->getAnoProcesso())) {
            $query->where('ov01_anousu', '=', $filtroProcesso->getAnoProcesso());
        }

        if (!empty($filtroProcesso->getUltimoSequencial())) {
            $query->where('ov01_sequencial', '>', $filtroProcesso->getUltimoSequencial());
        }
    }

    private function whereProcessoOuvidoriaLegacy(FiltroListagemProcessos $filtroProcesso)
    {
        $aWhere = [];

        if (!empty($filtroProcesso->getSequencial())) {
            $aWhere[] = "ov01_sequencial = {$filtroProcesso->getSequencial()}";
        }

        if (!empty($filtroProcesso->getCodigoInstituicao())) {
            $aWhere[] = "ov01_instit = {$filtroProcesso->getCodigoInstituicao()}";
        }

        if (!empty($filtroProcesso->getCodigoDepartamento())) {
            $oDaoTipoprocdepto = new \cl_tipoprocdepto();
            $sWhere = "p41_coddepto = {$filtroProcesso->getCodigoDepartamento()}";

            $rTipoprocdepto = db_query(
                $oDaoTipoprocdepto->sql_query_file(null, "*", null, $sWhere)
            );

            $aTipoprocdepto = \db_utils::getCollectionByRecord($rTipoprocdepto);

            $aTipoProc = array_map(function ($oTipoprocdepto) {
                return $oTipoprocdepto->p41_tipoproc;
            }, $aTipoprocdepto);

            $sTipoProc = implode(", ", $aTipoProc);
            $aWhere[] = "p51_codigo IN ({$sTipoProc})";
        }

        if (!empty($filtroProcesso->getCodigoProcessoProtocolo())) {
            $aWhere[] = "ov09_protprocesso = {$filtroProcesso->getCodigoProcessoProtocolo()}";
        } else {
            $aWhere[] = "ov09_ouvidoriaatendimento IS NULL";
        }

        if (!empty($filtroProcesso->getDataInicio())) {
            if ($filtroProcesso->getDataInicio() instanceof \DBDate) {
                $aWhere[] = "ov01_dataatend >= {$filtroProcesso->getDataInicio()->getDate()}";
            } else {
                $aWhere[] = "ov01_dataatend >= {$filtroProcesso->getDataInicio()}";
            }
        }

        if (!empty($filtroProcesso->getDataFim())) {
            if ($filtroProcesso->getDataFim() instanceof \DBDate) {
                $aWhere[] = "ov01_dataatend <= {$filtroProcesso->getDataFim()->getDate()}";
            } else {
                $aWhere[] = "ov01_dataatend <= {$filtroProcesso->getDataFim()}";
            }
        }

        if (!empty($filtroProcesso->getNumeroProcesso())) {
            $aWhere[] = "ov01_numero = {$filtroProcesso->getNumeroProcesso()}";
        }

        if (!empty($filtroProcesso->getAnoProcesso())) {
            $aWhere[] = "ov01_anousu = {$filtroProcesso->getAnoProcesso()}";
        }

        if (!empty($filtroProcesso->getUltimoSequencial())) {
            $aWhere[] = "ov01_sequencial > {$filtroProcesso->getUltimoSequencial()}";
        }

        return implode(" AND ", $aWhere);
    }
}
