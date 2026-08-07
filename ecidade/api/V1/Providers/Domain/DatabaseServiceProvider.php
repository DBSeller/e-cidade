<?php

namespace ECidade\Api\V1\Providers\Domain;

use ECidade\V3\Datasource\Database;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * Class DatabaseServiceProvider
 * @package ECidade\Api\V1\Providers\Domain
 */
class DatabaseServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    public function boot(Application $app)
    {
        $app->before(function () {
            global $conn;

            $conn = Database::getInstance(true)->connect();

            if (!$conn) {
                throw new AccessDeniedException('Sem permissão para conectar ao banco de dados.');
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
