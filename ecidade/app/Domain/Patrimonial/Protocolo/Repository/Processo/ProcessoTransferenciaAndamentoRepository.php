<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\Processo\ProcessoTransferenciaAndamento;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\ProcessoTransferenciaAndamentoRepository as RepositoryInterface; // @codingStandardsIgnoreLine

use cl_proctransand;
use Exception;

/**
* Classe repository do model que faz ligação entre um processo e um atendimento da ouvidoria
*
* @var string
*/
final class ProcessoTransferenciaAndamentoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = ProcessoTransferenciaAndamento::class;

    /**
     * @var cl_proctransand
     */
    private $dao;

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->dao = new cl_proctransand();
    }

    /**
     * Função que salva um novo registro
     *
     * @param ProcessoTransferenciaAndamento $model
     * @throws Exception
     */
    public function persist(
        ProcessoTransferenciaAndamento $model
    ) {

        $this->dao->p64_codandam = $model->getCodigoAndamento();
        $this->dao->p64_codtran  = $model->getCodigoTransferencia();

        $this->dao->incluir();

        if ($this->dao->erro_status == 0) {
            throw new \Exception($this->dao->erro_msg);
        }
    }
}
