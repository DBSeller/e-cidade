<?php


namespace ECidade\Configuracao\Cadastro\Repository;

use ECidade\Configuracao\Cadastro\Model\Modulo;
use Exception;

class ModuloRepository
{
    /**
     * @var ModuloRepository
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
     * @return ModuloRepository
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @return Modulo[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new \cl_db_modulos();
        $sql = $dao->sql_query_file(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os menus.");
        }

        $registros = array();

        if (pg_num_rows($rs) === 0) {
            return $registros;
        }

        while ($registro = pg_fetch_array($rs)) {
            $registros[] = Modulo::fromState($registro);
        }

        return $registros;
    }

    /**
     * @param  $id
     * @return bool|Modulo
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new \cl_db_modulos();
        $sql = $dao->sql_query_file($id);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar o menu solicitado.');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Modulo::fromState($resultado);
    }
}
