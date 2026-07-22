<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Patrimonial\Protocolo\Model\Processo\Arquivamento;
use App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\ArquivamentoRepository as RepositoryInterface; // @codingStandardsIgnoreLine

use cl_procarquiv;
use Exception;

/**
* Classe repository do model Arquivamento
*
* @var string
*/
final class ArquivamentoRepository extends BaseRepository implements RepositoryInterface
{
    /**
     * Model class for repo.
     *
     * @var string
     */
    protected $modelClass = Arquivamento::class;
    /**
     * @var cl_procarquiv
     */
    private $dao;

    /**
     * Construtor da classe
     *
     * @param cl_proctransferproc $dao
     */
    public function __construct()
    {
        $this->dao = new cl_procarquiv();
    }

    /**
     * Função que salva um novo registro
     *
     * @param Arquivamento $model
     */
    public function persist(
        Arquivamento $model
    ) {

        $this->dao->p67_codproc    = $model->getProcesso();
        $this->dao->p67_id_usuario = $model->getUsuario();
        $this->dao->p67_coddepto   = $model->getDepartamento();
        $this->dao->p67_dtarq      = $model->getData();
        $this->dao->p67_historico  = $model->getHistorico();

        if (!empty($model->getCodigo())) {
            $this->dao->p67_codarquiv = $model->getCodigo();
            $this->dao->alterar($model->getCodigo());
        } else {
            $this->dao->incluir(null);
            $model->setCodigo($this->dao->p67_codarquiv);
        }

        if ($this->dao->erro_status == 0) {
            throw new \Exception($this->dao->erro_msg);
        }
    }
}
