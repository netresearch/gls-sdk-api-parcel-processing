<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\RequestBuilder;

use GlsGroup\Sdk\ParcelProcessing\Exception\RequestValidatorException;

class ReturnShipmentRequestValidator
{
    public const MSG_ACCOUNT_DATA_REQUIRED = 'Shipper ID is required.';
    public const MSG_WEIGHT_REQUIRED = 'Parcel weight is required.';
    public const MSG_SENDER_ADDRESS_REQUIRED = 'Either pickup or sender address is required.';
    public const MSG_SENDER_ADDRESS_AMBIGUOUS = 'Either pickup or sender address must be given, not both.';
    public const MSG_RECIPIENT_ADDRESS_REQUIRED = 'Delivery address is required.';
    public const MSG_RECIPIENT_COMMUNICATION_REQUIRED = 'Recipient email or mobile is required.';
    public const MSG_INVALID_LABEL_SIZE = 'The given label size is not valid.';
    public const MSG_INVALID_LABEL_FORMAT = 'The given label format is not valid.';

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

        if (empty($data['pickupAddress']) && empty($data['shipperAddress'])) {
            throw new RequestValidatorException(self::MSG_SENDER_ADDRESS_REQUIRED);
        }

        if (!empty($data['pickupAddress']) && !empty($data['shipperAddress'])) {
            throw new RequestValidatorException(self::MSG_SENDER_ADDRESS_AMBIGUOUS);
        }

        if (!empty($data['shipperAddress']) && empty($data['recipientAddress'])) {
            // shop return always requires delivery address
            throw new RequestValidatorException(self::MSG_RECIPIENT_ADDRESS_REQUIRED);
        }

        if (isset($data['labelSize'])) {
            $labelSizes = [
                ReturnShipmentRequestBuilder::LABEL_SIZE_A6,
                ReturnShipmentRequestBuilder::LABEL_SIZE_A5,
                ReturnShipmentRequestBuilder::LABEL_SIZE_A4,
            ];

            if (!in_array($data['labelSize'], $labelSizes)) {
                throw new RequestValidatorException(self::MSG_INVALID_LABEL_SIZE);
            }
        }

        if (isset($data['labelFormat'])) {
            $labelFormats = [
                ReturnShipmentRequestBuilder::LABEL_FORMAT_PDF,
                ReturnShipmentRequestBuilder::LABEL_FORMAT_PNG,
            ];

            if (!in_array($data['labelFormat'], $labelFormats)) {
                throw new RequestValidatorException(self::MSG_INVALID_LABEL_FORMAT);
            }
        }
    }
}
