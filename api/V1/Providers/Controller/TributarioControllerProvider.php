<?php

namespace ECidade\Api\V1\Providers\Controller;

use ECidade\Api\V1\Controllers\Tributario\Recibo;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

/**
 * Class ProtocoloControllerProvider
 * @package ECidade\Api\V1\Providers
 */
class TributarioControllerProvider implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return ControllerCollection
     */
    public function connect(Application $app)
    {
        $app["recibo.controller"] = function () use ($app) {
            return new Recibo($app['request_stack']->getCurrentRequest());
        };

        /**
         * creates a new controller based on the default route
         * @var $controllers ControllerCollection
         */
        $controllers = $app['controllers_factory'];

        $controllers->post('recibo', "recibo.controller:geraRecibo");

        return $controllers;
    }
}
