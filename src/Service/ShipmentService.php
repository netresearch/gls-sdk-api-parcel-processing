<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Service;

use GlsGermany\Sdk\ParcelProcessing\Api\ShipmentServiceInterface;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\ShipmentResponseMapper;
use GlsGermany\Sdk\ParcelProcessing\Serializer\JsonSerializer;
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
}
