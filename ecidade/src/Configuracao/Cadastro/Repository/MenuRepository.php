<?php


namespace ECidade\Configuracao\Cadastro\Repository;

use cl_db_itensmenu;
use ECidade\Configuracao\Cadastro\Model\Menu;
use Exception;

class MenuRepository
{
    /**
     * @var MenuRepository
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
     * @return MenuRepository
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @return Menu[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_db_itensmenu();
        $sql = $dao->sql_query_file(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os menus.");
        }

        $menus = array();

        if (pg_num_rows($rs) === 0) {
            return $menus;
        }

        while ($menu = pg_fetch_array($rs)) {
            $menus[] = Menu::fromState($menu);
        }

        return $menus;
    }

    /**
     * @param  $id
     * @return bool|Menu
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new cl_db_itensmenu();
        $sql = $dao->sql_query_file($id);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar o menu solicitado.');
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return Menu::fromState($resultado);
    }
}
