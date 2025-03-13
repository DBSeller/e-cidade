<?php
namespace App\Domain\Tributario\ISSQN\Repository\AlvaraEventos;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\AlvaraEvento;
use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\Contracts\AlvaraEventoRepository as RepositoryInterface;

use Exception;

/**
* Classe abstrata para Repositorys. Usa eloquent
*
* @var string
*/
final class AlvaraEventoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for AlvaraEvento.
     *
     * @var string
     */
    protected $modelClass = AlvaraEvento::class;

    /**
     * Função que salva um novo registro
     *
     * @param ProcessoDocumento $model
     */
    public function persist(
        AlvaraEvento $model
    ) {
        return $model->save();
    }

    /**
     * Função que remove um registro
     *
     * @param integer $model
     */
    public function delete($id)
    {
        return AlvaraEvento::destroy($id);
    }

    /**
     * Função que retorna um registro
     *
     * @param integer $id
     */
    public function getAlvaraEvento($id)
    {
        $query = $this->newQuery();
        $query->select(
            "alvaraevento.*",
            "q98_descricao as tipoevento"
        );

        $query->join('issqn.isstipoalvara', 'q170_tipoalvara', 'q98_sequencial')
              ->where('q170_codigo', '=', $id);

        return $query->first();
    }
}
