<?php

namespace ECidade\Api\V1\Providers\Domain;

use ECidade\Api\V1\Domain\Route\RouteConfig;
use ECidade\Lib\Session\DefaultSession;
use Exception;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use UsuarioSistema;

/**
 * Class UserAuthServiceProvider
 * @package ECidade\Api\V1\Providers\Domain
 */
class UserAuthServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function boot(Application $app)
    {
        $app->before(function (Request $request) {
            $script = $request->get('_route');
            $route = RouteConfig::normalizeRequestRoute($script);

            if (!in_array($route, RouteConfig::userAuthRoutes())) {
                return;
            }

            if (!$request->request->has('idUsuario')) {
                throw new AccessDeniedException('O usuário não está autenticado.', 401);
            }

            $idUsuario = $request->request->get('idUsuario');

            if (!is_numeric($idUsuario)) {
                throw new AccessDeniedException('Código do usuário foi informado em um formato inválido.', 401);
            }

            try {
                $usuario = new UsuarioSistema($idUsuario);
                DefaultSession::getInstance()->atualizaDadosUsuario($usuario->getCodigo());
            } catch (Exception $exception) {
                throw new AccessDeniedException('Usuário informado não foi encontrado na base de dados.', 401);
            }
        });
    }

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        //
    }
}
