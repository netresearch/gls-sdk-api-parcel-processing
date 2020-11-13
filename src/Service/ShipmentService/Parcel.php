<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Service\ShipmentService;

use GlsGroup\Sdk\ParcelProcessing\Api\Data\ParcelInterface;

class Parcel implements ParcelInterface
{
    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $parcelNumber;

    /**
     * @var string
     */
    private $trackId;

    public function __construct(string $location, string $parcelNumber, string $trackId)
    {
        $this->location = $location;
        $this->parcelNumber = $parcelNumber;
        $this->trackId = $trackId;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getParcelNumber(): string
    {
        return $this->parcelNumber;
    }

    public function getTrackId(): string
    {
        return $this->trackId;
    }
}
