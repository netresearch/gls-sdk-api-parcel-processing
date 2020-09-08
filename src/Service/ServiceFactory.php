<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGermany\Sdk\ParcelProcessing\Service;

use GlsGermany\Sdk\ParcelProcessing\Api\CancellationServiceInterface;
use GlsGermany\Sdk\ParcelProcessing\Api\ServiceFactoryInterface;
use GlsGermany\Sdk\ParcelProcessing\Api\ShipmentServiceInterface;
use GlsGermany\Sdk\ParcelProcessing\Exception\ServiceExceptionFactory;
use GlsGermany\Sdk\ParcelProcessing\Http\HttpServiceFactory;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\HttpClientDiscovery;
use Psr\Log\LoggerInterface;

class ServiceFactory implements ServiceFactoryInterface
{
    /**
     * @var string
     */
    private $acceptLanguage;

    /**
     * ServiceFactory constructor.
     *
     * @param string $acceptLanguage
     */
    public function __construct(string $acceptLanguage = '')
    {
        $this->acceptLanguage = $acceptLanguage;
    }

    public function createShipmentService(
        string $username,
        string $password,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ShipmentServiceInterface {
        try {
            $httpClient = HttpClientDiscovery::find();
        } catch (NotFoundException $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        $httpServiceFactory = new HttpServiceFactory($httpClient, $this->acceptLanguage);
        return $httpServiceFactory->createShipmentService($username, $password, $logger, $sandboxMode);
    }

    public function createCancellationService(
        string $username,
        string $password,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): CancellationServiceInterface {
        try {
            $httpClient = HttpClientDiscovery::find();
        } catch (NotFoundException $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        $httpServiceFactory = new HttpServiceFactory($httpClient, $this->acceptLanguage);
        return $httpServiceFactory->createCancellationService($username, $password, $logger, $sandboxMode);
    }
}
