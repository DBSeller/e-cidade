<?php

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Repository;

use cl_matriz_saldo_contabil_lancamentos;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\MatrizSaldoContabil;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\MatrizSaldoContabilLancamento;
use Exception;

class MatrizSaldoContabilLancamentoRepositorio
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @param int $sequencial
     * @param string $operador
     * @return $this
     */
    public function scopeSequencial($sequencial, $operador = '=')
    {
        $this->scopes['sequencial'] = "c133_sequencial {$operador} {$sequencial}";
        return $this;
    }

    /**
     * @param MatrizSaldoContabil $matrizSaldoContabil
     * @param string $operador
     * @return $this
     */
    public function scopeMatrizSaldoContabil(MatrizSaldoContabil $matrizSaldoContabil, $operador = '=')
    {
        $scope = "c133_matriz_saldo_contabil {$operador} {$matrizSaldoContabil->getSequencial()}";
        $this->scopes['matrizSaldoContabil'] = $scope;
        return $this;
    }

    /**
     * @param string $estrutural
     * @param string $operador
     * @return $this
     */
    public function scopeEstrutural($estrutural, $operador = '=')
    {
        $this->scopes['estrutural'] = "c133_estrutural {$operador} '{$estrutural}'";
        return $this;
    }

    /**
     * @param string $atributos
     * @param string $operador
     * @return $this
     */
    public function scopeAtributos($atributos, $operador = '=')
    {
        $this->scopes['atributos'] = "c133_atributos {$operador} '{$atributos}'";
        return $this;
    }

    /**
     * @param float $beginningBalance
     * @param string $operador
     * @return $this
     */
    public function scopeBeginningBalance($beginningBalance, $operador = '=')
    {
        $this->scopes['beginningBalance'] = "c133_beginning_balance {$operador} {$beginningBalance}";
        return $this;
    }

    /**
     * @param float $periodChangeDebit
     * @param string $operador
     * @return $this
     */
    public function scopePeriodChangeDebit($periodChangeDebit, $operador = '=')
    {
        $this->scopes['periodChangeDebit'] = "c133_period_change_debit {$operador} {$periodChangeDebit}";
        return $this;
    }

    /**
     * @param float $periodChangeCredit
     * @param string $operador
     * @return $this
     */
    public function scopePeriodChangeCredit($periodChangeCredit, $operador = '=')
    {
        $this->scopes['periodChangeCredit'] = "c133_period_change_credit {$operador} {$periodChangeCredit}";
        return $this;
    }

    /**
     * @param float $endingBalance
     * @param string $operador
     * @return $this
     */
    public function scopeEndingBalance($endingBalance, $operador = '=')
    {
        $this->scopes['endingBalance'] = "c133_ending_balance {$operador} {$endingBalance}";
        return $this;
    }

    /**
     * @param string $natureza
     * @param string $operador
     * @return $this
     */
    public function scopeNatureza($natureza, $operador = '=')
    {
        $this->scopes['natureza'] = "c133_natureza {$operador} '{$natureza}'";
        return $this;
    }

    /**
     * @param string $natureza
     * @param string $operador
     * @return $this
     */
    public function scopeNaturezaFinal($natureza, $operador = '=')
    {
        $this->scopes['naturezaFinal'] = "c133_naturezaFinal {$operador} '{$natureza}'";
        return $this;
    }

    /**
     * @return MatrizSaldoContabilLancamento[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_matriz_saldo_contabil_lancamentos();
        $sql = $dao->sql(array('*'), $this->scopes);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os lançamentos da matriz saldo contábil.\nContate o suporte.");
        }

        $matrizSaldoContabilLancamentos = array();

        if (pg_num_rows($rs) === 0) {
            return $matrizSaldoContabilLancamentos;
        }

        while ($retorno = pg_fetch_array($rs)) {
            $matrizSaldoContabilLancamentos[] = MatrizSaldoContabilLancamento::fromState($retorno);
        }

        return $matrizSaldoContabilLancamentos;
    }

    /**
     * @param MatrizSaldoContabilLancamento|null $matrizSaldoContabilLancamento
     * @throws Exception
     */
    public function delete(MatrizSaldoContabilLancamento $matrizSaldoContabilLancamento = null)
    {
        $sequencial = $matrizSaldoContabilLancamento instanceof MatrizSaldoContabilLancamento
            ? $matrizSaldoContabilLancamento->getSequencial()
            : null;

        $dao = new cl_matriz_saldo_contabil_lancamentos();
        $dao->excluir($sequencial, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            $mensagem = "Não foi possível excluir os lançamentos da matriz saldo contábil.\nContate o suporte.";
            throw new Exception($mensagem);
        }
    }

    /**
     * @param MatrizSaldoContabilLancamento $matrizSaldoContabilLancamento
     * @return MatrizSaldoContabilLancamento
     * @throws Exception
     */
    public static function save(MatrizSaldoContabilLancamento $matrizSaldoContabilLancamento)
    {
        $dao = new cl_matriz_saldo_contabil_lancamentos();
        $dao->c133_sequencial = $matrizSaldoContabilLancamento->getSequencial();
        $dao->c133_matriz_saldo_contabil = $matrizSaldoContabilLancamento->getMatrizSaldoContabil()->getSequencial();
        $dao->c133_estrutural = $matrizSaldoContabilLancamento->getEstrutural();
        $dao->c133_atributos = $matrizSaldoContabilLancamento->getAtributos();
        $dao->c133_beginning_balance = $matrizSaldoContabilLancamento->getBeginningBalance();
        $dao->c133_period_change_debit = $matrizSaldoContabilLancamento->getPeriodChangeDebit();
        $dao->c133_period_change_credit = $matrizSaldoContabilLancamento->getPeriodChangeCredit();
        $dao->c133_ending_balance = $matrizSaldoContabilLancamento->getEndingBalance();
        $dao->c133_natureza = $matrizSaldoContabilLancamento->getNaturezaInicial();
        $dao->c133_natureza_final = $matrizSaldoContabilLancamento->getNaturezaFinal();

        $matrizSaldoContabilLancamento->getSequencial()
            ? $dao->alterar($matrizSaldoContabilLancamento->getSequencial())
            : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            $mensagem = "Não foi possível salvar os lançamentos da matriz saldo contábil.\n";
            $mensagem .= "Contate o suporte.".$dao->erro_msg;
            throw new Exception($mensagem);
        }

        $matrizSaldoContabilLancamento->setSequencial($dao->c133_sequencial);

        return $matrizSaldoContabilLancamento;
    }
}
