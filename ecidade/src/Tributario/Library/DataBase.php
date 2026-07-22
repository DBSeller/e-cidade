<?php

namespace ECidade\Tributario\Library;

use ECidade\V3\Datasource\Database as DataSourceDataBase;
use ECidade\V3\Extension\Registry as RegistryV3;
use Exception;

final class DataBase extends DataSourceDataBase
{
    /**
     * @var DataBase
     */
    public static $instance;

    /**
     * @param bool $withDbConn
     * @param null $conexao
     * @return DataBase
     * @throws Exception
     */
    private static function build($withDbConn = false, $conexao = null)
    {
        $dataSource = DataSourceDataBase::getInstance($withDbConn, $conexao);
        $database = new Database();

        if ($dataSource->isConnected()) {
            $database->setBase($dataSource->getBase());
            $database->setServidor($dataSource->getServidor());
            $database->setPorta($dataSource->getPorta());
            $database->setUsuario($dataSource->getUsuario());
            $database->setSenha($dataSource->getSenha());
            $database->setConnection($dataSource->getConnection());
        } else {
            $session = RegistryV3::get('app.request')->session();
            $database->setBase($session->get('DB_NBASE', $session->get('DB_base')));
            $database->setServidor($session->get('DB_servidor'));
            $database->setPorta($session->get('DB_porta'));
            $database->setUsuario($session->get('DB_user'));
            $database->setSenha($session->get('DB_senha'));
            $database->connect();
            $database->execute("SELECT set_config('search_path', current_setting('search_path') || ',plugins', false)");
        }
        return $database;
    }

    /**
     * @param bool $withDbConn
     * @return DataBase
     * @throws Exception
     */
    public static function getInstance($withDbConn = false, $conexao = null, $withCookie = false)
    {
        if (self::$instance == null) {
            self::$instance = self::build($withDbConn, $conexao);
        }

        return self::$instance;
    }

    /**
     * @return DataBase
     * @throws Exception
     */
    public static function init()
    {
        return self::getInstance();
    }

    /**
     * @param string $query
     * @return bool|resource
     */
    public function execute($query)
    {
        return db_query($query);
    }
}
