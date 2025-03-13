<?php

namespace ECidade\Api\V1\Providers\Domain;

use ECidade\Configuracao\Api\Models\ApiCliente;
use ECidade\Configuracao\Api\Repository\ApiClienteRepository;
use Exception;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\BootableProviderInterface;
use Silex\Application;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PersistUserDataServiceProvider
 * @package ECidade\Api\V1\Providers\Domain
 */
class PersistUserDataServiceProvider implements ServiceProviderInterface, BootableProviderInterface
{
    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app->before(function (Request $request, Application $app) {
            try {
                if (!isset($app['authClient'])) {
                    throw new AccessDeniedException('Usuário não autenticado na API.', 401);
                }
                /**
                 * @var ApiCliente $client
                 */
                $client = $app['authClient'];

                ApiClienteRepository::save($client);
            } catch (Exception $e) {
                throw new AccessDeniedException($e->getMessage());
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
