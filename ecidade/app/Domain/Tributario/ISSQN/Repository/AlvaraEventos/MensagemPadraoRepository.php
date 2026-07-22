<?php
namespace App\Domain\Tributario\ISSQN\Repository\AlvaraEventos;

use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\MensagemPadrao;
use App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\Contracts\MensagemPadraoRepository as RepositoryInterface;

use Exception;

/**
* Classe abstrata para Repositorys. Usa eloquent
*
* @var string
*/
final class MensagemPadraoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for MensagemPadrao.
     *
     * @var string
     */
    protected $modelClass = MensagemPadrao::class;

    /**
     * Função que salva um novo registro
     *
     * @param ProcessoDocumento $model
     */
    public function persist(
        MensagemPadrao $model
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
        return MensagemPadrao::destroy($id);
    }
}
