<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Exception;

/**
 * Class AuthenticationException
 *
 * A special instance of the DetailedServiceException, thrown on 401 errors.
 *
 * @api
 */
class AuthenticationException extends DetailedServiceException
{
}
