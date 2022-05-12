<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait ResponseTrait
{
    /**
     * @param Request $request
     * @param SymfonyResponse $response
     * @param bool $status
     * @param int $code
     * @return SymfonyResponse
     * @throws \Exception
     */
    public function getResponse(Request $request, SymfonyResponse $response, bool $status = true, int $code = 0): SymfonyResponse
    {
        if ($response->getStatusCode() !== SymfonyResponse::HTTP_INTERNAL_SERVER_ERROR) {
            $requestParams = [
                'success' => $status,
                'code' => $code,
            ];
            if ($response instanceof JsonResponse) {
                $responseData = $response->getData(true);
                if (!isset($responseData['success'])) {
                    $responseData = $response->getData(true);
                    $requestParams['data'] = $responseData;
                    $response->setData($requestParams);
                }
            } elseif ($response instanceof Response) {
                $requestParams['data'] = $response->getOriginalContent();
                $response->setContent($requestParams);
            }
        }
        return $response;
    }
}
