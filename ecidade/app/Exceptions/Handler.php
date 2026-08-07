<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException || substr($request->getPathInfo(), 0, 4) === '/web') {
            return parent::render($request, $exception);
        }

        $message = $exception->getMessage();
        if (!\db_utils::isUTF8($exception->getMessage())) {
            $message = utf8_encode($exception->getMessage());
        }
        $response = array('error' => true, 'message' => $message);
        $statusCode = $this->getStatusCode($exception);

        if (config('app.debug') === 'true') {
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            // $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        return response()->json($response, $statusCode);
    }

    /**
     * @param Exception $exception
     * @return int
     */
    protected function getStatusCode(Exception $exception)
    {
        $statusCode = 500;
        if (is_callable(array($exception, 'getStatusCode'))
            && $exception->getStatusCode() >= 99 && $exception->getStatusCode() < 600
        ) {
            $statusCode = $exception->getStatusCode();
        }

        switch (true) {
            case ($exception instanceof \DBException):
            case ($exception instanceof \ParameterException):
            case ($exception instanceof \BusinessException):
            case ($exception instanceof \Exception):
                if (is_callable(array($exception, 'getCode'))
                    && $exception->getCode() >= 99 && $exception->getCode() < 600
                ) {
                    $statusCode = $exception->getCode();
                }
                break;
            default:
                $statusCode = 500;
        }

        return $statusCode;
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
