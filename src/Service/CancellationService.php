<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Service;

use GlsGermany\Sdk\ParcelProcessing\Api\CancellationServiceInterface;
use GlsGermany\Sdk\ParcelProcessing\Exception\AuthenticationException;
use GlsGermany\Sdk\ParcelProcessing\Exception\DetailedErrorException;
use GlsGermany\Sdk\ParcelProcessing\Exception\DetailedServiceException;
use GlsGermany\Sdk\ParcelProcessing\Exception\ServiceException;
use GlsGermany\Sdk\ParcelProcessing\Exception\ServiceExceptionFactory;
use GlsGermany\Sdk\ParcelProcessing\Model\Cancellation\CancelParcelsResponseType;
use GlsGermany\Sdk\ParcelProcessing\Model\Cancellation\ResponseType\Status;
use GlsGermany\Sdk\ParcelProcessing\Serializer\JsonSerializer;
use Http\Client\HttpClient;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class CancellationService implements CancellationServiceInterface
{
    private const RESOURCE = 'public/v1/cancellation';

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    public function __construct(
        HttpClient $client,
        string $baseUrl,
        JsonSerializer $serializer,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory
    ) {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->serializer = $serializer;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    /**
     * @param string[] $parcelIds
     * @return string[]
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    private function cancel(array $parcelIds): array
    {
        $uri = sprintf('%s%s/parcelids/%s', $this->baseUrl, self::RESOURCE, implode(',', $parcelIds));

        try {
            $httpRequest = $this->requestFactory->createRequest('PUT', $uri);
            $response = $this->client->sendRequest($httpRequest);
            $responseJson = (string) $response->getBody();

            /** @var CancelParcelsResponseType $cancellationResponse */
            $cancellationResponse = $this->serializer->decode($responseJson, CancelParcelsResponseType::class);
        } catch (DetailedErrorException $exception) {
            if ($exception->getCode() === 401) {
                throw ServiceExceptionFactory::createAuthenticationException($exception);
            }

            throw ServiceExceptionFactory::createDetailedServiceException($exception);
        } catch (\Throwable $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        return array_reduce(
            $cancellationResponse->getStatus(),
            function (array $cancelled, Status $status) {
                if ($status->getCode() === 'E000') {
                    $cancelled[] = $status->getParcelId();
                }
                return $cancelled;
            },
            []
        );
    }

    public function cancelParcels(array $parcelIds): array
    {
        if (empty($parcelIds)) {
            throw new DetailedServiceException('No parcels given to cancel.');
        }

        $cancelled = array_map(
            function (array $parcelIds) {
                return $this->cancel($parcelIds);
            },
            array_chunk($parcelIds, 20)
        );

        return array_reduce($cancelled, 'array_merge', []);
    }
}
