<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Api;

use GlsGermany\Sdk\ParcelProcessing\Exception\AuthenticationException;
use GlsGermany\Sdk\ParcelProcessing\Exception\DetailedServiceException;
use GlsGermany\Sdk\ParcelProcessing\Exception\ServiceException;

/**
 * @api
 */
interface CancellationServiceInterface
{
    /**
     * Cancel one or multiple parcels identified by IDs.
     *
     * @param string[] $parcelIds
     * @return string[] Successfully cancelled parcels
     *
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    public function cancelParcels(array $parcelIds): array;
}
