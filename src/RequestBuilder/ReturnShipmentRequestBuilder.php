<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\RequestBuilder;

use GlsGroup\Sdk\ParcelProcessing\Api\LabelRequestBuilderInterface;
use GlsGroup\Sdk\ParcelProcessing\Api\ReturnShipmentRequestBuilderInterface;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentRequestType;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType\Address;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType\Parcel;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType\Service;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType\ServiceInfo;

class ReturnShipmentRequestBuilder implements ReturnShipmentRequestBuilderInterface
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
    ): LabelRequestBuilderInterface {
        $this->data['account']['shipperId'] = $shipperId;
        $this->data['account']['brokerReference'] = $brokerReference ?? '';

        return $this;
    }

    public function setReferenceNumbers(array $references): LabelRequestBuilderInterface
    {
        $this->data['shipmentDetails']['references'] = $references;

        return $this;
    }

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
    ): ReturnShipmentRequestBuilderInterface {
        $this->data['shipperAddress']['country'] = $country;
        $this->data['shipperAddress']['postalCode'] = $postalCode;
        $this->data['shipperAddress']['city'] = $city;
        $this->data['shipperAddress']['street'] = $street;
        $this->data['shipperAddress']['name'] = $name;
        $this->data['shipperAddress']['company'] = $company;
        $this->data['shipperAddress']['email'] = $email;
        $this->data['shipperAddress']['phone'] = $phone;
        $this->data['shipperAddress']['mobile'] = $mobile;
        $this->data['shipperAddress']['contactPerson'] = $contactPerson;
        $this->data['shipperAddress']['state'] = $state;
        $this->data['shipperAddress']['comment'] = $comment;

        return $this;
    }

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
    ): ReturnShipmentRequestBuilderInterface {
        $this->data['pickupAddress']['country'] = $country;
        $this->data['pickupAddress']['postalCode'] = $postalCode;
        $this->data['pickupAddress']['city'] = $city;
        $this->data['pickupAddress']['street'] = $street;
        $this->data['pickupAddress']['name'] = $name;
        $this->data['pickupAddress']['company'] = $company;
        $this->data['pickupAddress']['email'] = $email;
        $this->data['pickupAddress']['phone'] = $phone;
        $this->data['pickupAddress']['mobile'] = $mobile;
        $this->data['pickupAddress']['contactPerson'] = $contactPerson;
        $this->data['pickupAddress']['state'] = $state;
        $this->data['pickupAddress']['comment'] = $comment;

        return $this;
    }

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
    ): ReturnShipmentRequestBuilderInterface {
        $this->data['recipientAddress']['country'] = $country;
        $this->data['recipientAddress']['postalCode'] = $postalCode;
        $this->data['recipientAddress']['city'] = $city;
        $this->data['recipientAddress']['street'] = $street;
        $this->data['recipientAddress']['company'] = $company;
        $this->data['recipientAddress']['email'] = $email;
        $this->data['recipientAddress']['phone'] = $phone;
        $this->data['recipientAddress']['mobile'] = $mobile;
        $this->data['recipientAddress']['state'] = $state;
        $this->data['recipientAddress']['companyContactPerson'] = $companyContactPerson;
        $this->data['recipientAddress']['companyDivision'] = $companyDivision;
        $this->data['recipientAddress']['companyUnit'] = $companyUnit;
        $this->data['recipientAddress']['comment'] = $comment;

        return $this;
    }

    public function setCustomsDetails(int $incoterm): LabelRequestBuilderInterface
    {
        $this->data['customsDetails']['incoterm'] = $incoterm;

        return $this;
    }

    public function addParcel(
        float $weightInKg,
        bool $qrCode = false,
        string $reference = null,
        string $comment = null
    ): ReturnShipmentRequestBuilderInterface {
        $this->data['parcels'][] = [
            'weight' => $weightInKg,
            'qrCode' => $qrCode,
            'reference' => $reference,
            'comment' => $comment,
        ];

        return $this;
    }

    public function setLabelFormat(string $labelFormat = self::LABEL_FORMAT_PDF): LabelRequestBuilderInterface
    {
        $this->data['labelFormat'] = $labelFormat;

        return $this;
    }

    public function setLabelSize(string $labelSize = self::LABEL_SIZE_A6): LabelRequestBuilderInterface
    {
        $this->data['labelSize'] = $labelSize;

        return $this;
    }

    public function create(): \JsonSerializable
    {
        ReturnShipmentRequestValidator::validate($this->data);

        $isShopReturn = isset($this->data['shipperAddress']);

        $parcels = [];
        foreach ($this->data['parcels'] as $parcelData) {
            $parcel = new Parcel($parcelData['weight']);

            if (!empty($parcelData['reference'])) {
                $parcel->setReferences([$parcelData['reference']]);
            }

            if (!empty($parcelData['comment'])) {
                $parcel->setComment($parcelData['comment']);
            }

            if ($isShopReturn) {
                $shopReturnService = new Service('shopreturnservice');
                $shopReturnInfo = [];
                $shopReturnInfo[] = new ServiceInfo('returnonly', 'Y');
                if ($parcelData['qrCode']) {
                    $shopReturnInfo[] = new ServiceInfo('qrcode', 'QRCODE4');
                }
                $shopReturnService->setServiceInfo($shopReturnInfo);
                $parcel->setServices([$shopReturnService]);
            }

            $parcels[] = $parcel;
        }

        $request = new CreateShipmentRequestType($this->data['account']['shipperId'], $parcels);
        $request->setBrokerReference($this->data['account']['brokerReference']);

        if ($isShopReturn) {
            // shop return: package brought to parcel shop
            $shipper = new Address(
                $this->data['shipperAddress']['name'],
                $this->data['shipperAddress']['street'],
                $this->data['shipperAddress']['country'],
                $this->data['shipperAddress']['postalCode'],
                $this->data['shipperAddress']['city']
            );
            $shipper->setEmail($this->data['shipperAddress']['email'] ?? '');
            $shipper->setPhone($this->data['shipperAddress']['phone'] ?? '');
            $shipper->setMobile($this->data['shipperAddress']['mobile'] ?? '');
            $shipper->setProvince($this->data['shipperAddress']['state'] ?? '');
            $shipper->setContact($this->data['shipperAddress']['contactPerson'] ?? '');
            $shipper->setName2($this->data['shipperAddress']['company'] ?? '');
            $shipper->setComments($this->data['shipperAddress']['comment'] ?? '');

            $request->setReturnAddress($shipper);
        }

        if (!empty($this->data['pickupAddress'])) {
            // pick and return: parcel picked up by courier
            $pickup = new Address(
                $this->data['pickupAddress']['name'],
                $this->data['pickupAddress']['street'],
                $this->data['pickupAddress']['country'],
                $this->data['pickupAddress']['postalCode'],
                $this->data['pickupAddress']['city']
            );
            $pickup->setEmail($this->data['pickupAddress']['email'] ?? '');
            $pickup->setPhone($this->data['pickupAddress']['phone'] ?? '');
            $pickup->setMobile($this->data['pickupAddress']['mobile'] ?? '');
            $pickup->setProvince($this->data['pickupAddress']['state'] ?? '');
            $pickup->setContact($this->data['pickupAddress']['contactPerson'] ?? '');
            $pickup->setName2($this->data['pickupAddress']['company'] ?? '');
            $pickup->setComments($this->data['pickupAddress']['comment'] ?? '');

            $request->setPickupAddress($pickup);
        }

        if (!empty($this->data['recipientAddress'])) {
            // docs are not clear about behaviour when recipient address is
            // - omitted for shop return
            // - set for pick and return
            $recipient = new Address(
                $this->data['recipientAddress']['company'],
                $this->data['recipientAddress']['street'],
                $this->data['recipientAddress']['country'],
                $this->data['recipientAddress']['postalCode'],
                $this->data['recipientAddress']['city']
            );
            $recipient->setEmail($this->data['recipientAddress']['email'] ?? '');
            $recipient->setPhone($this->data['recipientAddress']['phone'] ?? '');
            $recipient->setMobile($this->data['recipientAddress']['mobile'] ?? '');
            $recipient->setProvince($this->data['recipientAddress']['state'] ?? '');
            $recipient->setContact($this->data['recipientAddress']['companyContactPerson'] ?? '');
            $recipient->setName2($this->data['recipientAddress']['companyDivision'] ?? '');
            $recipient->setName3($this->data['recipientAddress']['companyUnit'] ?? '');
            $recipient->setComments($this->data['recipientAddress']['comment'] ?? '');

            $request->setDeliveryAddress($recipient);
        }

        if (isset($this->data['shipmentDetails'], $this->data['shipmentDetails']['references'])) {
            $request->setReferences($this->data['shipmentDetails']['references']);
        }

        if (isset($this->data['customsDetails'], $this->data['customsDetails']['incoterm'])) {
            $request->setIncoterm($this->data['customsDetails']['incoterm']);
        }

        if (!empty($this->data['labelSize'])) {
            $request->setLabelSize($this->data['labelSize']);
        }

        if (!empty($this->data['labelFormat'])) {
            $request->setLabelFormat($this->data['labelFormat']);
        }

        $this->data = [];

        return $request;
    }
}
