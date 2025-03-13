<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class CorsMiddleware
 * @package App\Http\Middleware
 */
class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', $this->originAllow())
            ->header('Access-Control-Allow-Methods', "PUT, POST, DELETE, GET, OPTIONS")
            ->header('Access-Control-Allow-Headers', "Accept, Authorization, Content-Type, x-requested-with")
            ->header("Vary", "Origin")
            ->header('Access-Control-Allow-Credentials', "true");
    }

    private function originAllow()
    {
        $origin = '*';
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $origin = $_SERVER['HTTP_ORIGIN'];
        }

        if (is_null(env('ALLOWED_ORIGINS'))) {
            return $origin;
        }

        $origensPermitidas = explode(',', env('ALLOWED_ORIGINS'));
        if (in_array($origin, $origensPermitidas) || $origensPermitidas[0] === "*") {
            return $origin;
        }
        return array_shift($origensPermitidas);
    }
}
