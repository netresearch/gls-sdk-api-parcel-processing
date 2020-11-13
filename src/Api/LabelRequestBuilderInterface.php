<?php

namespace GlsGroup\Sdk\ParcelProcessing\Api;

interface LabelRequestBuilderInterface
{
    public const LABEL_FORMAT_PDF = 'PDF';
    public const LABEL_FORMAT_PNG = 'PNG';
    public const LABEL_SIZE_A6 = 'A6';
    public const LABEL_SIZE_A5 = 'A5';
    public const LABEL_SIZE_A4 = 'A4';

    /**
     * @param string $shipperId Customer ID / Contact ID as provided by depot or given in the GLS account settings
     * @param string|null $brokerReference Reference to the GLS Partner (optional)
     * @return LabelRequestBuilderInterface
     */
    public function setShipperAccount(string $shipperId, string $brokerReference = null): LabelRequestBuilderInterface;

    /**
     * @param string[] $references The customer given reference numbers for all parcels created (optional)
     * @return LabelRequestBuilderInterface
     */
    public function setReferenceNumbers(array $references): LabelRequestBuilderInterface;

    /**
     * Set the International Commercial Terms (Incoterm) code.
     *
     * For valid values, refer to the GLS documentation.
     *
     * @param int $incoterm
     * @return LabelRequestBuilderInterface
     */
    public function setCustomsDetails(int $incoterm): LabelRequestBuilderInterface;

    /**
     * Set label format. By default, a PDF label will be created.
     *
     * @param string $labelFormat
     * @return LabelRequestBuilderInterface
     */
    public function setLabelFormat(string $labelFormat = self::LABEL_FORMAT_PDF): LabelRequestBuilderInterface;

    /**
     * Change label size. By default, an A6 label will be created.
     *
     * @param string $labelSize
     * @return LabelRequestBuilderInterface
     */
    public function setLabelSize(string $labelSize = self::LABEL_SIZE_A6): LabelRequestBuilderInterface;

    public function create(): \JsonSerializable;
}
