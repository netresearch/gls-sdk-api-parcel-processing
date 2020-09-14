<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Api;

use GlsGermany\Sdk\ParcelProcessing\Api\Data\ShipmentInterface;
use GlsGermany\Sdk\ParcelProcessing\Exception\AuthenticationException;
use GlsGermany\Sdk\ParcelProcessing\Exception\DetailedServiceException;
use GlsGermany\Sdk\ParcelProcessing\Exception\ServiceException;

/**
 * @api
 */
interface ShipmentServiceInterface
{
    /**
     * @param \JsonSerializable $shipmentRequest
     * @return ShipmentInterface
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    public function createShipment(\JsonSerializable $shipmentRequest): ShipmentInterface;
}
