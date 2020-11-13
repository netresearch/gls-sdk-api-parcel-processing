<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Api\Data;

/**
 * @api
 */
interface ShipmentInterface
{
    /**
     * @param string[] $labels
     */
    public function setLabels(array $labels): void;

    /**
     * @param string[] $qrCodes
     */
    public function setQrCodes(array $qrCodes): void;

    /**
     * @param ParcelInterface[] $returnParcels
     */
    public function setReturnParcels(array $returnParcels): void;

    /**
     * @return string
     */
    public function getLocation(): string;

    /**
     * @return string
     */
    public function getConsignmentId(): string;

    /**
     * @return string[]
     */
    public function getLabels(): array;

    /**
     * @return string[]
     */
    public function getQrCodes(): array;

    /**
     * @return ParcelInterface[]
     */
    public function getParcels(): array;

    /**
     * @return ParcelInterface[]
     */
    public function getReturnParcels(): array;
}
