<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\Processo\ArquivamentoAndamento;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\ArquivamentoAndamentoRepository as RepositoryInterface; // @codingStandardsIgnoreLine

use cl_arqandam;
use Exception;

/**
* Classe repository do model ArquivamentoAndamento
*
* @var string
*/
final class ArquivamentoAndamentoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = ArquivamentoAndamento::class;

    /**
     * @var cl_arqandam
     */
    private $dao;

    /**
     * Construtor da classe
     *
     * @param cl_proctransferproc $dao
     */
    public function __construct()
    {
        $this->dao = new cl_arqandam();
    }

    /**
     * Função que salva um novo registro
     *
     * @param ArquivamentoAndamento $model
     */
    public function persist(
        ArquivamentoAndamento $model
    ) {
        $this->dao->p69_codarquiv = $model->getArquivamento();
        $this->dao->p69_codandam  = $model->getAndamento();
        $this->dao->p69_arquivado = ($model->getArquivado()) ? 'true' : 'false';

        $this->dao->incluir(null);

        if ($this->dao->erro_status == 0) {
            throw new \Exception($this->dao->erro_msg);
        }
    }
}
