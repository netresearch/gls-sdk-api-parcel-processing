<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Api;

use GlsGroup\Sdk\ParcelProcessing\Exception\RequestValidatorException;

/**
 * @api
 */
interface ShipmentRequestBuilderInterface extends LabelRequestBuilderInterface
{
    /**
     * @param \DateTimeInterface $shipmentDate Date of shipment (optional, defaults to current date)
     * @return ShipmentRequestBuilderInterface
     */
    public function setShipmentDate(\DateTimeInterface $shipmentDate): ShipmentRequestBuilderInterface;

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
     * Book Guaranteed24Service.
     *
     * @return ShipmentRequestBuilderInterface
     */
    public function requestNextDayDelivery(): ShipmentRequestBuilderInterface;

    /**
     * Book DepositService. To book LetterBox service, pass "letterbox" as argument.
     *
     * @param string $placeOfDeposit
     * @return ShipmentRequestBuilderInterface
     */
    public function setPlaceOfDeposit(string $placeOfDeposit): ShipmentRequestBuilderInterface;

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
     * Create the shipment request and reset the builder data.
     *
     * @return \JsonSerializable
     * @throws RequestValidatorException
     */
    public function create(): \JsonSerializable;
}
