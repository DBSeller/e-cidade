<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\Processo\Processo;
use App\Domain\Patrimonial\Protocolo\Model\Processo\ProcessoTransferencia;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\ProcessoTransferenciaRepository as RepositoryInterface; // @codingStandardsIgnoreLine

use cl_proctransferproc;
use Exception;

/**
* Classe repository do model que faz ligação entre um processo e um atendimento da ouvidoria
*
* @var string
*/
final class ProcessoTransferenciaRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = ProcessoTransferencia::class;

    /**
     * @var cl_proctransferproc
     */
    private $dao;

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->dao = new cl_proctransferproc();
    }

    /**
     * Função que salva um novo registro
     *
     * @param ProcessoTransferencia $model
     * @throws Exception
     */
    public function persist(
        ProcessoTransferencia $model
    ) {
        $this->dao->incluir(
            $model->getCodigoTransferencia(),
            $model->getCodigoProcesso()
        );

        if ($this->dao->erro_status == 0) {
            throw new \Exception($this->dao->erro_msg);
        }
    }

    /**
     * Função que retorna a posição atual de um processo
     *
     * @param Processo $model
     * @return int $count
     */
    public function getPosicaoAtualProcesso(Processo $model)
    {
        return $this->newQuery()
                    ->where("p63_codproc", "=", $model->getCodigoProcesso())
                    ->count();
    }
}
