<?php
namespace App\Domain\Tributario\ISSQN\Repository\AlvaraEventos;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\OrdemServico;
use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\Contracts\OrdemServicoRepository as RepositoryInterface;

use Exception;

/**
* Classe abstrata para Repositorys. Usa eloquent
*
* @var string
*/
final class OrdemServicoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = OrdemServico::class;

    /**
     * Função que salva um novo registro
     *
     * @param ProcessoDocumento $model
     */
    public function persist(
        OrdemServico $model
    ) {
        return $model->save();
    }

    /**
     * Função que remove um registro
     *
     * @param integer $id
     */
    public function delete($id)
    {
        return OrdemServico::destroy($id);
    }

    /**
     * Função que retorna um registro
     *
     * @param integer $id
     */
    public function getOrdemServico($id)
    {
        $query = $this->newQuery();
        $query->select(
            "ordemservico.*",
            DB::raw("p58_numero||'/'||p58_ano as processo"),
            "p58_requer as requerente",
            "xcgm.z01_numcgm as cgm_codigo",
            "xcgm.z01_nome as cgm",
            "isscgm.z01_numcgm as inscricao_cgm_codigo",
            "isscgm.z01_nome as inscricao"
        );

        $query->leftJoin('protocolo.protprocesso', 'q168_processo', 'p58_codproc')
              ->leftJoin('protocolo.cgm as xcgm', 'q168_cgm', 'xcgm.z01_numcgm')
              ->leftJoin('issqn.issbase as iss', 'q168_inscricao', 'iss.q02_inscr')
              ->leftJoin('protocolo.cgm as isscgm', 'q02_numcgm', 'isscgm.z01_numcgm')
              ->where('q168_codigo', '=', $id);

        return $query->first();
    }
}
