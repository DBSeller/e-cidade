<?php

namespace ECidade\Api\V1;

use ECidade\Api\V1\Providers\Controller\ConfiguracaoControllerProvider;
use ECidade\Api\V1\Providers\Controller\AuthControllerProvider;
use ECidade\Api\V1\Providers\Controller\MatriculaOnlineControllerProvider;
use ECidade\Api\V1\Providers\Controller\ProtocoloControllerProvider;
use ECidade\Api\V1\Providers\Controller\TributarioControllerProvider;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

/**
 * Class APIServiceProvider
 * @package ECidade\Api\V1
 */
class APIServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $prefix = $app['ecidade_api.mount_prefix'];

        $app->mount($prefix . "/auth", new AuthControllerProvider());
        $app->mount($prefix . "/protocolo", new ProtocoloControllerProvider());
        $app->mount($prefix . "/configuracao", new ConfiguracaoControllerProvider());
        $app->mount($prefix . "/tributario", new TributarioControllerProvider());
        $app->mount($prefix . "/matriculaonline", new MatriculaOnlineControllerProvider());
    }

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        //
    }
}
