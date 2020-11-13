<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Model\Shipment\RequestType;

class Address implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name1;

    /**
     * @var string
     */
    private $name2;

    /**
     * @var string
     */
    private $name3;

    /**
     * @var string
     */
    private $street1;

    /**
     * @var string
     */
    private $blockNo1;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $province;

    /**
     * @var string
     */
    private $contact;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $mobile;
    /**
     * @var string
     */
    private $comments;

    public function __construct(string $name1, string $street1, string $country, string $zipCode, string $city)
    {
        $this->name1 = $name1;
        $this->street1 = $street1;
        $this->country = $country;
        $this->zipCode = $zipCode;
        $this->city = $city;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setName2(string $name2): void
    {
        $this->name2 = $name2;
    }

    public function setName3(string $name3): void
    {
        $this->name3 = $name3;
    }

    public function setBlockNo1(string $blockNo1): void
    {
        $this->blockNo1 = $blockNo1;
    }

    public function setProvince(string $province): void
    {
        $this->province = $province;
    }

    public function setContact(string $contact): void
    {
        $this->contact = $contact;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function setMobile(string $mobile): void
    {
        $this->mobile = $mobile;
    }

    public function setComments(string $comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
