<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Model\Cancellation\ResponseType;

class Status
{
    /**
     * @var string
     */
    private $parcelId;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $info;

    public function getParcelId(): string
    {
        return $this->parcelId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getInfo(): string
    {
        return $this->info;
    }
}
