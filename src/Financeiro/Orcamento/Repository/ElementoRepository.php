<?php


namespace ECidade\Financeiro\Orcamento\Repository;

use cl_orcelemento;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Orcamento\Model\Elemento;
use Exception;

class ElementoRepository extends Repository
{
    /**
     * @param array $order
     * @return Elemento[]
     * @throws Exception
     */
    public function get($campos = ['*'], $order = ['o56_elemento'])
    {
        $campos = implode(', ', $campos);
        $order = implode(', ', $order);

        $dao = new cl_orcelemento();
        $sql = $dao->sql_query_file(null, null, $campos, $order, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar as elementos da despesa.");
        }

        $dados = [];
        while ($state = pg_fetch_array($rs)) {
            $dados[] = Elemento::fromState($state);
        }

        return $dados;
    }

    /**
     * @return Elemento|null
     * @throws Exception
     */
    public function first()
    {
        $elemento = $this->get();
        if (empty($elemento)) {
            return null;
        }

        return array_shift($elemento);
    }

    /**
     * @param integer $ano
     * @return $this
     */
    public function scopeAno($ano)
    {
        $this->scopes['ano'] = "o56_anousu = {$ano}";
        return $this;
    }

    /**
     * @param $elemento
     * @return $this
     */
    public function scopeFonte($elemento)
    {
        $this->scopes['elemento'] = "o56_elemento like '{$elemento}%'";
        return $this;
    }

    /**
     * @return $this
     */
    public function scopeHasVinculoContabilidade()
    {
        $this->scopes['vinculo_contabilidade']  = "
            exists(select 1 from conplanoorcamento where  o56_anousu = c60_anousu and o56_codele = c60_codcon)
        ";
        return $this;
    }
}
