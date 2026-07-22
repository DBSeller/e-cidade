<?php
namespace App\Domain\Core\Base\Repository;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Core\Base\Repository\Contracts\BaseRepository as BaseRepositoryInterface;

use Exception;

/**
* Classe abstrata para Repositorys. Usa eloquent
*
* @var string
*/
abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass;

    /**
     * @return EloquentQueryBuilder|QueryBuilder
     */
    protected function newQuery()
    {
        return app($this->modelClass)->newQuery();
    }

    /**
     * @param EloquentQueryBuilder|QueryBuilder $query
     * @param int                               $take
     * @param bool                              $paginate
     *
     * @return EloquentCollection|Paginator
     */
    protected function doQuery($query = null, $take = false, $paginate = false)
    {
        if (is_null($query)) {
            $query = $this->newQuery();
        }

        if (true == $paginate) {
            return $query->paginate($take);
        }

        if ($take > 0 || false !== $take) {
            $query->take($take);
        }

        return $query->get();
    }

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
        return $this->newQuery()->find($id);
    }

    /**
     * Returns all records.
     * If $take is false then brings all records
     * If $paginate is true returns Paginator instance.
     *
     * @param int  $take
     * @param bool $paginate
     *
     * @return EloquentCollection|Paginator
     */
    public function findAll($take = false, $paginate = false)
    {
        return $this->doQuery(null, $take, $paginate);
    }
}
