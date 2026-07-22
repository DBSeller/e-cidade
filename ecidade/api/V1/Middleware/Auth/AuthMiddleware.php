<?php

namespace ECidade\Api\V1\Middleware\Auth;

use DateTime;
use ECidade\Api\V1\Middleware\RequestMiddlewareInterface;
use ECidade\Configuracao\Api\Generator\HashGenerator;
use ECidade\Configuracao\Api\Repository\ApiClienteRepository;
use Exception;
use Silex\Application;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class AuthMiddleware implements RequestMiddlewareInterface
{
    /**
     * @param Request $request
     * @param Application $application
     * @throws Exception
     */
    public static function handle(Request $request, Application $application)
    {
        if (!$request->request->has('hash') || !$request->request->has('id')) {
            throw new AccessDeniedException('Sem permissão para acessar a API.', 401);
        }

        $id = $request->request->get('id');

        if (!is_numeric($id)) {
            throw new AccessDeniedException('Sem permissão para acessar a API.', 401);
        }

        $hash = $request->request->get('hash');

        $cliente = ApiClienteRepository::find($id);

        if ($cliente === false) {
            throw new AccessDeniedException('Cliente não encontrado.', 401);
        }

        $secret = $cliente->getChave();

        $decrypted = HashGenerator::decrypt($hash, $secret);

        if ($decrypted === false) {
            throw new AccessDeniedException('Sem permissão para acessar a API.', 401);
        }

        if (!array_key_exists('time', $decrypted)) {
            throw new AccessDeniedException('Tempo não informado no HASH.', 400);
        }

        $dataRequest = new DateTime();
        $dataRequest->setTimestamp($decrypted['time']);

        if ($cliente->getUltimaUtilizacao() !== null) {
            if ($cliente->getUltimaUtilizacao()->getTimestamp() >= $dataRequest->getTimestamp()) {
                throw new AccessDeniedException('A requisição informada não é valida.', 401);
            }
        }

        $cliente->setUltimaUtilizacao($dataRequest);

        $application['authClient'] = $cliente;
        $request->request->add($decrypted);
    }
}
