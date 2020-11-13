<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Api\Data;

/**
 * @api
 */
interface ParcelInterface
{
    public function getLocation(): string;

    public function getTrackId(): string;

    public function getParcelNumber(): string;
}
