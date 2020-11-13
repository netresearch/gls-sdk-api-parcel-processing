<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Model\Shipment;

use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType\Address;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType\Parcel;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType\ReturnParcel;

class CreateShipmentRequestType implements \JsonSerializable
{
    /**
     * @var string
     */
    private $shipperId;

    /**
     * @var string
     */
    private $shipmentDate;

    /**
     * @var string[]
     */
    private $references;

    /**
     * @var string
     */
    private $brokerReference;

    /**
     * @var Address
     */
    private $deliveryAddress;

    /**
     * @var Address
     */
    private $shipperAddress;

    /**
     * @var Address
     */
    private $returnAddress;

    /**
     * @var Address
     */
    private $pickupAddress;

    /**
     * @var int
     */
    private $incoterm;

    /**
     * @var Parcel[]
     */
    private $parcels;

    /**
     * @var ReturnParcel[]
     */
    private $returnParcels;

    /**
     * @var string
     */
    private $labelFormat;

    /**
     * @var string
     */
    private $labelSize;

    /**
     * CreateShipmentRequestType constructor.
     *
     * @param string $shipperId
     * @param Parcel[] $parcels
     */
    public function __construct(string $shipperId, array $parcels)
    {
        $this->shipperId = $shipperId;
        $this->parcels = $parcels;
    }

    public function setShipmentDate(string $shipmentDate): void
    {
        $this->shipmentDate = $shipmentDate;
    }

    /**
     * @param string[] $references
     */
    public function setReferences(array $references): void
    {
        $this->references = $references;
    }

    public function setBrokerReference(string $brokerReference): void
    {
        $this->brokerReference = $brokerReference;
    }

    public function setDeliveryAddress(Address $deliveryAddress): void
    {
        $this->deliveryAddress = $deliveryAddress;
    }

    public function setShipperAddress(Address $alternativeShipperAddress): void
    {
        $this->shipperAddress = $alternativeShipperAddress;
    }

    public function setReturnAddress(Address $returnAddress): void
    {
        $this->returnAddress = $returnAddress;
    }

    public function setPickupAddress(Address $pickupAddress): void
    {
        $this->pickupAddress = $pickupAddress;
    }

    public function setIncoterm(int $incoterm): void
    {
        $this->incoterm = $incoterm;
    }

    /**
     * @param ReturnParcel[] $returnParcels
     */
    public function setReturnParcels(array $returnParcels): void
    {
        $this->returnParcels = $returnParcels;
    }

    public function setLabelFormat(string $labelFormat): void
    {
        $this->labelFormat = $labelFormat;
    }

    public function setLabelSize(string $labelSize): void
    {
        $this->labelSize = $labelSize;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return [
            'shipperId' => $this->shipperId,
            'shipmentDate' => $this->shipmentDate,
            'references' => $this->references,
            'brokerReference' => $this->brokerReference,
            'addresses' => [
                'delivery' => $this->deliveryAddress,
                'alternativeShipper' => $this->shipperAddress,
                'return' => $this->returnAddress,
                'pickup' => $this->pickupAddress,
            ],
            'incoterm' => $this->incoterm,
            'parcels' => $this->parcels,
            'returns' => $this->returnParcels,
            'labelFormat' => $this->labelFormat,
            'labelSize' => $this->labelSize,
        ];
    }
}
