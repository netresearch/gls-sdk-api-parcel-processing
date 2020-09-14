<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Model\Shipment;

/**
 * Shipment Response
 *
 * Do not change the type annotations as this class is passed through the
 * JsonMapper which requires the full namespace annotation in order to map
 * the JSON response correctly.
 */
class CreateShipmentResponseType
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
     * @var string[]
     */
    private $labels;

    /**
     * @var \GlsGermany\Sdk\ParcelProcessing\Model\Shipment\ResponseType\Parcel[]
     */
    private $parcels;

    /**
     * @var \GlsGermany\Sdk\ParcelProcessing\Model\Shipment\ResponseType\Parcel[]
     */
    private $returns;

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
     * @return string[]
     */
    public function getLabels(): array
    {
        return $this->labels ?? [];
    }

    /**
     * @return \GlsGermany\Sdk\ParcelProcessing\Model\Shipment\ResponseType\Parcel[]
     */
    public function getParcels(): array
    {
        return $this->parcels;
    }

    /**
     * @return \GlsGermany\Sdk\ParcelProcessing\Model\Shipment\ResponseType\Parcel[]
     */
    public function getReturns(): array
    {
        return $this->returns ?? [];
    }
}
