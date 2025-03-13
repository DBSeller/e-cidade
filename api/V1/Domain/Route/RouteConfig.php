<?php

namespace ECidade\Api\V1\Domain\Route;

use ECidade\Api\V1\Configs\ApplicationConfig;
use ECidade\Api\V1\Models\Session\MenuAcessadoSession;
use Exception;

class RouteConfig
{
    const ROUTE_LOGIN = '/auth/login';
    const ROUTE_TRIBUTARIO_RECIBO = '/tributario/recibo';
    const ROUTE_MATRICULA_INSCRICAO = '/matriculaonline/inscricao';
    const ROUTE_MATRICULA_EMISSAO_PROTOCOLO = '/matriculaonline/emissaoProtocolo';

    /**
     * @return array
     */
    public static function routes()
    {
        return array(
            self::ROUTE_LOGIN,
            self::ROUTE_TRIBUTARIO_RECIBO,
            self::ROUTE_MATRICULA_INSCRICAO,
            self::ROUTE_MATRICULA_EMISSAO_PROTOCOLO,
        );
    }

    /**
     * @return MenuAcessadoSession[]
     * @throws Exception
     */
    public static function routesMenu()
    {
        return array(
            self::ROUTE_LOGIN => MenuAcessadoSession::build(1, 1),
            self::ROUTE_TRIBUTARIO_RECIBO => MenuAcessadoSession::build(56, 1985522),
            self::ROUTE_MATRICULA_INSCRICAO => MenuAcessadoSession::build(56, 1985522),
            self::ROUTE_MATRICULA_EMISSAO_PROTOCOLO => MenuAcessadoSession::build(56, 1985522),
        );
    }

    /**
     * @return string[]
     */
    public static function userAuthRoutes()
    {
        return array(
            self::ROUTE_TRIBUTARIO_RECIBO
        );
    }

    /**
     * @param $route
     * @return string
     */
    public static function removePrefixFromRoute($route)
    {
        return str_replace(
            ApplicationConfig::APPLICATION_PREFIX,
            '',
            "/{$route}"
        );
    }

    /**
     * @return bool
     * @var string $route
     */
    public static function isGuarded($route)
    {
        return in_array(self::removePrefixFromRoute($route), self::routes());
    }

    /**
     * @param $route
     * @return string
     */
    public static function normalizeRequestRoute($route)
    {
        $match = preg_replace('/^([^_]+_){2}([^_]+)/', '', $route);
        return str_replace('_', '/', $match);
    }
}
