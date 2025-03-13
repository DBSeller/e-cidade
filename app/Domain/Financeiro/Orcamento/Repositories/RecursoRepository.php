<?php


namespace App\Domain\Financeiro\Orcamento\Repositories;

use App\Domain\Financeiro\Orcamento\Models\Recurso;
use cl_orctiporec;
use Exception;

/**
 * Class RecursoRepository
 * @package App\Domain\Financeiro\Orcamento\Repositories
 */
class RecursoRepository
{
    /**
     * @var cl_orctiporec
     */
    private $dao;

    /**
     * RecursoRepository constructor.
     * @param cl_orctiporec $dao
     */
    public function __construct(cl_orctiporec $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param Recurso $model
     * @return Recurso
     * @throws Exception
     */
    public function persist(Recurso $model)
    {
        $this->dao->o15_codigo = $model->getCodigo();
        $this->dao->o15_descr = $model->getDescricao();
        $this->dao->o15_codtri = $model->getCodigoTribunal();
        $this->dao->o15_finali = $model->getFinalidade();
        $this->dao->o15_tipo = $model->getTipoRecurso();

        $carbon = $model->getDataLimite();

        $data = '';
        if (!is_null($carbon)) {
            $data = $carbon->format('Y-m-d');
        }
        $this->dao->o15_datalimite = $data;
        $this->dao->o15_db_estruturavalor = $model->getDbEstruturaValor()->db121_sequencial;
        $this->dao->o15_codigosiconfi = $model->getCodigoSiconfi();
        $this->dao->o15_complemento = $model->getComplemento()->o200_sequencial;
        $this->dao->o15_loaidentificadoruso = $model->getLoaIdentificadorUso();
        $this->dao->o15_loatipo = $model->getLoaTipo();
        $this->dao->o15_loagrupo = $model->getLoaGrupo();
        $this->dao->o15_loaespecificacao = $model->getLoaEspecificacao();
        $this->dao->o15_recurso = $model->getRecurso();

        if (!empty($model->getCodigo())) {
            $this->dao->alterar($model->getCodigo());
        } else {
            $this->dao->incluir(null);
            $model->o15_codigo  = $this->dao->o15_codigo;
        }

        if ($this->dao->erro_status == 0) {
            throw new Exception($this->dao->erro_msg, 406);
        }

        return $model;
    }

    /**
     * @param Recurso $recurso
     * @return bool
     * @throws Exception
     */
    public function excluir(Recurso $recurso)
    {
        $this->dao->excluir($recurso->getCodigo());
        if ($this->dao->erro_status == 0) {
            throw new Exception($this->dao->erro_msg, 406);
        }

        return true;
    }
}
