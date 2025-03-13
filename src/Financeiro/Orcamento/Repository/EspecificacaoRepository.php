<?php


namespace ECidade\Financeiro\Orcamento\Repository;

use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Orcamento\Model\Especificacao;
use Exception;

/**
 * Class EspecificacaoRepository
 * @package ECidade\Financeiro\Orcamento\Repository
 */
class EspecificacaoRepository extends Repository
{
    /**
     * @param $id
     * @return Especificacao
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new \cl_recursoespecificacao();
        $sql = $dao->sql_query_file($id);

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar especificação de recurso.");
        }
        return Especificacao::fromState(pg_fetch_array($rs));
    }

    /**
     * @param string[] $campos
     * @param array $order
     * @return Especificacao[]
     * @throws Exception
     */
    public function get(array $campos = ['*'], array $order = [null])
    {
        $campos = implode(', ', $campos);
        $order = implode(', ', $order);

        $dao = new \cl_recursoespecificacao();
        $sql = $dao->sql_query_file(null, $campos, $order, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar as especificações de recurso.");
        }

        $dados = [];
        while ($state = pg_fetch_array($rs)) {
            $dados[] = Especificacao::fromState($state);
        }

        return $dados;
    }

    /**
     * @param Especificacao $especificacao
     * @return Especificacao
     * @throws Exception
     */
    public function salvar(Especificacao $especificacao)
    {
        $dao = new \cl_recursoespecificacao();
        $dao->o205_sequencial = $especificacao->getId();
        $dao->o205_codigo = $especificacao->getCodigo();
        $dao->o205_descricao = pg_escape_string(trim($especificacao->getDescricao()));
        $dao->o205_estado = $especificacao->getEstado();

        if (!empty($dao->o205_sequencial)) {
            $dao->alterar();
        } else {
            $dao->incluir();
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Especificação de Recurso.");
        }

        $especificacao->setId($dao->o205_sequencial);

        return $especificacao;
    }

    /**
     * @param Especificacao $especificacao
     * @return bool
     * @throws Exception
     */
    public function excluir(Especificacao $especificacao)
    {
        $dao = new \cl_recursoespecificacao();
        $dao->excluir($especificacao->getId());

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Especificação de Recurso");
        }

        unset($especificacao);
        return true;
    }

    /**
     * @return Especificacao|null
     * @throws Exception
     */
    public function first()
    {
        $especificacoes = $this->get();
        if (empty($especificacoes)) {
            return null;
        }

        return $especificacoes[0];
    }

    /**
     * @param Especificacao $especificacao
     * @return $this
     */
    public function scopeEspecificacaoExiste(Especificacao $especificacao)
    {
        $this->scopeEspecificacao($especificacao->getCodigo());
        return $this;
    }

    /**
     * @param $id
     * @param string $operador
     * @return $this
     */
    public function scopeId($id, $operador = '=')
    {
        $this->scopes['codigo'] = "o205_sequencial {$operador} '{$id}'";
        return $this;
    }

    /**
     * @param string $especificacao
     * @param string $operador
     * @return $this
     */
    public function scopeEspecificacao($especificacao, $operador = '=')
    {
        $this->scopes['especificacao'] = "o205_codigo {$operador} '{$especificacao}'";
        return $this;
    }

    /**
     * @param Especificacao $especificacao
     * @return $this
     */
    public function scopeEspecificacaoVinculadaRecurso(Especificacao $especificacao)
    {
        $this->scopes['vinculada_recurso'] = "
            exists(select 1 from orctiporec where o15_loaespecificacao = '{$especificacao->getCodigo()}')
        ";
        return $this;
    }
}
