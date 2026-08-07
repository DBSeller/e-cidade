<?php

namespace ECidade\Api\V1\Providers\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use ECidade\Api\V1\Controllers\Protocolo\Cgm;

/**
 * Class ProtocoloControllerProvider
 * @package ECidade\Api\V1\Providers
 */
class ProtocoloControllerProvider implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $app["cgm.controller"] = function () use ($app) {
            return new Cgm($app['request_stack']->getCurrentRequest());
        };

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/cgm', "cgm.controller:getAll");
        $controllers->get('/cgm/{id}', "cgm.controller:get")->assert('id', '\d+');

        return $controllers;
    }
}
