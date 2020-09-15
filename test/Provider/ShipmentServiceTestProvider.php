<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Test\Provider;

use GlsGermany\Sdk\ParcelProcessing\Exception\RequestValidatorException;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentResponseType;
use GlsGermany\Sdk\ParcelProcessing\RequestBuilder\ShipmentRequestBuilder;

class ShipmentServiceTestProvider
{
    /**
     * Provide request/response for a standard domestic label request with shipper address stored in GLS account.
     *
     * @return CreateShipmentResponseType[]|string[]
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
        $requestBuilder->addParcel(0.95);

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/200_standard_label.json'),
        ];
    }

    /**
     * Provide request/response for a domestic label request with COD service.
     *
     * @return CreateShipmentResponseType[]|string[]
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

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/200_standard_label.json'),
        ];
    }

    /**
     * Provide request/response for a domestic label request with COD service.
     *
     * @return CreateShipmentResponseType[]|string[]
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

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/200_standard_label.json'),
        ];
    }

    /**
     * Provide request/response for a multi-piece domestic label request with alternative shipper address.
     *
     * @return CreateShipmentResponseType[]|string[]
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

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/200_multipiece_label.json'),
        ];
    }

    /**
     * Provide request/response for a domestic label request with enclosed return shipment label.
     *
     * @return CreateShipmentResponseType[]|string[]
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
        $requestBuilder->addParcel(0.95);

        return [
            'request' => $requestBuilder->create(),
            'response' => \file_get_contents(__DIR__ . '/_files/200_enclosed_return_label.json'),
        ];
    }
}
