<?php

namespace App\Domain\Saude\Ambulatorial\Middlewares;

use App\Domain\Saude\Ambulatorial\Models\CgsUnidade;

class HasCgsMiddleware
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, \Closure $next)
    {
        if ($request->header('cgs', '') == '') {
            throw new \Exception("O campo 'cgs' deve ser informado no cabeçalho da requisição.", 400);
        }

        if (!CgsUnidade::find((int)$request->header('cgs'))) {
            throw new \Exception('CGS não encontrado.', 400);
        }

        return $next($request);
    }
}
