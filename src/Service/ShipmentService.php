<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Service;

use GlsGroup\Sdk\ParcelProcessing\Api\Data\ShipmentInterface;
use GlsGroup\Sdk\ParcelProcessing\Api\ShipmentServiceInterface;
use GlsGroup\Sdk\ParcelProcessing\Exception\DetailedErrorException;
use GlsGroup\Sdk\ParcelProcessing\Exception\ServiceExceptionFactory;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentResponseType;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\ShipmentResponseMapper;
use GlsGroup\Sdk\ParcelProcessing\Serializer\JsonSerializer;
use Http\Client\HttpClient;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class ShipmentService implements ShipmentServiceInterface
{
    private const RESOURCE = 'public/v1/shipments';

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

    /**
     * @var ShipmentResponseMapper
     */
    private $responseMapper;

    public function __construct(
        HttpClient $client,
        string $baseUrl,
        JsonSerializer $serializer,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        ShipmentResponseMapper $responseMapper
    ) {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->serializer = $serializer;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->responseMapper = $responseMapper;
    }

    public function createShipment(\JsonSerializable $shipmentRequest): ShipmentInterface
    {
        $uri = $this->baseUrl . self::RESOURCE;

        try {
            $payload = $this->serializer->encode($shipmentRequest);
            $stream = $this->streamFactory->createStream($payload);

            $httpRequest = $this->requestFactory->createRequest('POST', $uri)->withBody($stream);

            $response = $this->client->sendRequest($httpRequest);
            $responseJson = (string) $response->getBody();

            /** @var CreateShipmentResponseType $shipmentResponse */
            $shipmentResponse = $this->serializer->decode($responseJson, CreateShipmentResponseType::class);
        } catch (DetailedErrorException $exception) {
            if ($exception->getCode() === 401) {
                throw ServiceExceptionFactory::createAuthenticationException($exception);
            }

            throw ServiceExceptionFactory::createDetailedServiceException($exception);
        } catch (\Throwable $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        return $this->responseMapper->map($shipmentResponse);
    }
}
