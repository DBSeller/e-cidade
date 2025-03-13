<?php

namespace App\Http\Middleware;

use Closure;
use ECidade\V3\Datasource\Database;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as BaseResponse;

/**
 * Class DatabaseMiddleware
 * @package App\Http\Middleware
 */
class DatabaseMiddleware
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

        $database = Database::getInstance(true, null, true);

        global $conn;
        $conn = $database->getConnection();

        Config::set('database.connections.pgsql.host', $database->getServidor());
        Config::set('database.connections.pgsql.port', $database->getPorta());
        Config::set('database.connections.pgsql.database', $database->getBase());
        Config::set('database.connections.pgsql.username', $database->getUsuario());
        Config::set('database.connections.pgsql.password', $database->getSenha());

        DB::purge('pgsql');
        DB::beginTransaction();
        $database->begin();

        $response = $next($request);

        if ($response instanceof BaseResponse && ($response->isSuccessful() || $response->isRedirection())) {
            // DB::rollBack();
            // $database->rollBack();
            DB::commit();
            $database->commit();
        } else {
            DB::rollBack();
            $database->rollBack();
        }

        return $response;
    }
}
