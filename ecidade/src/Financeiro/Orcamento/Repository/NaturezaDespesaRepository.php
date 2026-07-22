<?php

namespace ECidade\Financeiro\Orcamento\Repository;

use cl_orcelemento;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Orcamento\Model\NaturezaDespesa;
use Exception;

/**
 * Class NaturezaDespesaRepository
 * @package ECidade\Financeiro\Orcamento\Repository
 */
class NaturezaDespesaRepository extends Repository
{

    /**
     * @param string[] $campos
     * @param array $order
     * @return NaturezaDespesa[]
     * @throws Exception
     */
    public function get($campos = ['orcelemento.*'], $order = ['o56_elemento'])
    {
        $campos = implode(', ', $campos);
        $where = implode(' and ', $this->scopes);


        $sql = "
        select {$campos}
          from orcelemento
         inner join conplanoorcamento on o56_codele = conplanoorcamento.c60_codcon and o56_anousu = c60_anousu
        ";
        if (!empty($where)) {
            $sql .= " where {$where}";
        }

        if (!empty($order)) {
            $sql .= " order by " . implode(', ', $order);
        }

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar as naturezas de despesa.");
        }

        $dados = [];
        while ($state = pg_fetch_array($rs)) {
            $dados[] = NaturezaDespesa::fromState($state);
        }

        return $dados;
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
     * @param $natureza
     * @return $this
     */
    public function scopeNaturezaDespesa($natureza)
    {
        $this->scopes['natureza'] = "o56_elemento like '{$natureza}%'";
        return $this;
    }

    /**
     * Filtra apenas os elementos sintéticas
     * @return $this
     */
    public function scopeApenasNaturezaSintetica()
    {
        $this->scopes['tipo'] = " not exists (
            select 1 from contabilidade.conplanoorcamentoanalitica
             where c61_codcon = o56_codele
               and c61_anousu = o56_anousu
        )";

        return $this;
    }

    /**
     * Filtra apenas os elementos analiticos
     * @return $this
     */
    public function scopeApenasNaturezaAnalitico()
    {
        $this->scopes['tipo'] = " exists (
            select 1 from contabilidade.conplanoorcamentoanalitica
             where c61_codcon = o56_codele
               and c61_anousu = o56_anousu
        )";

        return $this;
    }

    /**
     * Filtra as naturezas de despesa do nivel da dotacao
     * @return $this
     */
    public function nivelDotacao()
    {
        $this->scopes['nivel'] = "fc_nivel_plano2005(c60_estrut) = 6";
        return $this;
    }
}
