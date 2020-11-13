<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\RequestBuilder;

use GlsGroup\Sdk\ParcelProcessing\Exception\RequestValidatorException;

class ShipmentRequestValidator
{
    public const MSG_ACCOUNT_DATA_REQUIRED = 'Shipper ID is required.';
    public const MSG_WEIGHT_REQUIRED = 'Parcel weight is required.';
    public const MSG_RECIPIENT_ADDRESS_REQUIRED = 'Delivery address is required.';
    public const MSG_RECIPIENT_COMMUNICATION_REQUIRED = 'Recipient email or mobile is required.';
    public const MSG_COD_REFERENCE_REQUIRED = 'COD reference is required in Germany.';
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

        if (empty($data['recipientAddress'])) {
            // required unless it is a return shipment (different builder / validator)
            throw new RequestValidatorException(self::MSG_RECIPIENT_ADDRESS_REQUIRED);
        }

        foreach ($data['parcels'] as $parcelData) {
            if (!empty($parcelData['codAmount'])) {
                if (($data['recipientAddress']['country'] === 'DE') && empty($parcelData['codReference'])) {
                    throw new RequestValidatorException(self::MSG_COD_REFERENCE_REQUIRED);
                }
            }
        }

        if (isset($data['labelSize'])) {
            $labelSizes = [
                ShipmentRequestBuilder::LABEL_SIZE_A6,
                ShipmentRequestBuilder::LABEL_SIZE_A5,
                ShipmentRequestBuilder::LABEL_SIZE_A4,
            ];

            if (!in_array($data['labelSize'], $labelSizes)) {
                throw new RequestValidatorException(self::MSG_INVALID_LABEL_SIZE);
            }
        }

        if (isset($data['labelFormat'])) {
            $labelFormats = [
                ShipmentRequestBuilder::LABEL_FORMAT_PDF,
                ShipmentRequestBuilder::LABEL_FORMAT_PNG,
            ];

            if (!in_array($data['labelFormat'], $labelFormats)) {
                throw new RequestValidatorException(self::MSG_INVALID_LABEL_FORMAT);
            }
        }

        if (isset($data['services'], $data['services']['flexdelivery'])) {
            if (empty($data['recipientAddress']['email']) && empty($data['recipientAddress']['mobile'])) {
                throw new RequestValidatorException(self::MSG_RECIPIENT_COMMUNICATION_REQUIRED);
            }
        }
    }
}
