<?php
namespace App\Domain\Tributario\ISSQN\Repository\AlvaraEventos;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\OrdemServicoFiscal;
use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\Contracts\OrdemServicoFiscalRepository as RepositoryInterface;

use Exception;

/**
* Classe abstrata para Repositorys. Usa eloquent
*
* @var string
*/
final class OrdemServicoFiscalRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = OrdemServicoFiscal::class;

    /**
     * Função que salva um novo registro
     *
     * @param ProcessoDocumento $model
     */
    public function persist(
        OrdemServicoFiscal $model
    ) {
        return $model->save();
    }

    /**
     * Função que busca todos registros de uma ordem de servico
     *
     * @param integer $ordemServicoId
     */
    public function findByOrdemServico($ordemServicoId)
    {
        $query = $this->newQuery()
                      ->select('q169_fiscal as codigo', 'usuario.nome as nome')
                      ->join('cadfiscais as fiscal', 'fiscal.id_usuario', 'q169_fiscal')
                      ->join('db_usuarios as usuario', 'fiscal.id_usuario', 'usuario.id_usuario')
                      ->where('q169_ordemservico', '=', $ordemServicoId);


        return $this->doQuery($query);
    }

    /**
     * Função que remove todos registros de uma ordem de servico
     *
     * @param integer $ordemServicoId
     */
    public function deleteByOrdemServico($ordemServicoId)
    {
        return OrdemServicoFiscal::where('q169_ordemservico', $ordemServicoId)->delete();
    }
}
