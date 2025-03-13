<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\Processo\ArquivamentoProcesso;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\ArquivamentoProcessoRepository as RepositoryInterface; // @codingStandardsIgnoreLine

use cl_arqproc;
use Exception;

/**
* Classe repository do model ArquivamentoProcesso
*
* @var string
*/
final class ArquivamentoProcessoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = ArquivamentoProcesso::class;
    /**
     * @var cl_arqproc
     */
    private $dao;

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->dao = new cl_arqproc();
    }

    /**
     * Função que salva um novo registro
     *
     * @param ArquivamentoProcesso $model
     */
    public function persist(
        ArquivamentoProcesso $model
    ) {

        $this->dao->incluir(
            $model->getArquivamento(),
            $model->getProcesso()
        );

        if ($this->dao->erro_status == 0) {
            throw new \Exception($this->dao->erro_msg);
        }
    }
}
