<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\RequestBuilder;

use GlsGermany\Sdk\ParcelProcessing\Exception\RequestValidatorException;

class ShipmentRequestValidator
{
    public const MSG_ACCOUNT_DATA_REQUIRED = 'Shipper ID is required.';
    public const MSG_WEIGHT_REQUIRED = 'Parcel weight is required.';

    /**
     * Validate request data before sending it to the web service.
     *
     * @param mixed[][] $data
     *
     * @throws RequestValidatorException
     */
    public static function validate(array $data): void
    {
        if (empty($data['account']['shipperId'])) {
            throw new RequestValidatorException(self::MSG_ACCOUNT_DATA_REQUIRED);
        }

        if (empty($data['parcels'])) {
            throw new RequestValidatorException(self::MSG_WEIGHT_REQUIRED);
        }

        // check if recipient address is set or Pick&ReturnService is requested
    }
}
