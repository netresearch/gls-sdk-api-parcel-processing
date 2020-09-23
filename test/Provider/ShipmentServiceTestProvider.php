<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Test\Provider;

use GlsGermany\Sdk\ParcelProcessing\Api\Data\ShipmentInterface;
use GlsGermany\Sdk\ParcelProcessing\Exception\RequestValidatorException;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentResponseType;
use GlsGermany\Sdk\ParcelProcessing\RequestBuilder\ReturnShipmentRequestBuilder;
use GlsGermany\Sdk\ParcelProcessing\RequestBuilder\ShipmentRequestBuilder;
use PHPUnit\Framework\Assert;

class ShipmentServiceTestProvider
{
    /**
     * Provide request/response for a standard domestic label request with shipper address stored in GLS account.
     *
     * @return CreateShipmentResponseType[]|string[]|callable[]
     * @throws RequestValidatorException
     */
    public static function standardLabel()
    {
        $requestBuilder = new ShipmentRequestBuilder();
        $requestBuilder->setShipperAccount('9876543210');
        $requestBuilder->setRecipientAddress(
            $country = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Nonnenstraße 11d',
            $name = 'Christoph Aßmann'
        );
        $requestBuilder->setReferenceNumbers(['1000000302']);
        $requestBuilder->addParcel(0.95, '1000000302-A');

        $assertionsCallback = function (ShipmentInterface $shipment) {
            Assert::assertNotEmpty($shipment->getLabels());
            Assert::assertEmpty($shipment->getQrCodes());
        };

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/201_standard_label.json'),
            'assertions' => $assertionsCallback,
        ];
    }

    /**
     * Provide request/response for a domestic label request with COD service.
     *
     * @return CreateShipmentResponseType[]|string[]|callable[]
     * @throws RequestValidatorException
     */
    public static function codLabel()
    {
        $requestBuilder = new ShipmentRequestBuilder();
        $requestBuilder->setShipperAccount('9876543210');
        $requestBuilder->setRecipientAddress(
            $country = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Nonnenstraße 11d',
            $name = 'Christoph Aßmann'
        );
        $requestBuilder->addParcel(0.95, null, null, 34.99, 'Order #1000000303');

        $assertionsCallback = function (ShipmentInterface $shipment) {
            Assert::assertNotEmpty($shipment->getLabels());
            Assert::assertEmpty($shipment->getQrCodes());
        };

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/201_standard_label.json'),
            'assertions' => $assertionsCallback,
        ];
    }

    /**
     * Provide request/response for a domestic label request with COD service.
     *
     * @return CreateShipmentResponseType[]|string[]|callable[]
     * @throws RequestValidatorException
     */
    public static function flexDeliveryLabel()
    {
        $requestBuilder = new ShipmentRequestBuilder();
        $requestBuilder->setShipperAccount('9876543210');
        $requestBuilder->setRecipientAddress(
            $country = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Nonnenstraße 11d',
            $name = 'Christoph Aßmann',
            null,
            $email = 'christoph.assmann@netresearch.de'
        );
        $requestBuilder->requestFlexDeliveryService();
        $requestBuilder->addParcel(0.95);

        $assertionsCallback = function (ShipmentInterface $shipment) {
            Assert::assertNotEmpty($shipment->getLabels());
            Assert::assertEmpty($shipment->getQrCodes());
        };

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/201_standard_label.json'),
            'assertions' => $assertionsCallback,
        ];
    }

    /**
     * Provide request/response for a multi-piece domestic label request with alternative shipper address.
     *
     * @return CreateShipmentResponseType[]|string[]|callable[]
     * @throws RequestValidatorException
     */
    public static function multiPieceLabel()
    {
        $requestBuilder = new ShipmentRequestBuilder();
        $requestBuilder->setShipperAccount('9876543210');
        $requestBuilder->setShipperAddress(
            $country = 'DE',
            $postalCode = '36286',
            $city = 'Neuenstein',
            $street = 'GLS Germany-Straße 1 - 7',
            $company = 'GLS Germany'
        );
        $requestBuilder->setRecipientAddress(
            $country = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Nonnenstraße 11d',
            $name = 'Christoph Aßmann'
        );
        $requestBuilder->addParcel(0.95);
        $requestBuilder->addParcel(1.30);

        $assertionsCallback = function (ShipmentInterface $shipment) {
            Assert::assertNotEmpty($shipment->getLabels());
            Assert::assertEmpty($shipment->getQrCodes());
        };

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/201_multipiece_label.json'),
            'assertions' => $assertionsCallback,
        ];
    }

    /**
     * Provide request/response for a domestic label request with enclosed return shipment label.
     *
     * @return CreateShipmentResponseType[]|string[]|callable[]
     * @throws RequestValidatorException
     */
    public static function enclosedReturnLabel()
    {
        $requestBuilder = new ShipmentRequestBuilder();
        $requestBuilder->setShipperAccount('9876543210');
        $requestBuilder->setRecipientAddress(
            $country = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Nonnenstraße 11d',
            $name = 'Christoph Aßmann'
        );
        $requestBuilder->setReturnAddress(
            $country = 'DE',
            $postalCode = '36286',
            $city = 'Neuenstein',
            $street = 'GLS Germany-Straße 1 - 7',
            $company = 'GLS Germany'
        );
        $requestBuilder->setReferenceNumbers(['1000000606']);
        $requestBuilder->addParcel(0.95, '1000000606-A', '1000000606-R');

        $assertionsCallback = function (ShipmentInterface $shipment) {
            Assert::assertNotEmpty($shipment->getLabels());
            Assert::assertEmpty($shipment->getQrCodes());
        };

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/201_enclosed_return_label.json'),
            'assertions' => $assertionsCallback,
        ];
    }

    /**
     * Provide request/response for a domestic label request with delivery to a given parcel shop.
     *
     * @return CreateShipmentResponseType[]|string[]|callable[]
     * @throws RequestValidatorException
     */
    public static function shopDeliveryLabel()
    {
        $requestBuilder = new ShipmentRequestBuilder();
        $requestBuilder->setShipperAccount('9876543210');
        $requestBuilder->setParcelShopId('2760196671');
        $requestBuilder->setRecipientAddress(
            $country = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Nonnenstraße 11d',
            $name = 'Christoph Aßmann',
            null,
            $email = 'christoph.assmann@netresearch.de',
            null,
            null,
            $contactPerson = 'Christoph Aßmann'
        );
        $requestBuilder->addParcel(0.95);

        $assertionsCallback = function (ShipmentInterface $shipment) {
            Assert::assertNotEmpty($shipment->getLabels());
            Assert::assertEmpty($shipment->getQrCodes());
        };

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/201_standard_label.json'),
            'assertions' => $assertionsCallback,
        ];
    }

    /**
     * Provide request/response for a domestic label request with pickup from given address.
     *
     * Supplier-to-Consumer with pickup at the manufacturer/retailer/wholesaler (pick&ship).
     * Note that response does not contain a label (brought by courier).
     *
     * @return CreateShipmentResponseType[]|string[]|callable[]
     * @throws RequestValidatorException
     */
    public static function pickAndShip()
    {
        $requestBuilder = new ShipmentRequestBuilder();
        $requestBuilder->setShipperAccount('9876543210');
        $requestBuilder->setPickupAddress(
            $country = 'DE',
            $postalCode = '04103',
            $city = 'Leipzig',
            $street = 'Brüderstr. 6',
            $name = 'Corsoela GmbH'
        );
        $requestBuilder->setRecipientAddress(
            $country = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Nonnenstraße 11d',
            $name = 'Christoph Aßmann'
        );
        $requestBuilder->addParcel(0.95);

        $assertionsCallback = function (ShipmentInterface $shipment) {
            Assert::assertEmpty($shipment->getLabels());
            Assert::assertEmpty($shipment->getQrCodes());
        };

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/201_parcel_pickup.json'),
            'assertions' => $assertionsCallback,
        ];
    }

    /**
     * Provide request/response for a domestic return label request with pickup from given address.
     *
     * Return shipment from consumer to merchant (pick&return).
     * Note that response does not contain a label (brought by courier).
     *
     * @return CreateShipmentResponseType[]|string[]|callable[]
     * @throws RequestValidatorException
     */
    public static function pickAndReturn()
    {
        $requestBuilder = new ReturnShipmentRequestBuilder();
        $requestBuilder->setShipperAccount('9876543210');
        $requestBuilder->setPickupAddress(
            $country = 'DE',
            $postalCode = '04103',
            $city = 'Leipzig',
            $street = 'Brüderstr. 6',
            $name = 'Corsoela GmbH'
        );
        $requestBuilder->setRecipientAddress(
            $country = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Nonnenstraße 11d',
            $name = 'Christoph Aßmann'
        );
        $requestBuilder->addParcel(0.95);

        $assertionsCallback = function (ShipmentInterface $shipment) {
            Assert::assertEmpty($shipment->getLabels());
            Assert::assertEmpty($shipment->getQrCodes());
        };

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/201_parcel_pickup.json'),
            'assertions' => $assertionsCallback,
        ];
    }

    /**
     * Provide request/response for a domestic return label request with QR code and drop-off at parcel shop.
     *
     * @return CreateShipmentResponseType[]|string[]|callable[]
     * @throws RequestValidatorException
     */
    public static function shopReturnQrLabel()
    {
        $requestBuilder = new ReturnShipmentRequestBuilder();
        $requestBuilder->setShipperAccount('9876543210');
        $requestBuilder->setShipperAddress(
            $country = 'DE',
            $postalCode = '04229',
            $city = 'Leipzig',
            $street = 'Nonnenstraße 11d',
            $name = 'Christoph Aßmann'
        );
        $requestBuilder->setRecipientAddress(
            $country = 'DE',
            $postalCode = '36286',
            $city = 'Neuenstein',
            $street = 'GLS Germany-Straße 1 - 7',
            $company = 'GLS Germany'
        );
        $requestBuilder->addParcel(0.95, true);

        $assertionsCallback = function (ShipmentInterface $shipment) {
            Assert::assertNotEmpty($shipment->getLabels());
            Assert::assertNotEmpty($shipment->getQrCodes());
        };

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/201_shop_return_label.json'),
            'assertions' => $assertionsCallback,
        ];
    }
}
