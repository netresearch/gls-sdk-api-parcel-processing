<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType;

class Service implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ServiceInfo[]
     */
    private $infos;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param ServiceInfo[] $infos
     */
    public function setServiceInfo(array $infos): void
    {
        $this->infos = $infos;
    }

    /**
     * @return string[]|ServiceInfo[][]
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
