<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

/**
 * Class DBRequestMiddleware
 * @package App\Http\Middleware
 */
class DBRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        unset($request['_path']);

        $dados = $request->all();
        self::decodeFromUtf8($dados);
        $request->merge($dados);

        return $next($request);
    }

    /**
     * Funcao que percorre a request decodificando
     *
     * @param type &$request
     * @return type
     */
    public static function decodeFromUtf8(&$request)
    {
        foreach ($request as $key => $value) {
            if ($key == "_path") {
                continue;
            }

            if (is_array($value)) {
                self::decodeFromUtf8($value);
                continue;
            }

            $request[$key] = utf8_decode($value);
        }
    }
}
