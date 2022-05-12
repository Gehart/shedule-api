<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseTrait;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $e
     * @return JsonResponse|\Illuminate\Http\Response|Response
     *
     */
    public function render($request, Throwable $e): mixed
    {
        $data = [];

        $data['message'] = $e->getMessage();

        $httpStatusCode = Response::HTTP_OK;
        switch (true) {
            case $e instanceof ValidationException:
                $data['message'] = $e->validator->errors()->first();

                break;
            case $e instanceof NotFoundHttpException:
                $data['message'] = 'Invalid url: ' . $request->url();

                break;
            case $e instanceof MethodNotAllowedHttpException:
                $data['message'] = 'Invalid method: ' . $request->url();

                break;
            default:
                if (getenv('APP_DEBUG')) {
                    $data['trace'] = $e->getTraceAsString();
                }
        }

        $code = $e->getCode();
        $response = new JsonResponse($data, $httpStatusCode);

        return $this->getResponse($request, $response, false, $code);
    }
}
