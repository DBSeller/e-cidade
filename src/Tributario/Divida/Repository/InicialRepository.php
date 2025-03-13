<?php


namespace ECidade\Tributario\Divida\Repository;

use cl_inicial;
use DateTime;
use ECidade\Tributario\Divida\Model\Inicial;
use Exception;

class InicialRepository
{

    /**
     * @var InicialRepository
     */
    protected static $instance;

    /**
     * @var array
     */
    private $scopes = array();

    /**
     * InicialRepository constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return InicialRepository
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
    public function scopeInicial($incial, $operador = '=')
    {
        $this->scopes['incial'] = "v50_inicial {$operador} {$incial}";
        return $this;
    }

    /**
     * @return Diversos[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_inicial();
        $sql = $dao->sql_query_file(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os débitos.");
        }

        $arrInicial = array();

        if (pg_num_rows($rs) === 0) {
            return $arrInicial;
        }

        while ($inicial = pg_fetch_array($rs)) {
            $arrInicial[] = Inicial::fromState($inicial);
        }

        return $arrInicial;
    }

    /**
     * @param  $codigo
     * @return bool|Inicial
     * @throws Exception
     */
    public static function find($codigo)
    {
        $dao = new cl_inicial();
        $sql = $dao->sql_query_file($codigo);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar o lote solicitado.');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Inicial::fromState($resultado);
    }

    public function getByNumpre($numpre)
    {
        $dao = new cl_inicial();
        $sql = $dao->sql_query_by_numpre($numpre);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os débitos.");
        }

        $arrInicial = array();

        if (pg_num_rows($rs) === 0) {
            return $arrInicial;
        }

        while ($inicial = pg_fetch_array($rs)) {
            $arrInicial[] = Inicial::fromState($inicial);
        }

        return $arrInicial;
    }
}
