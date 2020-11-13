<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Service;

use GlsGroup\Sdk\ParcelProcessing\Api\CancellationServiceInterface;
use GlsGroup\Sdk\ParcelProcessing\Exception\AuthenticationException;
use GlsGroup\Sdk\ParcelProcessing\Exception\DetailedErrorException;
use GlsGroup\Sdk\ParcelProcessing\Exception\DetailedServiceException;
use GlsGroup\Sdk\ParcelProcessing\Exception\ServiceException;
use GlsGroup\Sdk\ParcelProcessing\Exception\ServiceExceptionFactory;
use GlsGroup\Sdk\ParcelProcessing\Model\Cancellation\CancelParcelsResponseType;
use GlsGroup\Sdk\ParcelProcessing\Model\Cancellation\ResponseType\Status;
use GlsGroup\Sdk\ParcelProcessing\Serializer\JsonSerializer;
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

    public function cancelParcel(string $parcelId): string
    {
        $uri = sprintf('%s%s/parcelids/%s', $this->baseUrl, self::RESOURCE, $parcelId);

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

        $statuses = $cancellationResponse->getStatus();
        $status = array_shift($statuses);
        if ($status->getCode() !== 'E000') {
            $message = sprintf('[%s] %s: %s', $status->getCode(), $status->getParcelId(), $status->getInfo());
            throw new DetailedServiceException($message);
        }

        return $status->getParcelId();
    }
}
