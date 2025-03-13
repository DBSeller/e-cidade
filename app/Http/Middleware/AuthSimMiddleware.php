<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * Class AuthSimMiddleware
 * @package App\Http\Middleware
 */
class AuthSimMiddleware
{
    /**
     * @param $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        $dataToken = null;
        try {
            $this->verifyOrigin($request->server->get('REMOTE_ADDR'));
            if (!$request->headers->has('Authorization')) {
                Log::warning("[Api SIM] Credenciais não informadas");
                throw new Exception('Credenciais (usuário/senha ou token) inválidos.', 401);
            }
            $dataToken = $this->parseToken($request->headers->get('Authorization'));
            $this->attempt($dataToken->user, $dataToken->hash);
        } catch (Exception $exception) {
            $dataLogger = (object)[
                "remote_ip" => $request->server->get('REMOTE_ADDR'),
                "basic_token" => $request->headers->has('Authorization'),
                "data_token" => $dataToken
            ];
            Log::warning("[Api SIM] Erro: {$exception->getMessage()}", ['data' => $dataLogger]);
            return response()->json([
                'error' => true,
                'message' => utf8_encode($exception->getMessage())
            ], $exception->getCode()?:500);
        }

        return $next($request);
    }

    /**
     * @param $token
     * @return object
     */
    private function parseToken($token)
    {
        $basicToken = base64_decode(substr($token, 6, -1));
        list($user, $hash) = explode(':', $basicToken);
        return (object)['user' => $user, 'hash' => $hash];
    }

    /**
     * @param $user
     * @param $hash
     * @return void
     * @throws Exception
     */
    private function attempt($user, $hash)
    {
        if (empty(env('SSP_USER')) || empty(env('SSP_USER'))) {
            Log::alert("[Api SIM] Arquivo .env não configurado corretamente");
            throw new Exception("Credenciais não configuradas!");
        }

        if (env('SSP_USER') != $user) {
            Log::warning("[Api SIM] Usuário inválido ", ["user" => $user]);
            throw new Exception('Credenciais (usuário/senha ou token) inválidos.', 401);
        }

        $passwordEnv = env('SSP_PASS') . date('Y-m-d');

        if (hash('sha256', $passwordEnv) != $hash) {
            Log::warning("[Api SIM] Senha inválida: ", ["hash" => $hash]);
            throw new Exception('Credenciais (usuário/senha ou token) inválidos.', 401);
        }
    }

    private function verifyOrigin($remoteIp)
    {
        if (empty(env('SSP_WHITE_LIST')) || !in_array($remoteIp, explode(',', env('SSP_WHITE_LIST')))) {
            Log::alert("[Api SIM] Acesso Negado IP: " . $remoteIp);
            throw new Exception("Acesso negado!", 403);
        }
        Log::info("[Api SIM] Acesso autorizado IP: " . $remoteIp);
    }
}
