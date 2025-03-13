<?php


namespace ECidade\Api\V1\Controllers;


use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class GenericApplicationController extends GenericController
{
    /**
     * @var Application
     */
    private $application;

    /**
     * GenericApplicationController constructor.
     * @param Request $request
     * @param Application $application
     */
    public function __construct(Request $request, Application $application)
    {
        parent::__construct($request);
        $this->application = $application;
    }

    /**
     * @return Application
     */
    public function application()
    {
        return $this->application;
    }
}
