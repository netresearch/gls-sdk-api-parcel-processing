<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType;

class Parcel implements \JsonSerializable
{
    /**
     * @var float
     */
    private $weight;

    /**
     * @var string[]
     */
    private $references;

    /**
     * @var string
     */
    private $comment;

    /**
     * @var Service[]
     */
    private $services;

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

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @param Service[] $services
     */
    public function setServices(array $services): void
    {
        $this->services = $services;
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
