<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseMiddleware
{
    use ResponseTrait;

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $response = $next($request);
        if (!$response instanceof Response) {
            $response = response($response);
        }

        return $this->getResponse($request, $response);
    }
}
