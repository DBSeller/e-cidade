<?php

namespace ECidade\Api\V1\Configs;

class ApplicationConfig
{
    public static $instance;

    const APPLICATION_PREFIX = '/api/v1';

    private function __construct()
    {
    }

    /**
     * @return ApplicationConfig
     */
    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    public static function routePrefix()
    {
        return str_replace(
            '/',
            '_',
            substr(self::APPLICATION_PREFIX, 1)
        );
    }
}
