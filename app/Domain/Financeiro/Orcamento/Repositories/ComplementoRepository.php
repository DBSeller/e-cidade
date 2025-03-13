<?php


namespace App\Domain\Financeiro\Orcamento\Repositories;

use App\Domain\Financeiro\Orcamento\Models\Complemento;
use cl_complementofonterecurso;
use Exception;

/**
 * Class ComplementoRepository
 * @package App\Domain\Financeiro\Orcamento\Repositories
 */
class ComplementoRepository
{
    /**
     * @var cl_complementofonterecurso
     */
    private $dao;

    /**
     * RecursoRepository constructor.
     * @param cl_complementofonterecurso $dao
     */
    public function __construct(cl_complementofonterecurso $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param Complemento $complemento
     * @return Complemento
     * @throws Exception
     */
    public function persist(Complemento $complemento)
    {
        $this->dao->o200_sequencial = $complemento->getCodigoAttribute();
        $this->dao->o200_descricao = $complemento->getDescricaoAttribute();
        $this->dao->o200_msc = $complemento->getMscAttribute() ? 't' : 'f';
        $this->dao->o200_tribunal = $complemento->getTribunalAttribute() ? 't' : 'f';

        if (is_null(Complemento::find($complemento->getCodigoAttribute()))) {
            $this->dao->incluir(null);
            $complemento->o200_sequencial = $this->dao->o200_sequencial;
        } else {
            $this->dao->alterar($this->dao->o200_sequencial);
        }

        if ($this->dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Complemento de recurso." . $this->dao->erro_msg);
        }

        return $complemento;
    }

    public function excluir(Complemento $complemento)
    {
        $this->dao->excluir($complemento->codigo);
    }
}
