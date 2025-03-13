<?php
namespace ECidade\Lib\Session;

use ECidade\V3\Datasource\Database;
use ECidade\V3\Window\Session;
use Exception;

class DatabaseSession
{
    /**
     * @var DatabaseSession
     */
    public static $instance;

    /**
     * DatabaseSession constructor.
     */
    protected function __construct()
    {
    }

    /**
     * @return DatabaseSession
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * @throws Exception
     */
    private function start()
    {
        Database::getInstance()->execute('SELECT fc_startsession()');
    }

    /**
     * @param $key
     * @param $value
     * @throws Exception
     */
    private function add($key, $value)
    {
        $escapedValue = pg_escape_string($value);
        Database::getInstance()->execute("SELECT fc_putsession('{$key}', '{$escapedValue}')");
    }

    /**
     * @param $value
     * @throws Exception
     */
    private function adicionarDataHora($value)
    {
        $time = microtime(true);
        $microTime = sprintf("%06d", ($time - floor($time)) * 1000000);
        $timeNow = date("H:i:s");
        $dateTime = date("Y-m-d {$timeNow}.{$microTime}O", $value);

        $this->add('DB_DATAHORAUSU', $dateTime);
    }

    /**
     * @param $value
     * @throws Exception
     */
    private function adicionarData($value)
    {
        $data = date("Y-m-d", $value);
        $this->add('DB_DATAUSU', $data);
    }

    /**
     * @throws Exception
     */
    public function addSessionToDatabase()
    {
        if (session_status() !== Session::ACTIVE) {
            throw new Exception('Sessão não está ativa.');
        }

        if (!Database::getInstance()->isConnected()) {
            throw new Exception('Não existe conexão com o banco de dados.');
        }

        $this->start();

        foreach ($_SESSION as $prop => $value) {
            $key = strtoupper($prop);

            switch ($key) {
                case 'DB_DATAUSU':
                    $this->adicionarDataHora($value);
                    $this->adicionarData($value);
                    break;
                default:
                    if (substr($key, 0, 2) == "DB") {
                        $this->add($key, pg_escape_string($value));
                    }
                    break;
            }
        }

        return true;
    }
}
