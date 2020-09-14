<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Service\ShipmentService;

use GlsGermany\Sdk\ParcelProcessing\Api\Data\ParcelInterface;
use GlsGermany\Sdk\ParcelProcessing\Api\Data\ShipmentInterface;

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
     * @var string|null
     */
    private $label;

    /**
     * @var ParcelInterface[]
     */
    private $returnParcels;

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
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @param ParcelInterface[] $returnParcels
     */
    public function setReturnParcels(array $returnParcels): void
    {
        $this->returnParcels = $returnParcels;
    }

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getConsignmentId(): string
    {
        return $this->consignmentId;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return (string) $this->label;
    }

    /**
     * @return ParcelInterface[]
     */
    public function getParcels(): array
    {
        return $this->parcels;
    }

    /**
     * @return ParcelInterface[]
     */
    public function getReturnParcels(): array
    {
        return $this->returnParcels;
    }
}
