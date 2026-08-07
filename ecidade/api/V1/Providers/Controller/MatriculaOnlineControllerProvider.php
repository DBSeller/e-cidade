<?php

namespace ECidade\Api\V1\Providers\Controller;

use ECidade\Api\V1\Controllers\MatriculaOnline\InscricaoCandidato;
use Silex\Api\ControllerProviderInterface;
use Silex\Application;
use Silex\ControllerCollection;

/**
 * Class ProtocoloControllerProvider
 * @package ECidade\Api\V1\Providers
 */
class MatriculaOnlineControllerProvider implements ControllerProviderInterface
{
    /**
     * @param Application $app
     * @return ControllerCollection
     */
    public function connect(Application $app)
    {
        $app["inscricaomatricula.controller"] = function () use ($app) {
            return new InscricaoCandidato($app['request_stack']->getCurrentRequest());
        };

        /**
         * creates a new controller based on the default route
         * @var $controllers ControllerCollection
         */
        $controllers = $app['controllers_factory'];

        $controllers->post('inscricao', "inscricaomatricula.controller:inscricao");
        $controllers->get('emissaoProtocolo', "inscricaomatricula.controller:emissaoProtocolo");

        return $controllers;
    }
}
