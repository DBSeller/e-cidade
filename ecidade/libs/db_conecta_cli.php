<?php
    $sPath = 'libs/';

if (!isset($_SESSION)) {
    session_start();
}


if (isset($_SESSION[\ECidade\Lib\Session\DefaultSession::DB_REQUEST_FROM_API])) {
    return;
}

if (empty($_SESSION['DB_login']) || empty($_SESSION['DB_id_usuario'])) {
    session_destroy();
    echo "Sessão inválida (12).\nFeche seu navegador e faça login novamente.\n";
    exit;
}

require_once modification("{$sPath}db_conn.php");

if (isset($_SESSION['DB_servidor']) &&
    isset($_SESSION['DB_base']) &&
    isset($_SESSION['DB_user']) &&
    isset($_SESSION['DB_porta']) &&
    isset($_SESSION['DB_senha'])) {
    $DB_SERVIDOR = db_getsession("DB_servidor");
    $DB_BASE = db_getsession("DB_base");
    $DB_PORTA = db_getsession("DB_porta");
    $DB_USUARIO = db_getsession("DB_user");
    $DB_SENHA = db_getsession("DB_senha");
}

/**
 * Nome do programa atual
 */
$sProgramaAtual = basename($_SERVER["SCRIPT_NAME"]);

if (isset($_SESSION['DB_NBASE'])) {
    $DB_BASE = $_SESSION["DB_NBASE"];
}

if (isset($_SESSION['DB_servidor'])) {
    $DB_SERVIDOR = $_SESSION["DB_servidor"];
}

if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {

    echo "Contate com Administrador do Sistema! (Conexão Inválida.)   <br>Sessão terminada, feche seu navegador!\n";
    session_destroy();
    exit;
}

$database = \ECidade\V3\Datasource\Database::getInstance(false, $conn);

$capsuleManager = new Illuminate\Database\Capsule\Manager;
$capsuleManager->addConnection([
    'driver'    => 'pgsql',
    'host'      => $database->getServidor(),
    'port'      => $database->getPorta(),
    'database'  => $database->getBase(),
    'username'  => $database->getUsuario(),
    'password'  => $database->getSenha(),
    'charset'   => 'latin1',
    'collation' => '',
    'prefix'    => '',
]);
$capsuleManager->setAsGlobal();
