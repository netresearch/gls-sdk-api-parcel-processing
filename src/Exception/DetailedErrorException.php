<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Exception;

use Http\Client\Exception\HttpException;

/**
 * A detailed HTTP exception.
 */
class DetailedErrorException extends HttpException
{
}
