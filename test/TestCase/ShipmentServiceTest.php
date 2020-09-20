<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Service;

use GlsGermany\Sdk\ParcelProcessing\Exception\RequestValidatorException;
use GlsGermany\Sdk\ParcelProcessing\Exception\ServiceException;
use GlsGermany\Sdk\ParcelProcessing\Http\HttpServiceFactory;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentRequestType;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentResponseType;
use GlsGermany\Sdk\ParcelProcessing\RequestBuilder\ShipmentRequestBuilder;
use GlsGermany\Sdk\ParcelProcessing\Test\Provider\ShipmentServiceTestProvider;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class ShipmentServiceTest extends TestCase
{
    /**
     * @return CreateShipmentResponseType[][]|string[][]
     * @throws RequestValidatorException
     */
    public function labelDataProvider()
    {
        return [
            'standard' => ShipmentServiceTestProvider::standardLabel(),
            'cod' => ShipmentServiceTestProvider::codLabel(),
            'flex-delivery' => ShipmentServiceTestProvider::flexDeliveryLabel(),
            'multi-piece' => ShipmentServiceTestProvider::multiPieceLabel(),
            'enclosed-return' => ShipmentServiceTestProvider::enclosedReturnLabel(),
            'parcel-shop' => ShipmentServiceTestProvider::shopDeliveryLabel(),
        ];
    }

    /**
     * @return CreateShipmentResponseType[][]|string[][]
     * @throws RequestValidatorException
     */
    public function pickupShipmentDataProvider()
    {
        return [
            'pickandship' => ShipmentServiceTestProvider::pickAndShipLabel(),
        ];
    }

    /**
     * Request standard merchant-to-consumer labels.
     *
     * @test
     * @dataProvider labelDataProvider
     *
     * @param CreateShipmentRequestType $shipmentRequest
     * @param string $responseBody
     * @throws ServiceException
     */
    public function requestLabel(CreateShipmentRequestType $shipmentRequest, string $responseBody)
    {
        $logger = new TestLogger();
        $httpClient = new Client();
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $httpClient->setDefaultResponse(
            $responseFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($responseBody))
        );

        $serviceFactory = new HttpServiceFactory($httpClient);
        $service = $serviceFactory->createShipmentService('u5er', 'p4ss', $logger, true);

        $shipment = $service->createShipment($shipmentRequest);
        self::assertNotEmpty($shipment->getLabel());
    }

    /**
     * Request a supplier-to-consumer label with pickup at the manufacturer/retailer/wholesaler.
     *
     * Courier will bring the label to the pickup location, thus no shipment label in response.
     *
     * @test
     * @dataProvider pickupShipmentDataProvider
     *
     * @param CreateShipmentRequestType $shipmentRequest
     * @param string $responseBody
     * @throws ServiceException
     */
    public function requestPickAndShipLabel(CreateShipmentRequestType $shipmentRequest, string $responseBody)
    {
        $logger = new TestLogger();
        $httpClient = new Client();
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $httpClient->setDefaultResponse(
            $responseFactory
                ->createResponse(200, 'OK')
                ->withBody($streamFactory->createStream($responseBody))
        );

        $serviceFactory = new HttpServiceFactory($httpClient);
        $service = $serviceFactory->createShipmentService('u5er', 'p4ss', $logger, true);

        $shipment = $service->createShipment($shipmentRequest);
        self::assertEmpty($shipment->getLabel());
    }

    /**
     * Request a standalone return shipment label with address pickup.
     */
    public function requestPickAndReturnLabel()
    {

    }

    /**
     * Request a standalone return shipment label with parcel shop drop-off.
     */
    public function requestShopReturnLabel()
    {

    }
}

