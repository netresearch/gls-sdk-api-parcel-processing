<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Service;

use GlsGroup\Sdk\ParcelProcessing\Exception\RequestValidatorException;
use GlsGroup\Sdk\ParcelProcessing\Exception\ServiceException;
use GlsGroup\Sdk\ParcelProcessing\Http\HttpServiceFactory;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentRequestType;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentResponseType;
use GlsGroup\Sdk\ParcelProcessing\Test\Provider\ShipmentServiceTestProvider;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\Test\TestLogger;

class ShipmentServiceTest extends TestCase
{
    /**
     * @return CreateShipmentResponseType[][]|string[][]|callable[][]
     * @throws RequestValidatorException
     */
    public function dataProvider()
    {
        return [
            'standard' => ShipmentServiceTestProvider::standardLabel(),
            'cod' => ShipmentServiceTestProvider::codLabel(),
            'flex-delivery' => ShipmentServiceTestProvider::flexDeliveryLabel(),
            'deposit' => ShipmentServiceTestProvider::depositLabel(),
            'next-day' => ShipmentServiceTestProvider::nextDayLabel(),
            'multi-piece' => ShipmentServiceTestProvider::multiPieceLabel(),
            'enclosed-return' => ShipmentServiceTestProvider::enclosedReturnLabel(),
            'parcel-shop' => ShipmentServiceTestProvider::shopDeliveryLabel(),
            'pickandship' => ShipmentServiceTestProvider::pickAndShip(),
            'pickandreturn' => ShipmentServiceTestProvider::pickAndReturn(),
            'shopreturn' => ShipmentServiceTestProvider::shopReturnQrLabel(),
        ];
    }

    /**
     * Perform valid request, assert shipment response details.
     *
     * @test
     * @dataProvider dataProvider
     *
     * @param CreateShipmentRequestType $shipmentRequest
     * @param string $responseBody
     * @param callable $assert
     * @throws ServiceException
     */
    public function performRequest(CreateShipmentRequestType $shipmentRequest, string $responseBody, callable $assert)
    {
        $logger = new TestLogger();
        $httpClient = new Client();
        $responseFactory = Psr17FactoryDiscovery::findResponseFactory();
        $streamFactory = Psr17FactoryDiscovery::findStreamFactory();

        $httpClient->setDefaultResponse(
            $responseFactory
                ->createResponse(201, 'Created')
                ->withBody($streamFactory->createStream($responseBody))
        );

        $serviceFactory = new HttpServiceFactory($httpClient);
        $service = $serviceFactory->createShipmentService('u5er', 'p4ss', $logger, true);

        $shipment = $service->createShipment($shipmentRequest);
        $assert($shipment);
    }
}
