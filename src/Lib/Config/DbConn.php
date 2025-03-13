<?php

namespace ECidade\Lib\Config;

class DbConn
{
    /**
     * @var DbConn
     */
    public static $instance;

    /**
     * @var string
     */
    private $servidor;

    /**
     * @var string
     */
    private $base;

    /**
     * @var string
     */
    private $porta;

    /**
     * @var string
     */
    private $usuario;

    /**
     * @var null string
     */
    private $senha;

    private function __construct(
        $DB_SERVIDOR = null,
        $DB_BASE = null,
        $DB_PORTA = null,
        $DB_USUARIO = null,
        $DB_SENHA = null
    ) {
        /***
         * Legacy compat
         */
        require_once(modification("libs/db_conn.php"));

        $this->servidor = $DB_SERVIDOR;
        $this->base = $DB_BASE;
        $this->porta = $DB_PORTA;
        $this->usuario = $DB_USUARIO;
        $this->senha = $DB_SENHA;
    }

    /**
     * @return DbConn
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    /**
     * @return string
     */
    public function servidor()
    {
        return $this->servidor;
    }

    /**
     * @return string
     */
    public function base()
    {
        return $this->base;
    }

    /**
     * @return string
     */
    public function porta()
    {
        return $this->porta;
    }

    /**
     * @return string
     */
    public function usuario()
    {
        return $this->usuario;
    }

    /**
     * @return null
     */
    public function senha()
    {
        return $this->senha;
    }
}
