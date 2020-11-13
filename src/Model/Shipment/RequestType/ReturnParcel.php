<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType;

class ReturnParcel implements \JsonSerializable
{
    /**
     * @var float
     */
    private $weight;

    /**
     * @var string[]
     */
    private $references;

    public function __construct(float $weight)
    {
        $this->weight = $weight;
    }

    /**
     * @param string[] $references
     */
    public function setReferences(array $references): void
    {
        $this->references = $references;
    }

    /**
     * @return float[]|string[][]
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
