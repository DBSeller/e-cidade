<?php

namespace ECidade\Api\V1\Providers\Domain;

use ECidade\Api\V1\Middleware\Auth\AuthMiddleware;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class ApplicationAuthServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request, Application $app) {
            AuthMiddleware::handle($request, $app);
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
