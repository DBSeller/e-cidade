<?php

use ECidade\Api\V1\APIServiceProvider;
use ECidade\Api\V1\Configs\ApplicationConfig;
use ECidade\Api\V1\Domain\Route\RouteConfig;
use ECidade\Api\V1\Providers\Domain\ApplicationAuthServiceProvider;
use ECidade\Api\V1\Providers\Domain\ContentTypeServiceProvider;
use ECidade\Api\V1\Providers\Domain\DatabaseServiceProvider;
use ECidade\Api\V1\Providers\Domain\QueryServiceProvider;
use ECidade\Api\V1\Providers\Domain\PersistUserDataServiceProvider;
use ECidade\Api\V1\Providers\Domain\ResponseServiceProvider;
use ECidade\Api\V1\Providers\Domain\SessionServiceProvider;
use ECidade\Api\V1\Providers\Domain\UserAuthServiceProvider;
use ECidade\Lib\Session\DefaultSession;
use ECidade\V3\Datasource\Database;
use ECidade\V3\Extension\Front;
use ECidade\V3\Extension\Registry;
use ECidade\V3\Extension\Request as EcidadeRequest;
use Silex\Application;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'bootstrap.php');

// @todo Revisar essa logica
// Criamos um request fake para poder utilizar o recursos dos modifications.

$_SERVER['REQUEST_URI'] = preg_replace('/(.*?)\/w\/\d+(.*)/', '$1$2', $_SERVER['REQUEST_URI']);

$front = new Front();
$ecidadeRequest = new EcidadeRequest($front->getPath());
Registry::set('app.request', $ecidadeRequest);
$front->createWindow();

$app = new Application();

$app['debug'] = true;

$app['class.loader'] = Registry::get('app.loader');

// aplica o service provider do ServiceController
$app->register(new ServiceControllerServiceProvider());

// app api version1 routes
$app->register(new APIServiceProvider(), array(
    'ecidade_api.mount_prefix' => ApplicationConfig::APPLICATION_PREFIX
));

/**
 * Legacy compat
 */
require_once modification("libs/db_stdlib.php");

/**
 * Verifica se a rota está protegida pelo sistema de autenticação
 *  caso não, utiliza a autenticação antiga
 */
if (RouteConfig::isGuarded($ecidadeRequest->getUri())) {
    $app->register(new ContentTypeServiceProvider());
    $app->register(new QueryServiceProvider());
    $app->register(new DatabaseServiceProvider());
    $app->register(new ApplicationAuthServiceProvider());
    $app->register(new SessionServiceProvider());
    $app->register(new PersistUserDataServiceProvider());
    $app->register(new UserAuthServiceProvider());
    $app->register(new ResponseServiceProvider());
    /**
     * Legacy compat
     */
    require_once(modification("dbforms/db_funcoes.php"));
} else {
    $app->before(function (Request $request, Application $app) {
        Registry::get('app.request')->session()->start();

        /**
         * @see https://tools.ietf.org/html/rfc7235#section-3.1
         */
        if ((empty($_SESSION) || empty($_SESSION['DB_login']))) {
            throw new AccessDeniedHttpException('Sessão inválida ou expirada. Tente logar novamente.');
        }

        $DB_SERVIDOR = db_getsession("DB_servidor");
        $DB_BASE = db_getsession("DB_base");
        $DB_PORTA = db_getsession("DB_porta");
        $DB_USUARIO = db_getsession("DB_user");
        $DB_SENHA = db_getsession("DB_senha");

        global $conn;
        $conn = pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA");

    });
}

// app error handling
$app->error(function (Exception $e, $code) use ($app) {
    $response = array(
        "statusCode" => $code,
        "message" => DBString::utf8_encode_all($e->getMessage())
    );

    if ($app['debug']) {
        $response["stacktrace"] = $e->getTraceAsString();
    }

    return new JsonResponse($response);
});

if (RouteConfig::isGuarded($ecidadeRequest->getUri())) {
    $app->finish(function () {
        DefaultSession::getInstance()->destroy();
        Database::getInstance()->disconnect();
    });
}

$app->run();
