<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Http\ClientPlugin;

use GlsGroup\Sdk\ParcelProcessing\Exception\DetailedErrorException;
use Http\Client\Common\Plugin;
use Http\Client\Exception\HttpException;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Convert errors into exceptions, parse exception message from response if available.
 */
final class ErrorPlugin implements Plugin
{
    /**
     * Returns TRUE if the response contains a parsable body.
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function isDetailedErrorResponse(ResponseInterface $response): bool
    {
        $contentTypes = $response->getHeader('Content-Type');
        return $contentTypes && (strpos($contentTypes[0], 'json') !== false);
    }

    /**
     * Returns the formatted error message.
     *
     * @param int $statusCode The response status code
     * @param string $reasonPhrase The response reason phrase
     * @param string[][] $responseData The error response data
     * @return string
     */
    private function formatErrorMessage(int $statusCode, string $reasonPhrase, array $responseData): string
    {
        if (empty($responseData['errors'])) {
            return "[$statusCode] $reasonPhrase";
        }

        $errorMessages = array_map(
            function (array $error) {
                return sprintf('[%s] %s – %s', $error['exitCode'], $error['exitMessage'], $error['description']);
            },
            $responseData['errors']
        );

        return implode(" | ", $errorMessages);
    }

    /**
     * Handles client/server errors with error messages in response body.
     *
     * @param int $statusCode
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return void
     *
     * @throws DetailedErrorException
     */
    private function handleDetailedError(int $statusCode, RequestInterface $request, ResponseInterface $response)
    {
        $responseJson = (string) $response->getBody();
        $responseData = \json_decode($responseJson, true);
        $errorMessage = $this->formatErrorMessage($statusCode, $response->getReasonPhrase(), $responseData);

        throw new DetailedErrorException($errorMessage, $request, $response);
    }

    /**
     * Handles all client/server errors when response does not contains body with error message.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @return void
     *
     * @throws HttpException
     */
    private function handleError(RequestInterface $request, ResponseInterface $response)
    {
        throw new HttpException($response->getReasonPhrase(), $request, $response);
    }

    /**
     * Handle the request and return the response coming from the next callable.
     *
     * @param RequestInterface $request
     * @param callable $next Next middleware in the chain, the request is passed as the first argument
     * @param callable $first First middleware in the chain, used to to restart a request
     *
     * @return Promise Resolves a PSR-7 Response or fails with an Http\Client\Exception (The same as HttpAsyncClient).
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        /** @var Promise $promise */
        $promise = $next($request);

        // a response is available. transform error responses into exceptions
        $fnFulfilled = function (ResponseInterface $response) use ($request) {
            $statusCode = $response->getStatusCode();

            if ($statusCode >= 400 && $statusCode < 600) {
                $this->isDetailedErrorResponse($response)
                    ? $this->handleDetailedError($statusCode, $request, $response)
                    : $this->handleError($request, $response);
            }

            // no error
            return $response;
        };

        return $promise->then($fnFulfilled);
    }
}
