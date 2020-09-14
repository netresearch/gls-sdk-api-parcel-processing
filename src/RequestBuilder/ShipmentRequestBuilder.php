<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\RequestBuilder;

use GlsGermany\Sdk\ParcelProcessing\Api\ShipmentRequestBuilderInterface;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentRequestType;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\RequestType\Address;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\RequestType\Parcel;

class ShipmentRequestBuilder implements ShipmentRequestBuilderInterface
{
    /**
     * The collected data used to build the request
     *
     * @var mixed[]
     */
    private $data = [];

    public function setShipperAccount(
        string $shipperId,
        string $brokerReference = null
    ): ShipmentRequestBuilderInterface {
        $this->data['account']['shipperId'] = $shipperId;
        $this->data['account']['brokerReference'] = $brokerReference;

        return $this;
    }

    public function setReferenceNumbers(array $references): ShipmentRequestBuilderInterface
    {
        // TODO: Implement setReferenceNumbers() method.
    }

    public function setShipmentDate(\DateTime $shipmentDate): ShipmentRequestBuilderInterface
    {
        // TODO: Implement setShipmentDate() method.
    }

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
    ): ShipmentRequestBuilderInterface {
        $this->data['shipper']['country'] = $country;
        $this->data['shipper']['postalCode'] = $postalCode;
        $this->data['shipper']['city'] = $city;
        $this->data['shipper']['street'] = $street;
        $this->data['shipper']['company'] = $company;
        $this->data['shipper']['email'] = $email;
        $this->data['shipper']['phone'] = $phone;
        $this->data['shipper']['mobile'] = $mobile;
        $this->data['shipper']['state'] = $state;
        $this->data['shipper']['companyContactPerson'] = $companyContactPerson;
        $this->data['shipper']['companyDivision'] = $companyDivision;
        $this->data['shipper']['companyUnit'] = $companyUnit;
        $this->data['shipper']['comment'] = $comment;

        return $this;
    }

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
    ): ShipmentRequestBuilderInterface {
        $this->data['recipient']['country'] = $country;
        $this->data['recipient']['postalCode'] = $postalCode;
        $this->data['recipient']['city'] = $city;
        $this->data['recipient']['street'] = $street;
        $this->data['recipient']['name'] = $name;
        $this->data['recipient']['company'] = $company;
        $this->data['recipient']['email'] = $email;
        $this->data['recipient']['phone'] = $phone;
        $this->data['recipient']['mobile'] = $mobile;
        $this->data['recipient']['contactPerson'] = $contactPerson;
        $this->data['recipient']['state'] = $state;
        $this->data['recipient']['comment'] = $comment;

        return $this;
    }

    public function requestReturnLabel(
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
    ): ShipmentRequestBuilderInterface {
        // TODO: Implement requestReturnLabel() method.
    }

    public function requestPickup(
        bool $isReturn,
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
    ): ShipmentRequestBuilderInterface {
        // TODO: Implement requestPickup() method.
    }

    public function setCustomsDetails(int $incoterm): ShipmentRequestBuilderInterface
    {
        // TODO: Implement setCustomsDetails() method.
    }

    public function addParcel(
        float $weightInKg,
        string $reference = null,
        string $returnReference = null,
        string $comment = null
    ): ShipmentRequestBuilderInterface {
        $this->data['parcels'][] = [
            'weight' => $weightInKg,
            'reference' => $reference,
            'returnReference' => $returnReference,
            'comment' => $comment,
        ];

        return $this;
    }

    public function setLabelFormatPng(): ShipmentRequestBuilderInterface
    {
        // TODO: Implement setLabelFormatPng() method.
    }

    public function setLabelSizeA5(): ShipmentRequestBuilderInterface
    {
        // TODO: Implement setLabelSizeA5() method.
    }

    public function setLabelSizeA4(): ShipmentRequestBuilderInterface
    {
        // TODO: Implement setLabelSizeA4() method.
    }

    public function create()
    {
        ShipmentRequestValidator::validate($this->data);

        $parcels = [];
        $returnParcels = [];

        foreach ($this->data['parcels'] as $parcelData) {
            $parcel = new Parcel($parcelData['weight']);

            if (!empty($parcelData['comment'])) {
                $parcel->setComment($parcelData['comment']);
            }

            if (!empty($parcelData['reference'])) {
                $parcel->setReferences([$parcelData['reference']]);
            }

            //todo(nr): decide whether to add a return parcel or not
            //todo(nr): decide whether to attach service(s) or not

            $parcels[] = $parcel;
        }

        $request = new CreateShipmentRequestType($this->data['account']['shipperId'], $parcels);
        $request->setReturnParcels($returnParcels);

        if (!empty($this->data['shipper'])) {
            $shipper = new Address(
                $this->data['shipper']['company'],
                $this->data['shipper']['street'],
                $this->data['shipper']['country'],
                $this->data['shipper']['postalCode'],
                $this->data['shipper']['city']
            );
            $shipper->setEmail($this->data['shipper']['email'] ?? '');
            $shipper->setPhone($this->data['shipper']['phone'] ?? '');
            $shipper->setMobile($this->data['shipper']['mobile'] ?? '');
            $shipper->setProvince($this->data['shipper']['state'] ?? '');
            $shipper->setContact($this->data['shipper']['companyContactPerson'] ?? '');
            $shipper->setName2($this->data['shipper']['companyDivision'] ?? '');
            $shipper->setName3($this->data['shipper']['companyUnit'] ?? '');
            $shipper->setComments($this->data['shipper']['comment'] ?? '');

            $request->setShipperAddress($shipper);
        }

        if (!empty($this->data['recipient'])) {
            $recipient = new Address(
                $this->data['recipient']['name'],
                $this->data['recipient']['street'],
                $this->data['recipient']['country'],
                $this->data['recipient']['postalCode'],
                $this->data['recipient']['city']
            );
            $recipient->setEmail($this->data['recipient']['email'] ?? '');
            $recipient->setPhone($this->data['recipient']['phone'] ?? '');
            $recipient->setMobile($this->data['recipient']['mobile'] ?? '');
            $recipient->setProvince($this->data['recipient']['state'] ?? '');
            $recipient->setContact($this->data['recipient']['contactPerson'] ?? '');
            $recipient->setName2($this->data['recipient']['company'] ?? '');
            $recipient->setComments($this->data['recipient']['comment'] ?? '');

            $request->setDeliveryAddress($recipient);
        }

        $this->data = [];

        return $request;
    }
}
