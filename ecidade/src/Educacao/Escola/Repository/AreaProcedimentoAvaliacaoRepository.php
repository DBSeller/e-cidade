<?php


namespace ECidade\Educacao\Escola\Repository;

use cl_areaprocedimentoavaliacao;
use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use Exception;

/**
 * Class AreaProcedimentoAvaliacaoRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class AreaProcedimentoAvaliacaoRepository extends Repository
{
    /**
     * @param $key
     * @return AreaProcedimentoAvaliacao
     * @throws Exception
     */
    public static function find($key)
    {
        $dao = new cl_areaprocedimentoavaliacao;
        $sql = $dao->sql_query_file($key);
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar Avaliação do Procedimento por Area de Conhecimento.");
        }

        return AreaProcedimentoAvaliacao::fromState(pg_fetch_array($rs));
    }

    /**
     * @return AreaProcedimentoAvaliacao[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_areaprocedimentoavaliacao;
        $sql = $dao->sql_query_file(null, '*', 'ed158_ordem', implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Avaliações do Procedimento por Area de Conhecimento.");
        }

        $procedimentosAvaliacaoArea = [];
        while ($state = pg_fetch_array($rs)) {
            $procedimentosAvaliacaoArea[] = AreaProcedimentoAvaliacao::fromState($state);
        }

        return $procedimentosAvaliacaoArea;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @return $this
     */
    public function scopeAreaProcedimento(AreaProcedimento $areaProcedimento)
    {
        $this->scopes['area_procedimento'] = "ed158_areaprocedimento = {$areaProcedimento->getCodigo()}";
        return $this;
    }

    /**
     * @param AreaProcedimentoAvaliacao $avaliacao
     * @return AreaProcedimentoAvaliacao
     * @throws Exception
     */
    public function salvar(AreaProcedimentoAvaliacao $avaliacao)
    {
        $dao = new cl_areaprocedimentoavaliacao();
        $dao->ed158_codigo = $avaliacao->getCodigo();
        $dao->ed158_areaprocedimento = $avaliacao->getAreaProcedimento()->getCodigo();
        $dao->ed158_formaavaliacao = $avaliacao->getFormaAvaliacao()->getCodigo();
        $dao->ed158_periodoavaliacao = $avaliacao->getPeriodoAvaliacao()->getCodigo();
        $dao->ed158_tipo = $avaliacao->getTipo();
        $dao->ed158_ordem_elemento = $avaliacao->getOrdemElemento();
        $dao->ed158_formaobtencao = $avaliacao->getFormaObtencao()->getValue();
        $dao->ed158_peso = 'null';
        $dao->ed158_ordem = $avaliacao->getOrdem();

        if (empty($dao->ed158_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed158_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Procedimento de Avaliação da Área.");
        }

        $avaliacao->setCodigo($dao->ed158_codigo);

        return $avaliacao;
    }

    public function excluir(AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        $dao = new cl_areaprocedimentoavaliacao();
        $dao->excluir($areaProcedimentoAvaliacao->getCodigo());

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Procedimento de Avaliação da Area de Conhecimento.");
        }

        return true;
    }
}
