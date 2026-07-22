<?php
namespace App\Domain\Patrimonial\Ouvidoria\Repository\Cidadao;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Ouvidoria\Model\Cidadao\Cidadao;
use App\Domain\Patrimonial\Ouvidoria\Repository\Cidadao\Contracts\CidadaoRepository as RepositoryInterface;

use Exception;

/**
* Classe abstrata para Repositorys. Usa eloquent
*
* @var string
*/
final class CidadaoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = Cidadao::class;

    /**
     * Retrieves a record by his id
     * If fail is true $ fires ModelNotFoundException.
     *
     * @param int  $id
     * @param bool $fail
     *
     * @return Model
     */
    public function find($id)
    {
        return $this->newQuery()
                    ->where('ov02_sequencial', '=', $id)
                    ->orderBy('ov02_seq', 'desc')
                    ->first();
    }
}
