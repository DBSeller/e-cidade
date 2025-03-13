<?php

namespace ECidade\Api\V1\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

interface RequestMiddlewareInterface
{
    /**
     * @param Request $request
     * @param Application $application
     */
    public static function handle(Request $request, Application $application);
}
