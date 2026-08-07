<?php


namespace App\Domain\Financeiro\Orcamento\Repositories;

use App\Domain\Financeiro\Orcamento\Models\ValorEstrutural;
use cl_db_estruturavalor;
use Exception;

/**
 * Class ValorEstruturalRepository
 * @package App\Domain\Financeiro\Orcamento\Repositories
 */
class ValorEstruturalRepository
{
    /**
     * @var cl_db_estruturavalor
     */
    private $dao;

    /**
     * RecursoRepository constructor.
     * @param cl_db_estruturavalor $dao
     */
    public function __construct(cl_db_estruturavalor $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @todo foi implementado apenas o necessário para salvar o valor estrutural ao cadastrar um recurso
     * @param ValorEstrutural $model
     * @return ValorEstrutural
     */
    public function salvar(ValorEstrutural $model)
    {
        $this->dao->db121_sequencial = $model->db121_sequencial;
        $this->dao->db121_db_estrutura = $model->db121_db_estrutura;
        $this->dao->db121_estrutural = $model->db121_estrutural;
        $this->dao->db121_descricao = $model->db121_descricao;
        $this->dao->db121_estruturavalorpai = $model->db121_estruturavalorpai;
        $this->dao->db121_nivel = $model->db121_nivel;
        $this->dao->db121_tipoconta = $model->db121_tipoconta;

        $this->dao->incluir(null);

        if ($this->dao->erro_status == 0) {
            throw new Exception($this->dao->erro_msg);
        }

        $model->db121_sequencial = $this->dao->db121_sequencial;

        return $model;
    }
}
