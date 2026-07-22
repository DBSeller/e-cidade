<?php

namespace ECidade\Tributario\Caixa\Repository;

use cl_arrepaga;
use ECidade\Tributario\Caixa\Model\Arrepaga;
use Exception;

class ArrepagaRepository
{
    /**
     * @var ArrepagaRepository
     */
    protected static $instance;

    /**
     * @var array
     */
    private $scopes = array();

    /**
     * DiversosRepository constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return ArrepagaRepository
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @param  $numpre
     * @param  string $operador
     * @return $this
     */
    public function scopeNumpre($numpre, $operador = '=')
    {
        $this->scopes['numpre'] = "k00_numpre {$operador} {$numpre}";
        return $this;
    }

    /**
     * @return Arrepaga[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_arrepaga();
        $sql = $dao->sql_query_file(null, '*', 'k00_dtpaga asc', implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os débitos pagos.");
        }

        $debitos = array();

        if (pg_num_rows($rs) === 0) {
            return $debitos;
        }

        while ($debito = pg_fetch_array($rs)) {
            $debitos[] = Arrepaga::fromState($debito)->withDisbanco();
        }

        return $debitos;
    }
}
