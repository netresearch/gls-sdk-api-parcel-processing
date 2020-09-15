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
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\RequestType\Service;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\RequestType\ServiceInfo;

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
        $timezone = new \DateTimeZone('Europe/Berlin');
        $convertDate = $shipmentDate->setTimezone($timezone)->format('Y-m-d');

        $this->data['shipmentDetails']['date'] = $convertDate;

        return $this;
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
        $this->data['shipperAddress']['country'] = $country;
        $this->data['shipperAddress']['postalCode'] = $postalCode;
        $this->data['shipperAddress']['city'] = $city;
        $this->data['shipperAddress']['street'] = $street;
        $this->data['shipperAddress']['company'] = $company;
        $this->data['shipperAddress']['email'] = $email;
        $this->data['shipperAddress']['phone'] = $phone;
        $this->data['shipperAddress']['mobile'] = $mobile;
        $this->data['shipperAddress']['state'] = $state;
        $this->data['shipperAddress']['companyContactPerson'] = $companyContactPerson;
        $this->data['shipperAddress']['companyDivision'] = $companyDivision;
        $this->data['shipperAddress']['companyUnit'] = $companyUnit;
        $this->data['shipperAddress']['comment'] = $comment;

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
        $this->data['recipientAddress']['country'] = $country;
        $this->data['recipientAddress']['postalCode'] = $postalCode;
        $this->data['recipientAddress']['city'] = $city;
        $this->data['recipientAddress']['street'] = $street;
        $this->data['recipientAddress']['name'] = $name;
        $this->data['recipientAddress']['company'] = $company;
        $this->data['recipientAddress']['email'] = $email;
        $this->data['recipientAddress']['phone'] = $phone;
        $this->data['recipientAddress']['mobile'] = $mobile;
        $this->data['recipientAddress']['contactPerson'] = $contactPerson;
        $this->data['recipientAddress']['state'] = $state;
        $this->data['recipientAddress']['comment'] = $comment;

        return $this;
    }

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
    ): ShipmentRequestBuilderInterface {
        $this->data['returnAddress']['country'] = $country;
        $this->data['returnAddress']['postalCode'] = $postalCode;
        $this->data['returnAddress']['city'] = $city;
        $this->data['returnAddress']['street'] = $street;
        $this->data['returnAddress']['company'] = $company;
        $this->data['returnAddress']['email'] = $email;
        $this->data['returnAddress']['phone'] = $phone;
        $this->data['returnAddress']['mobile'] = $mobile;
        $this->data['returnAddress']['state'] = $state;
        $this->data['returnAddress']['companyContactPerson'] = $companyContactPerson;
        $this->data['returnAddress']['companyDivision'] = $companyDivision;
        $this->data['returnAddress']['companyUnit'] = $companyUnit;
        $this->data['returnAddress']['comment'] = $comment;

        return $this;
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

    public function requestFlexDeliveryService(): ShipmentRequestBuilderInterface
    {
        $this->data['services']['flexdelivery'] = true;

        return $this;
    }

    public function setCustomsDetails(int $incoterm): ShipmentRequestBuilderInterface
    {
        $this->data['customsDetails']['incoterm'] = $incoterm;

        return $this;
    }

    public function addParcel(
        float $weightInKg,
        string $reference = null,
        string $returnReference = null,
        float $codAmount = null,
        string $codReference = null,
        string $comment = null
    ): ShipmentRequestBuilderInterface {
        $this->data['parcels'][] = [
            'weight' => $weightInKg,
            'reference' => $reference,
            'returnReference' => $returnReference,
            'codAmount' => $codAmount,
            'codReference' => $codReference,
            'comment' => $comment,
        ];

        return $this;
    }

    public function setLabelFormat(string $labelFormat = self::LABEL_FORMAT_PDF): ShipmentRequestBuilderInterface
    {
        $this->data['labelFormat'] = $labelFormat;

        return $this;
    }

    public function setLabelSize(string $labelSize = self::LABEL_SIZE_A6): ShipmentRequestBuilderInterface
    {
        $this->data['labelSize'] = $labelSize;

        return $this;
    }

    public function create()
    {
        ShipmentRequestValidator::validate($this->data);

        $isReturnLabelRequested = isset($this->data['returnAddress']);

        $parcels = [];
        $returnParcels = [];

        foreach ($this->data['parcels'] as $parcelData) {
            $services = [];

            $parcel = new Parcel($parcelData['weight']);

            if (!empty($parcelData['comment'])) {
                $parcel->setComment($parcelData['comment']);
            }

            if (!empty($parcelData['reference'])) {
                $parcel->setReferences([$parcelData['reference']]);
            }

            if (!empty($parcelData['codAmount'])) {
                $codInfo = [
                    new ServiceInfo('amount', (string) $parcelData['codAmount'])
                ];
                if (!empty($parcelData['codReference'])) {
                    $codInfo[] = new ServiceInfo('reference', $parcelData['codReference'] ?? '');
                }
                $codService = new Service('cashondelivery');
                $codService->setServiceInfo($codInfo);

                $services[] = $codService;
            }

            if ($isReturnLabelRequested) {
                $services[] = new Service('shopreturnservice');

                $returnParcel = new Parcel($parcelData['weight']);
                if (!empty($parcelData['returnReference'])) {
                    $returnParcel->setReferences([$parcelData['returnReference']]);
                }
                $returnParcels[] = $returnParcel;
            }

            if (isset($this->data['services'], $this->data['services']['flexdelivery'])) {
                $services[] = new Service('flexdeliveryservice');
            }

            $parcel->setServices($services);
            $parcels[] = $parcel;
        }

        $request = new CreateShipmentRequestType($this->data['account']['shipperId'], $parcels);
        $request->setReturnParcels($returnParcels);

        if (!empty($this->data['shipperAddress'])) {
            $shipper = new Address(
                $this->data['shipperAddress']['company'],
                $this->data['shipperAddress']['street'],
                $this->data['shipperAddress']['country'],
                $this->data['shipperAddress']['postalCode'],
                $this->data['shipperAddress']['city']
            );
            $shipper->setEmail($this->data['shipperAddress']['email'] ?? '');
            $shipper->setPhone($this->data['shipperAddress']['phone'] ?? '');
            $shipper->setMobile($this->data['shipperAddress']['mobile'] ?? '');
            $shipper->setProvince($this->data['shipperAddress']['state'] ?? '');
            $shipper->setContact($this->data['shipperAddress']['companyContactPerson'] ?? '');
            $shipper->setName2($this->data['shipperAddress']['companyDivision'] ?? '');
            $shipper->setName3($this->data['shipperAddress']['companyUnit'] ?? '');
            $shipper->setComments($this->data['shipperAddress']['comment'] ?? '');

            $request->setShipperAddress($shipper);
        }

        if (!empty($this->data['recipientAddress'])) {
            $recipient = new Address(
                $this->data['recipientAddress']['name'],
                $this->data['recipientAddress']['street'],
                $this->data['recipientAddress']['country'],
                $this->data['recipientAddress']['postalCode'],
                $this->data['recipientAddress']['city']
            );
            $recipient->setEmail($this->data['recipientAddress']['email'] ?? '');
            $recipient->setPhone($this->data['recipientAddress']['phone'] ?? '');
            $recipient->setMobile($this->data['recipientAddress']['mobile'] ?? '');
            $recipient->setProvince($this->data['recipientAddress']['state'] ?? '');
            $recipient->setContact($this->data['recipientAddress']['contactPerson'] ?? '');
            $recipient->setName2($this->data['recipientAddress']['company'] ?? '');
            $recipient->setComments($this->data['recipientAddress']['comment'] ?? '');

            $request->setDeliveryAddress($recipient);
        }

        if ($isReturnLabelRequested) {
            $return = new Address(
                $this->data['returnAddress']['company'],
                $this->data['returnAddress']['street'],
                $this->data['returnAddress']['country'],
                $this->data['returnAddress']['postalCode'],
                $this->data['returnAddress']['city']
            );
            $return->setEmail($this->data['returnAddress']['email'] ?? '');
            $return->setPhone($this->data['returnAddress']['phone'] ?? '');
            $return->setMobile($this->data['returnAddress']['mobile'] ?? '');
            $return->setProvince($this->data['returnAddress']['state'] ?? '');
            $return->setContact($this->data['returnAddress']['companyContactPerson'] ?? '');
            $return->setName2($this->data['returnAddress']['companyDivision'] ?? '');
            $return->setName3($this->data['returnAddress']['companyUnit'] ?? '');
            $return->setComments($this->data['returnAddress']['comment'] ?? '');

            $request->setReturnAddress($return);
        }

        if (isset($this->data['shipmentDetails'], $this->data['shipmentDetails']['date'])) {
            $request->setShipmentDate($this->data['shipmentDetails']['date']);
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
