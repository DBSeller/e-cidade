<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php');
require_once(modification(ECIDADE_PATH . 'libs/db_stdlib.php'));

// default config
$config = [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations'
    ],

    'migration_base_class' => 'Classes\PostgresMigration',

    'environments' => [
        'default_migration_table' => 'phinxlog_ecidade',
    ]
];


// dynamic config
$environment = 'ecidade';

$config['environments']['default_database'] = $environment;

require 'libs/db_conn.php';


// utiliza configuracao do db_conn (producao)
$host = $DB_SERVIDOR;
$name = $DB_BASE;
$user = $DB_USUARIO;
$pass = $DB_SENHA;
$port = $DB_PORTA;

if (!($conn = @pg_connect("host=$host dbname=$name port=$port user=$user password=$pass"))) {
    echo "erro ao conectar";
}

// utiliza configuracao da sessao (producao, desenvolvimento, teste)
if (function_exists('db_getsession') && isset($_SESSION['DB_servidor'])) {

    $host = db_getsession("DB_servidor");
    $name = db_getsession("DB_base");
    $user = db_getsession("DB_user");
    $pass = db_getsession("DB_senha");
    $port = db_getsession("DB_porta");
}

$config['environments'][$environment] = array(
    'adapter' => 'pgsql',
    'host' => $host,
    'name' => $name,
    'user' => $user,
    'pass' => $pass,
    'port' => $port,
    'charset' => 'latin1'
);

return $config;
