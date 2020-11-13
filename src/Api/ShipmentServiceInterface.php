<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Api;

use GlsGroup\Sdk\ParcelProcessing\Api\Data\ShipmentInterface;
use GlsGroup\Sdk\ParcelProcessing\Exception\AuthenticationException;
use GlsGroup\Sdk\ParcelProcessing\Exception\DetailedServiceException;
use GlsGroup\Sdk\ParcelProcessing\Exception\ServiceException;

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
