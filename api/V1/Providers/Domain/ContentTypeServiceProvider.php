<?php

namespace ECidade\Api\V1\Providers\Domain;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ContentTypeServiceProvider
 * @package ECidade\Api\V1\Providers\Domain
 */
class ContentTypeServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request) {
            if ($request->headers->get('Content-Type') === 'application/json') {
                $data = json_decode($request->getContent(), true);
                $request->request->replace(is_array($data) ? $data : array());
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
