<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\RequestBuilder;

use GlsGermany\Sdk\ParcelProcessing\Api\LabelRequestBuilderInterface;
use GlsGermany\Sdk\ParcelProcessing\Api\ShipmentRequestBuilderInterface;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\CreateShipmentRequestType;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\RequestType\Address;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\RequestType\Parcel;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\RequestType\ReturnParcel;
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
    ): LabelRequestBuilderInterface {
        $this->data['account']['shipperId'] = $shipperId;
        $this->data['account']['brokerReference'] = $brokerReference;

        return $this;
    }

    public function setReferenceNumbers(array $references): LabelRequestBuilderInterface
    {
        $this->data['shipmentDetails']['references'] = $references;

        return $this;
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

    public function setParcelShopId(string $parcelShopId): ShipmentRequestBuilderInterface
    {
        $this->data['services']['shopdelivery'] = $parcelShopId;

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
    ): ShipmentRequestBuilderInterface {
        $this->data['pickupAddress']['country'] = $country;
        $this->data['pickupAddress']['postalCode'] = $postalCode;
        $this->data['pickupAddress']['city'] = $city;
        $this->data['pickupAddress']['street'] = $street;
        $this->data['pickupAddress']['company'] = $company;
        $this->data['pickupAddress']['email'] = $email;
        $this->data['pickupAddress']['phone'] = $phone;
        $this->data['pickupAddress']['mobile'] = $mobile;
        $this->data['pickupAddress']['state'] = $state;
        $this->data['pickupAddress']['companyContactPerson'] = $companyContactPerson;
        $this->data['pickupAddress']['companyDivision'] = $companyDivision;
        $this->data['pickupAddress']['companyUnit'] = $companyUnit;
        $this->data['pickupAddress']['comment'] = $comment;

        return $this;
    }

    public function requestFlexDeliveryService(): ShipmentRequestBuilderInterface
    {
        $this->data['services']['flexdelivery'] = true;

        return $this;
    }

    public function requestNextDayDelivery(): ShipmentRequestBuilderInterface
    {
        $this->data['services']['guaranteed24'] = true;

        return $this;
    }

    public function setPlaceOfDeposit(string $placeOfDeposit): ShipmentRequestBuilderInterface
    {
        $this->data['services']['deposit'] = $placeOfDeposit;

        return $this;
    }

    public function setCustomsDetails(int $incoterm): LabelRequestBuilderInterface
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
        ShipmentRequestValidator::validate($this->data);


        $shipperAddress = $this->data['shipperAddress'] ?? [];
        $recipientAddress = $this->data['recipientAddress'] ?? [];
        $pickupAddress = $this->data['pickupAddress'] ?? [];
        $returnAddress = $this->data['returnAddress'] ?? [];

        $isReturnLabelRequested = !empty($returnAddress);

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

                $returnParcel = new ReturnParcel($parcelData['weight']);
                if (!empty($parcelData['returnReference'])) {
                    $returnParcel->setReferences([$parcelData['returnReference']]);
                }
                $returnParcels[] = $returnParcel;
            }

            if (isset($this->data['services'], $this->data['services']['flexdelivery'])) {
                $services[] = new Service('flexdeliveryservice');
            }

            if (isset($this->data['services'], $this->data['services']['guaranteed24'])) {
                $services[] = new Service('guaranteed24service');
            }

            if (isset($this->data['services'], $this->data['services']['deposit'])) {
                $depositService = new Service('depositservice');
                $depositServiceInfo = [new ServiceInfo('placeofdeposit', $this->data['services']['deposit'])];

                if (!empty($recipientAddress['contactPerson'])) {
                    $depositServiceInfo[] = new ServiceInfo('contact', $recipientAddress['contactPerson']);
                }
                if (!empty($recipientAddress['phone'])) {
                    $depositServiceInfo[] = new ServiceInfo('phone', $recipientAddress['phone']);
                }
                $depositService->setServiceInfo($depositServiceInfo);
                $services[] = $depositService;
            }

            if (isset($this->data['services'], $this->data['services']['shopdelivery'])) {
                $shopDeliveryService = new Service('shopdeliveryservice');
                $shopDeliveryService->setServiceInfo([
                    new ServiceInfo('parcelshopid', $this->data['services']['shopdelivery'])
                ]);
                $services[] = $shopDeliveryService;
            }

            $parcel->setServices($services);
            $parcels[] = $parcel;
        }

        $request = new CreateShipmentRequestType($this->data['account']['shipperId'], $parcels);
        $request->setReturnParcels($returnParcels);

        if (!empty($shipperAddress)) {
            $shipper = new Address(
                $shipperAddress['company'],
                $shipperAddress['street'],
                $shipperAddress['country'],
                $shipperAddress['postalCode'],
                $shipperAddress['city']
            );
            $shipper->setEmail($shipperAddress['email'] ?? '');
            $shipper->setPhone($shipperAddress['phone'] ?? '');
            $shipper->setMobile($shipperAddress['mobile'] ?? '');
            $shipper->setProvince($shipperAddress['state'] ?? '');
            $shipper->setContact($shipperAddress['companyContactPerson'] ?? '');
            $shipper->setName2($shipperAddress['companyDivision'] ?? '');
            $shipper->setName3($shipperAddress['companyUnit'] ?? '');
            $shipper->setComments($shipperAddress['comment'] ?? '');

            $request->setShipperAddress($shipper);
        }

        if (!empty($pickupAddress)) {
            $pickup = new Address(
                $pickupAddress['company'],
                $pickupAddress['street'],
                $pickupAddress['country'],
                $pickupAddress['postalCode'],
                $pickupAddress['city']
            );
            $pickup->setEmail($pickupAddress['email'] ?? '');
            $pickup->setPhone($pickupAddress['phone'] ?? '');
            $pickup->setMobile($pickupAddress['mobile'] ?? '');
            $pickup->setProvince($pickupAddress['state'] ?? '');
            $pickup->setContact($pickupAddress['companyContactPerson'] ?? '');
            $pickup->setName2($pickupAddress['companyDivision'] ?? '');
            $pickup->setName3($pickupAddress['companyUnit'] ?? '');
            $pickup->setComments($pickupAddress['comment'] ?? '');

            $request->setPickupAddress($pickup);
        }

        if (!empty($recipientAddress)) {
            $recipient = new Address(
                $recipientAddress['name'],
                $recipientAddress['street'],
                $recipientAddress['country'],
                $recipientAddress['postalCode'],
                $recipientAddress['city']
            );
            $recipient->setEmail($recipientAddress['email'] ?? '');
            $recipient->setPhone($recipientAddress['phone'] ?? '');
            $recipient->setMobile($recipientAddress['mobile'] ?? '');
            $recipient->setProvince($recipientAddress['state'] ?? '');
            $recipient->setContact($recipientAddress['contactPerson'] ?? '');
            $recipient->setName2($recipientAddress['company'] ?? '');
            $recipient->setComments($recipientAddress['comment'] ?? '');

            $request->setDeliveryAddress($recipient);
        }

        if ($isReturnLabelRequested) {
            $return = new Address(
                $returnAddress['company'],
                $returnAddress['street'],
                $returnAddress['country'],
                $returnAddress['postalCode'],
                $returnAddress['city']
            );
            $return->setEmail($returnAddress['email'] ?? '');
            $return->setPhone($returnAddress['phone'] ?? '');
            $return->setMobile($returnAddress['mobile'] ?? '');
            $return->setProvince($returnAddress['state'] ?? '');
            $return->setContact($returnAddress['companyContactPerson'] ?? '');
            $return->setName2($returnAddress['companyDivision'] ?? '');
            $return->setName3($returnAddress['companyUnit'] ?? '');
            $return->setComments($returnAddress['comment'] ?? '');

            $request->setReturnAddress($return);
        }

        if (isset($this->data['shipmentDetails'], $this->data['shipmentDetails']['date'])) {
            $request->setShipmentDate($this->data['shipmentDetails']['date']);
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
