<?php

namespace ECidade\Api\V1\Providers\Domain;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class QueryServiceProvider
 * @package ECidade\Api\V1\Providers\Domain
 */
class QueryServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request) {
            if ($request->isMethod('GET')) {
                $data = $request->query->all();
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
