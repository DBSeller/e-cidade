<?php

namespace App\Http\Middleware;

use Closure;
use ECidade\Lib\Session\DefaultSession;

/**
 * Classe que monta os dados da sessão, geralmente usado em codigo legado.
 */
class LegacySessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $defaultSession = DefaultSession::getInstance();

        if (empty($defaultSession->get(DefaultSession::DB_INSTIT))) {
            \db_putsession(DefaultSession::DB_INSTIT, $defaultSession->get(DefaultSession::DB_INSTIT));
        }

        if (empty($defaultSession->get(DefaultSession::DB_CODDEPTO))) {
            \db_putsession(DefaultSession::DB_CODDEPTO, $defaultSession->get(DefaultSession::DB_CODDEPTO));
        }

        if (empty($defaultSession->get(DefaultSession::DB_USE_PCASP))) {
            \db_putsession(DefaultSession::DB_USE_PCASP, $defaultSession->get(DefaultSession::DB_USE_PCASP));
        }

        if (empty($defaultSession->get(DefaultSession::DB_ANO_PCASP))) {
            \db_putsession(DefaultSession::DB_ANO_PCASP, $defaultSession->get(DefaultSession::DB_ANO_PCASP));
        }

        if (empty($defaultSession->get(DefaultSession::DB_DATAUSU))) {
            \db_putsession(DefaultSession::DB_DATAUSU, $defaultSession->get(DefaultSession::DB_DATAUSU));
        }

        if (empty($defaultSession->get(DefaultSession::DB_ANOUSU))) {
            \db_putsession(DefaultSession::DB_ANOUSU, $defaultSession->get(DefaultSession::DB_ANOUSU));
        }

        if (empty($defaultSession->get(DefaultSession::DB_ID_USUARIO))) {
            \db_putsession(DefaultSession::DB_ID_USUARIO, $defaultSession->get(DefaultSession::DB_ID_USUARIO));
        }

        if (empty($defaultSession->get(DefaultSession::DB_LOGIN))) {
            \db_putsession(DefaultSession::DB_LOGIN, $defaultSession->get(DefaultSession::DB_LOGIN));
        }

        if (empty($defaultSession->get(DefaultSession::DB_IP))) {
            \db_putsession(DefaultSession::DB_IP, $defaultSession->get(DefaultSession::DB_IP));
        }

        if (empty($defaultSession->get(DefaultSession::DB_MODULO))) {
            \db_putsession(DefaultSession::DB_MODULO, $defaultSession->get(DefaultSession::DB_MODULO));
        }

        if (empty($defaultSession->get(DefaultSession::DB_NOME_MODULO))) {
            \db_putsession(DefaultSession::DB_NOME_MODULO, $defaultSession->get(DefaultSession::DB_NOME_MODULO));
        }

        if (empty($defaultSession->get(DefaultSession::DB_UOL_HORA))) {
            \db_putsession(DefaultSession::DB_UOL_HORA, $defaultSession->get(DefaultSession::DB_UOL_HORA));
        }

        if (empty($defaultSession->get(DefaultSession::DB_REQUEST_FROM_API))) {
            \db_putsession(
                DefaultSession::DB_REQUEST_FROM_API,
                $defaultSession->get(DefaultSession::DB_REQUEST_FROM_API)
            );
        }

        if (empty($defaultSession->get(DefaultSession::DB_ACESSADO))) {
            \db_putsession(DefaultSession::DB_ACESSADO, $defaultSession->get(DefaultSession::DB_ACESSADO));
        }

        if (empty($defaultSession->get(DefaultSession::DB_BASE))) {
            \db_putsession(DefaultSession::DB_BASE, $defaultSession->get(DefaultSession::DB_BASE));
        }

        return $next($request);
    }
}
