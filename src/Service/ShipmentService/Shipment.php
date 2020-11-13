<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Service\ShipmentService;

use GlsGroup\Sdk\ParcelProcessing\Api\Data\ParcelInterface;
use GlsGroup\Sdk\ParcelProcessing\Api\Data\ShipmentInterface;

class Shipment implements ShipmentInterface
{
    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $consignmentId;

    /**
     * @var ParcelInterface[]
     */
    private $parcels;

    /**
     * @var ParcelInterface[]
     */
    private $returnParcels;

    /**
     * @var string[]
     */
    private $labels;

    /**
     * @var string[]
     */
    private $qrCodes;

    /**
     * Shipment constructor.
     * @param string $location
     * @param string $consignmentId
     * @param ParcelInterface[] $parcels
     */
    public function __construct(
        string $location,
        string $consignmentId,
        array $parcels
    ) {
        $this->location = $location;
        $this->consignmentId = $consignmentId;
        $this->parcels = $parcels;

        $this->returnParcels = [];
        $this->labels = [];
        $this->qrCodes = [];
    }

    public function setLabels(array $labels): void
    {
        $this->labels = $labels;
    }

    public function setQrCodes(array $qrCodes): void
    {
        $this->qrCodes = $qrCodes;
    }

    public function setReturnParcels(array $returnParcels): void
    {
        $this->returnParcels = $returnParcels;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getConsignmentId(): string
    {
        return $this->consignmentId;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }

    public function getQrCodes(): array
    {
        return $this->qrCodes;
    }

    public function getParcels(): array
    {
        return $this->parcels;
    }

    public function getReturnParcels(): array
    {
        return $this->returnParcels;
    }
}
