<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo;

use cl_processoouvidoria;
use Illuminate\Database\Eloquent\Builder as EloquentQueryBuilder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Pagination\AbstractPaginator as Paginator;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Core\Base\Repository\Contracts\BaseSaveRepository;
use App\Domain\Patrimonial\Protocolo\Model\Processo\ProcessoOuvidoria;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\ProcessoOuvidoriaRepository as RepositoryInterface;

use Exception;

/**
* Classe repository do model que faz ligação entre um processo e um atendimento da ouvidoria
*
* @var string
*/
final class ProcessoOuvidoriaRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = ProcessoOuvidoria::class;

    /**
     * @var cl_processoouvidoria
     */
    private $dao;

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->dao = new cl_processoouvidoria();
    }

    /**
     * Função que salva um novo registro
     *
     * @param ProcessoOuvidoria $model
     */
    public function persist(
        ProcessoOuvidoria $model
    ) {

        $this->dao->ov09_protprocesso         = $model->getProcesso();
        $this->dao->ov09_ouvidoriaatendimento = $model->getAtendimento();
        $this->dao->ov09_principal            = ($model->getPrincipal() == true) ? 't' : 'f';

        if (!empty($model->getCodigo())) {
            $this->dao->ov09_sequencial = $model->getProcesso();
            $this->dao->alterar($model->getCodigo());
        } else {
            $this->dao->incluir(null);
        }

        if ($this->dao->erro_status == 0) {
            throw new \Exception($this->dao->erro_msg);
        }
    }
}
