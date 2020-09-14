<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Service;

use GlsGermany\Sdk\ParcelProcessing\Exception\ServiceException;
use GlsGermany\Sdk\ParcelProcessing\Http\HttpServiceFactory;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentRequestType;
use GlsGermany\Sdk\ParcelProcessing\Test\Provider\ShipmentServiceTestProvider;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class ShipmentServiceTest extends TestCase
{
    public function labelDataProvider()
    {
        return [
            'standard' => ShipmentServiceTestProvider::standardLabel(),
            'multi-piece' => ShipmentServiceTestProvider::multiPieceLabel()
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
    public function requestStandardLabel(CreateShipmentRequestType $shipmentRequest, string $responseBody)
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
     * Request a standard merchant-to-consumer label with consumer notification.
     */
    public function requestFlexDeliveryLabel()
    {

    }

    /**
     * Request a merchant-to-consumer label with COD payment.
     */
    public function requestCODLabel()
    {

    }

    /**
     * Request a merchant-to-consumer label with parcel shop delivery.
     */
    public function requestShopDeliveryLabel()
    {

    }

    /**
     * Request a merchant-to-consumer label with enclosed return shipment label.
     */
    public function requestEnclosedReturnLabel()
    {

    }

    /**
     * Request a supplier-to-consumer label with pickup at the manufacturer/retailer/wholesaler.
     */
    public function requestPickAndShipLabel()
    {

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

