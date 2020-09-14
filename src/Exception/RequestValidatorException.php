<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Exception;

/**
 * Class RequestValidatorException
 *
 * A special instance of the DetailedServiceException which is
 * caused by invalid request data before a web service request was sent.
 *
 * @api
 */
class RequestValidatorException extends DetailedServiceException
{
}
