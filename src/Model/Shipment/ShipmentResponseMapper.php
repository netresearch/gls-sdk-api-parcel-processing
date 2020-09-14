<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Model\Shipment;

use GlsGermany\Sdk\ParcelProcessing\Api\Data\ShipmentInterface;
use GlsGermany\Sdk\ParcelProcessing\Model\Shipment\ResponseType\Parcel as ApiParcel;
use GlsGermany\Sdk\ParcelProcessing\Service\ShipmentService\Parcel;
use GlsGermany\Sdk\ParcelProcessing\Service\ShipmentService\Shipment;

class ShipmentResponseMapper
{
    public function map(CreateShipmentResponseType $apiShipment): ShipmentInterface
    {
        $parcels = array_map(
            function (ApiParcel $apiParcel) {
                return new Parcel($apiParcel->getLocation(), $apiParcel->getParcelNumber(), $apiParcel->getTrackId());
            },
            $apiShipment->getParcels()
        );

        $returnParcels = array_map(
            function (ApiParcel $apiParcel) {
                return new Parcel($apiParcel->getLocation(), $apiParcel->getParcelNumber(), $apiParcel->getTrackId());
            },
            $apiShipment->getReturns()
        );

        $shipment = new Shipment(
            $apiShipment->getLocation(),
            $apiShipment->getConsignmentId(),
            $parcels
        );

        $shipment->setReturnParcels($returnParcels);

        $b64Labels = $apiShipment->getLabels();
        if (!empty($b64Labels)) {
            $shipment->setLabel(base64_decode($b64Labels[0]));
        }

        return $shipment;
    }
}
