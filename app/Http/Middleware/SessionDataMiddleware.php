<?php

namespace App\Http\Middleware;

use Closure;
use ECidade\Lib\Session\DefaultSession;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class SessionMiddleware
 * @package App\Http\Middleware
 */
class SessionDataMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        $defaultSession = DefaultSession::getInstance();

        if (!empty($defaultSession->get("DB_instit"))) {
            $sql = "select fc_putsession('DB_instit'::varchar, '" . $defaultSession->get("DB_instit") . "')";
            DB::statement($sql);
        }
        if (!empty($defaultSession->get("DB_id_usuario"))) {
            $sql = "select fc_putsession('DB_id_usuario'::varchar, '" . $defaultSession->get("DB_id_usuario") . "')";
            DB::statement($sql);
        }
        if (!empty($defaultSession->get("DB_login"))) {
            DB::statement("select fc_putsession('DB_login'::varchar, '" . $defaultSession->get("DB_login") . "')");
        }

        DB::statement("select fc_putsession('DB_datausu'::varchar, current_date::varchar)");
        DB::statement("select fc_putsession('DB_anousu'::varchar, extract(year from current_date)::varchar)");
        DB::statement("select fc_putsession('DB_use_pcasp'::varchar,  '1')");

        return $next($request);
    }
}
