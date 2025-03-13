<?php

namespace ECidade\Api\V1\Providers\Controller;

use ECidade\Api\V1\Controllers\Auth\Login;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;

/**
 * Class AuthControllerProvider
 * @package ECidade\Api\V1\Providers\Controller
 */
class AuthControllerProvider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app["login.controller"] = function () use ($app) {
            return new Login($app['request_stack']->getCurrentRequest(), $app);
        };

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
        $controllers->post('login', "login.controller:login");

        return $controllers;
    }
}
