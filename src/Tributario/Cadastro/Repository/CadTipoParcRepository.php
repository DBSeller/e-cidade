<?php


namespace ECidade\Tributario\Cadastro\Repository;

use cl_cadtipoparc;
use DateTime;
use ECidade\Tributario\Cadastro\Model\CadTipoParc;
use Exception;

class CadTipoParcRepository
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
    public function scopeCodigo($codigo, $operador = '=')
    {
        $this->scopes['codigo'] = "k40_codigo {$operador} {$codigo}";
        return $this;
    }

    /**
     * @return Diversos[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_cadtipoparc();
        $sql = $dao->sql_query_file(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível o tipo de parcelamento.");
        }

        $arrCadTipoParc = array();

        if (pg_num_rows($rs) === 0) {
            return $arrCadTipoParc;
        }

        while ($inicial = pg_fetch_array($rs)) {
            $arrCadTipoParc[] = CadTipoParc::fromState($inicial);
        }

        return $arrCadTipoParc;
    }

    /**
     * @param  $codigo
     * @return bool|Inicial
     * @throws Exception
     */
    public static function find($codigo)
    {
        $dao = new cl_cadtipoparc();
        $sql = $dao->sql_query_file($codigo);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar o lote solicitado.');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return CadTipoParc::fromState($resultado);
    }
}
