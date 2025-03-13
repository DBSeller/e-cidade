<?php

namespace ECidade\Api\V1\Providers\Domain;

use ECidade\Api\V1\Middleware\Session\MenuAcessadoSessionMiddleware;
use ECidade\Api\V1\Middleware\Session\SessionMiddleware;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SessionServiceProvider
 * @package ECidade\Api\V1\Providers\Domain
 */
class SessionServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function boot(Application $app)
    {
        $app->before(function (Request $request, Application $app) {
            SessionMiddleware::handle($request, $app);
            MenuAcessadoSessionMiddleware::handle($request, $app);
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
