# GLS Parcel Processing API SDK

The GLS Parcel Processing API SDK package offers an interface to the following web services:

- GLS Web API for Parcel Processing
- GLS Web API for Parcel Cancellation

## Requirements

### System Requirements

- PHP 7.1+ with JSON extension

### Package Requirements

- `netresearch/jsonmapper`: Mapper for deserialization of JSON response messages into PHP objects
- `php-http/discovery`: Discovery service for HTTP client and message factory implementations
- `php-http/httplug`: Pluggable HTTP client abstraction
- `php-http/logger-plugin`: HTTP client logger plugin for HTTPlug
- `psr/http-client`: PSR-18 HTTP client interfaces
- `psr/http-factory`: PSR-7 HTTP message factory interfaces
- `psr/http-message`: PSR-7 HTTP message interfaces
- `psr/log`: PSR-3 logger interfaces

### Virtual Package Requirements

- `psr/http-client-implementation`: Any package that provides a PSR-18 compatible HTTP client
- `psr/http-factory-implementation`: Any package that provides PSR-7 compatible HTTP message factories
- `psr/http-message-implementation`: Any package that provides PSR-7 HTTP messages

### Development Package Requirements

- `nyholm/psr7`: PSR-7 HTTP message factory & message implementation
- `phpunit/phpunit`: Testing framework
- `php-http/mock-client`: HTTPlug mock client implementation
- `phpstan/phpstan`: Static analysis tool
- `squizlabs/php_codesniffer`: Static analysis tool

## Installation

```bash
$ composer require glsgroup/sdk-api-parcel-processing
```

## Uninstallation

```bash
$ composer remove glsgroup/sdk-api-parcel-processing
```

## Testing

```bash
$ ./vendor/bin/phpunit -c test/phpunit.xml
```

## Features

The GLS Parcel Processing API SDK supports the following features:

* Create shipments with labels
* Cancel parcels

### Create Shipments

Create shipments with one or more parcels and retrieve shipping labels.
Value-added services may be ordered per parcel. Return shipment labels
can be requested either standalone ("return only") or together with the
regular "way-to" label.

#### Public API

The library's components suitable for consumption comprise

* services:
  * service factory
  * shipment service
  * data transfer object builders
* data transfer objects:
  * shipment with parcels
* exceptions

#### Usage

```php
$logger = new \Psr\Log\NullLogger();
$serviceFactory = new \GlsGroup\Sdk\ParcelProcessing\Service\ServiceFactory();
$service = $serviceFactory->createShipmentService('basicAuthUser', 'basicAuthPass', $logger, $sandbox = true);

// REGULAR SHIPMENT
$requestBuilder = new \GlsGroup\Sdk\ParcelProcessing\RequestBuilder\ShipmentRequestBuilder();
$requestBuilder->setShipperAccount($shipperId = '98765 43210');
$requestBuilder->setRecipientAddress(
    $country = 'DE',
    $postalCode = '36286',
    $city = 'Neuenstein',
    $street = 'GLS-Germany-Straße 1 - 7',
    $name = 'Jane Doe'
);
$requestBuilder->addParcel($parcelWeightA = 0.95);
$requestBuilder->addParcel($parcelWeightB = 1.2);
$request = $requestBuilder->create();

$shipment = $service->createShipment($request);

// work with the web service response, e.g. persist label
foreach ($shipment->getLabels() as $i => $label) {
    file_put_contents("/tmp/{$shipment->getConsignmentId()}-{$i}.pdf", $label);
}

// RETURN SHIPMENT
$requestBuilder = new \GlsGroup\Sdk\ParcelProcessing\RequestBuilder\ReturnShipmentRequestBuilder();
$requestBuilder->setShipperAccount($shipperId = '98765 43210');
$requestBuilder->setShipperAddress(
    $country = 'DE',
    $postalCode = '36286',
    $city = 'Neuenstein',
    $street = 'GLS-Germany-Straße 1 - 7',
    $name = 'Jane Doe'
);
$requestBuilder->setRecipientAddress(
    $country = 'DE',
    $postalCode = '36286',
    $city = 'Neuenstein',
    $street = 'GLS Germany-Straße 1 - 7',
    $company = 'GLS Germany'
);
$requestBuilder->addParcel($weight = 0.95, $qrCode = true);
$request = $requestBuilder->create();

$shipment = $service->createShipment($request);
```

### Cancel Parcels

Cancel one or more parcels.

#### Public API

The library's components suitable for consumption comprise

* services:
  * service factory
  * cancellation service
* exceptions

#### Usage

```php
$logger = new \Psr\Log\NullLogger();
$serviceFactory = new \GlsGroup\Sdk\ParcelProcessing\Service\ServiceFactory();
$service = $serviceFactory->createCancellationService('basicAuthUser', 'basicAuthPass', $logger, $sandbox = true);

$cancelledIds = $service->cancelParcels([$parcelIdA = '12345', $parcelIdB = '54321']);
```
