<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Model\Shipment\ResponseType;

class Parcel
{
    /**
     * @var string
     */
    private $location;

    /**
     * @var string
     */
    private $trackId;

    /**
     * @var string
     */
    private $parcelNumber;

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
    public function getTrackId(): string
    {
        return $this->trackId;
    }

    /**
     * @return string
     */
    public function getParcelNumber(): string
    {
        return $this->parcelNumber;
    }
}
