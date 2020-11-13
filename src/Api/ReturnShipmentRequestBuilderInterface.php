<?php

namespace GlsGroup\Sdk\ParcelProcessing\Api;

use GlsGroup\Sdk\ParcelProcessing\Exception\RequestValidatorException;

interface ReturnShipmentRequestBuilderInterface extends LabelRequestBuilderInterface
{
    /**
     * Book ShopReturnService by providing details of the returning person.
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
     * @return ReturnShipmentRequestBuilderInterface
     */
    public function setShipperAddress(
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
    ): ReturnShipmentRequestBuilderInterface;

    /**
     * Book Pick&ReturnService by providing an address where the parcel should be picked up from.
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
     * @return ReturnShipmentRequestBuilderInterface
     */
    public function setPickupAddress(
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
    ): ReturnShipmentRequestBuilderInterface;

    /**
     * Provide receiver address of the return.
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
     * @return ReturnShipmentRequestBuilderInterface
     */
    public function setRecipientAddress(
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
    ): ReturnShipmentRequestBuilderInterface;

    /**
     * Add a return parcel to the shipment.
     *
     * @param float $weightInKg
     * @param bool $qrCode Request a QR code in addition to the label
     * @param string|null $reference Parcel reference (optional)
     * @param string|null $comment Comment (optional)
     * @return ReturnShipmentRequestBuilderInterface
     */
    public function addParcel(
        float $weightInKg,
        bool $qrCode = false,
        string $reference = null,
        string $comment = null
    ): ReturnShipmentRequestBuilderInterface;

    /**
     * Create the return shipment request and reset the builder data.
     *
     * @return \JsonSerializable
     * @throws RequestValidatorException
     */
    public function create(): \JsonSerializable;
}
