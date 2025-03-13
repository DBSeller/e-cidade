<?php

namespace App\Domain\Saude\ESF\Middlewares;

use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class AuthEsfMiddleware
{
    public function handle($request, \Closure $next)
    {
        if (!is_dir("plugins/esf") || !file_exists("plugins/esf/Manifest.xml")) {
            throw new NotAcceptableHttpException('Plugin ESF não instalado.');
        }

        return $next($request);
    }
}
