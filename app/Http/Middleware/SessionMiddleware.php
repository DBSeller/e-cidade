<?php

namespace App\Http\Middleware;

use Closure;
use ECidade\Api\V1\Middleware\Session\MenuAcessadoSessionMiddleware;
use ECidade\Api\V1\Middleware\Session\SessionMiddleware as ApiSessionMiddleware;
use ECidade\Lib\Session\DefaultSession;
use Exception;
use Illuminate\Http\Request;

/**
 * Class SessionMiddleware
 * @package App\Http\Middleware
 */
class SessionMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        ApiSessionMiddleware::makeSession();

        $defaultSession = DefaultSession::getInstance()->addFromRequest($request);

        return $next($request);
    }
}
