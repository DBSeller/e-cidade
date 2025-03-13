<?php

namespace ECidade\Api\V1\Providers\Domain;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;

/**
 * Class ResponseServiceProvider
 * @package ECidade\Api\V1\Providers\Domain
 */
class ResponseServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * @inheritDoc
     */
    public function boot(Application $app)
    {
        //
    }

    /**
     * @param Container $container
     */
    public function register(Container $container)
    {
        //
    }
}
