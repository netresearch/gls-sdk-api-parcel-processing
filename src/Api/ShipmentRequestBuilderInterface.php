<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Api;

use GlsGermany\Sdk\ParcelProcessing\Exception\RequestValidatorException;

/**
 * @api
 */
interface ShipmentRequestBuilderInterface
{
    public const LABEL_FORMAT_PDF = 'PDF';
    public const LABEL_FORMAT_PNG = 'PNG';
    public const LABEL_SIZE_A6 = 'A6';
    public const LABEL_SIZE_A5 = 'A5';
    public const LABEL_SIZE_A4 = 'A4';

    /**
     * @param string $shipperId Customer ID / Contact ID as provided by depot or given in the GLS account settings
     * @param string|null $brokerReference Reference to the GLS Partner (optional)
     * @return ShipmentRequestBuilderInterface
     */
    public function setShipperAccount(
        string $shipperId,
        string $brokerReference = null
    ): ShipmentRequestBuilderInterface;

    /**
     * @param string[] $references The customer given reference numbers for all parcels created (optional)
     * @return ShipmentRequestBuilderInterface
     */
    public function setReferenceNumbers(array $references): ShipmentRequestBuilderInterface;

    /**
     * @param \DateTime $shipmentDate Date of shipment (optional, defaults to current date)
     * @return ShipmentRequestBuilderInterface
     */
    public function setShipmentDate(\DateTime $shipmentDate): ShipmentRequestBuilderInterface;

    /**
     * Provide an alternative/customized shipper address.
     *
     * @param string $country
     * @param string $postalCode
     * @param string $city
     * @param string $street
     * @param string $company
     * @param string|null $email
     * @param string|null $phone
     * @param string|null $mobile
     * @param string|null $state
     * @param string|null $companyContactPerson
     * @param string|null $companyDivision
     * @param string|null $companyUnit
     * @param string|null $comment
     * @return ShipmentRequestBuilderInterface
     */
    public function setShipperAddress(
        string $country,
        string $postalCode,
        string $city,
        string $street,
        string $company,
        string $email = null,
        string $phone = null,
        string $mobile = null,
        string $state = null,
        string $companyContactPerson = null,
        string $companyDivision = null,
        string $companyUnit = null,
        string $comment = null
    ): ShipmentRequestBuilderInterface;

    /**
     * Provide the receiver address.
     *
     * @param string $country
     * @param string $postalCode
     * @param string $city
     * @param string $street
     * @param string $name
     * @param string|null $company
     * @param string|null $email
     * @param string|null $phone
     * @param string|null $mobile
     * @param string|null $contactPerson
     * @param string|null $state
     * @param string|null $comment
     * @return ShipmentRequestBuilderInterface
     */
    public function setRecipientAddress(
        string $country,
        string $postalCode,
        string $city,
        string $street,
        string $name,
        string $company = null,
        string $email = null,
        string $phone = null,
        string $mobile = null,
        string $contactPerson = null,
        string $state = null,
        string $comment = null
    ): ShipmentRequestBuilderInterface;

    /**
     * Set the receiver's parcel shop id.
     *
     * @param string $parcelShopId
     * @return ShipmentRequestBuilderInterface
     */
    public function setParcelShopId(string $parcelShopId): ShipmentRequestBuilderInterface;

    /**
     * Book the ShopReturnService by providing an address where the parcel should be returned to.
     *
     * This will yield an enclosed return shipment label as additional label page.
     *
     * @param string $country
     * @param string $postalCode
     * @param string $city
     * @param string $street
     * @param string $company
     * @param string|null $email
     * @param string|null $phone
     * @param string|null $mobile
     * @param string|null $state
     * @param string|null $companyContactPerson
     * @param string|null $companyDivision
     * @param string|null $companyUnit
     * @param string|null $comment
     * @return ShipmentRequestBuilderInterface
     */
    public function setReturnAddress(
        string $country,
        string $postalCode,
        string $city,
        string $street,
        string $company,
        string $email = null,
        string $phone = null,
        string $mobile = null,
        string $state = null,
        string $companyContactPerson = null,
        string $companyDivision = null,
        string $companyUnit = null,
        string $comment = null
    ): ShipmentRequestBuilderInterface;

    /**
     * Book Pick&ShipService by providing an address where the parcel should be picked up from.
     *
     * @param string $country
     * @param string $postalCode
     * @param string $city
     * @param string $street
     * @param string $company
     * @param string|null $email
     * @param string|null $phone
     * @param string|null $mobile
     * @param string|null $state
     * @param string|null $companyContactPerson
     * @param string|null $companyDivision
     * @param string|null $companyUnit
     * @param string|null $comment
     * @return ShipmentRequestBuilderInterface
     */
    public function setPickupAddress(
        string $country,
        string $postalCode,
        string $city,
        string $street,
        string $company,
        string $email = null,
        string $phone = null,
        string $mobile = null,
        string $state = null,
        string $companyContactPerson = null,
        string $companyDivision = null,
        string $companyUnit = null,
        string $comment = null
    ): ShipmentRequestBuilderInterface;

    /**
     * Book FlexDeliveryService.
     *
     * @return ShipmentRequestBuilderInterface
     */
    public function requestFlexDeliveryService(): ShipmentRequestBuilderInterface;

    /**
     * Set the International Commercial Terms (Incoterm) code.
     *
     * For valid values, refer to the GLS documentation.
     *
     * @param int $incoterm
     * @return ShipmentRequestBuilderInterface
     */
    public function setCustomsDetails(int $incoterm): ShipmentRequestBuilderInterface;

    /**
     * Add a parcel to the shipment.
     *
     * @param float $weightInKg
     * @param string|null $reference Parcel reference (optional)
     * @param string|null $returnReference Return reference for the parcel (optional)
     * @param float|null $codAmount Monetary value to be collected by the courier upon delivery (optional)
     * @param string|null $codReference Reference for cash on delivery payment (conditionally mandatory)
     * @param string|null $comment Comment (optional)
     * @return ShipmentRequestBuilderInterface
     */
    public function addParcel(
        float $weightInKg,
        string $reference = null,
        string $returnReference = null,
        float $codAmount = null,
        string $codReference = null,
        string $comment = null
    ): ShipmentRequestBuilderInterface;

    /**
     * Set label format. By default, a PDF label will be created.
     *
     * @param string $labelFormat
     * @return ShipmentRequestBuilderInterface
     */
    public function setLabelFormat(string $labelFormat = self::LABEL_FORMAT_PDF): ShipmentRequestBuilderInterface;

    /**
     * Change label size. By default, an A6 label will be created.
     *
     * @param string $labelSize
     * @return ShipmentRequestBuilderInterface
     */
    public function setLabelSize(string $labelSize = self::LABEL_SIZE_A6): ShipmentRequestBuilderInterface;

    /**
     * Create the shipment request and reset the builder data.
     *
     * @return \JsonSerializable
     * @throws RequestValidatorException
     */
    public function create();
}
