<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Api\Data;

/**
 * @api
 */
interface ShipmentInterface
{
    public function getLocation(): string;

    public function getConsignmentId(): string;

    public function getLabel(): string;

    /**
     * @return ParcelInterface[]
     */
    public function getParcels(): array;

    /**
     * @return ParcelInterface[]
     */
    public function getReturnParcels(): array;
}
