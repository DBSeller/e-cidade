<?php

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Repository;

use cl_matriz_saldo_contabil;
use ECidade\Financeiro\Contabilidade\MatrizSaldoContabil\Model\MatrizSaldoContabil;
use Exception;

class MatrizSaldoContabilRepositorio
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @param int $sequencial
     * @param string $operador
     * @return MatrizSaldoContabilRepositorio
     */
    public function scopeSequencial($sequencial, $operador = '=')
    {
        $this->scopes['sequencial'] = "c132_sequencial {$operador} {$sequencial}";
        return $this;
    }

    /**
     * @param int $mes
     * @param string $operador
     * @return MatrizSaldoContabilRepositorio
     */
    public function scopeMes($mes, $operador = '=')
    {
        $this->scopes['mes'] = "c132_mes {$operador} {$mes}";
        return $this;
    }

    /**
     * @param int $ano
     * @param string $operador
     * @return MatrizSaldoContabilRepositorio
     */
    public function scopeAno($ano, $operador = '=')
    {
        $this->scopes['ano'] = "c132_ano {$operador} {$ano}";
        return $this;
    }

    /**
     * @return MatrizSaldoContabil[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_matriz_saldo_contabil();
        $sql = $dao->sql(array('*'), $this->scopes);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as matrizes saldos contábeis.\nContate o suporte.");
        }

        $matrizes = array();

        if (pg_num_rows($rs) === 0) {
            return $matrizes;
        }

        while ($retorno = pg_fetch_array($rs)) {
            $matrizes[] = MatrizSaldoContabil::fromState($retorno);
        }

        return $matrizes;
    }

    /**
     * @return MatrizSaldoContabil|null
     * @throws Exception
     */
    public function first()
    {
        $registros = $this->get();

        return count($registros) > 0
            ? array_shift($registros)
            : null;
    }

    /**
     * @param MatrizSaldoContabil|null $matrizSaldoContabil
     * @throws Exception
     */
    public function delete(MatrizSaldoContabil $matrizSaldoContabil = null)
    {
        $sequencial = $matrizSaldoContabil instanceof MatrizSaldoContabil
            ? $matrizSaldoContabil->getSequencial()
            : null;

        $dao = new cl_matriz_saldo_contabil();
        $dao->excluir($sequencial, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir a matriz saldo contábil.\nContate o suporte.");
        }
    }

    /**
     * @param MatrizSaldoContabil $matrizSaldoContabil
     * @return MatrizSaldoContabil
     * @throws Exception
     */
    public static function save(MatrizSaldoContabil $matrizSaldoContabil)
    {
        $dao = new cl_matriz_saldo_contabil();
        $dao->c132_sequencial = $matrizSaldoContabil->getSequencial();
        $dao->c132_mes = $matrizSaldoContabil->getMes();
        $dao->c132_ano = $matrizSaldoContabil->getAno();

        $matrizSaldoContabil->getSequencial()
            ? $dao->alterar($matrizSaldoContabil->getSequencial())
            : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações da matriz saldo contábil.\nContate o suporte.");
        }

        $matrizSaldoContabil->setSequencial($dao->c132_sequencial);

        return $matrizSaldoContabil;
    }

    /**
     * @param $sequencial
     * @param array $columns
     * @return MatrizSaldoContabil|null
     * @throws Exception
     */
    public static function find($sequencial, array $columns = array('*'))
    {
        $dao = new cl_matriz_saldo_contabil();
        $sql = $dao->sql_query($sequencial, implode(', ', $columns));
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception("Não foi possível buscar a matriz saldo contábil.\nContate o suporte.");
        }

        if (pg_num_rows($resultado) === 0) {
            return null;
        }

        $resultado = pg_fetch_array($resultado);

        return MatrizSaldoContabil::fromState($resultado);
    }
}
