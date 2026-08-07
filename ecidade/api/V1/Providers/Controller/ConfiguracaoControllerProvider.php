<?php
namespace ECidade\Api\V1\Providers\Controller;

use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use ECidade\Api\V1\Controllers\Configuracao\Formulario;

/**
 * Class ConfiguracaoControllerProvider
 * @package ECidade\Api\V1\Providers\Controller
 */
class ConfiguracaoControllerProvider implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return mixed
     */
    public function connect(Application $app)
    {
        $app["formularios.controller"] = function () use ($app) {
            return new Formulario($app['request_stack']->getCurrentRequest());
        };

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];
        $controllers->get('/formulario/{id}/instituicao/{instituicao}', "formularios.controller:getAll");

        return $controllers;
    }
}
