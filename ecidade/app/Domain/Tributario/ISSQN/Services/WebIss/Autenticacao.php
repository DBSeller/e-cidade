<?php
namespace App\Domain\Tributario\ISSQN\Services\WebIss;

use stdClass;

class Autenticacao
{
  
    public static function getWebIssConfig()
    {
        $configApi = new stdClass();
        $configApi->url          = env('URL_WEBISS');
        $configApi->autenticacao = env('AUTENTICACAO_WEBISS');
        if (empty($configApi->url) || empty($configApi->autenticacao)) {
            return false;
        }

        return (object)$configApi;
    }
}
