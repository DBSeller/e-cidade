<?php

namespace ECidade\Api\V1\Middleware\Session;

use DBSeller\Session\Session;
use ECidade\Api\V1\Middleware\RequestMiddlewareInterface;
use ECidade\Lib\Session\DatabaseSession;
use ECidade\Lib\Session\DefaultSession;
use Exception;
use Silex\Application;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SessionMiddleware implements RequestMiddlewareInterface
{
    /**
     * @param Request $request
     * @param Application $application
     * @throws Exception
     */
    public static function handle(Request $request, Application $application)
    {
        static::makeSession();
    }

    /**
     * @throws Exception
     */
    public static function makeSession()
    {
        $session = DefaultSession::getInstance()
            ->add([DefaultSession::DB_REQUEST_FROM_API => true])
            ->start();

        if ($session->status() !== Session::ACTIVE) {
            throw new AccessDeniedException('Não foi possível construir a sessão de acesso.');
        }

        $databaseSession = DatabaseSession::getInstance()->addSessionToDatabase();

        if (!$databaseSession) {
            throw new NotFoundHttpException('Não foi possível adicionar a sessão ao banco de dados.');
        }
    }
}
