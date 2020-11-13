<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Http;

use GlsGroup\Sdk\ParcelProcessing\Api\CancellationServiceInterface;
use GlsGroup\Sdk\ParcelProcessing\Api\ServiceFactoryInterface;
use GlsGroup\Sdk\ParcelProcessing\Api\ShipmentServiceInterface;
use GlsGroup\Sdk\ParcelProcessing\Exception\ServiceExceptionFactory;
use GlsGroup\Sdk\ParcelProcessing\Http\ClientPlugin\ErrorPlugin;
use GlsGroup\Sdk\ParcelProcessing\Model\Shipment\ShipmentResponseMapper;
use GlsGroup\Sdk\ParcelProcessing\Serializer\JsonSerializer;
use GlsGroup\Sdk\ParcelProcessing\Service\CancellationService;
use GlsGroup\Sdk\ParcelProcessing\Service\ShipmentService;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\ContentLengthPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Message\Authentication\BasicAuth;
use Http\Message\Formatter\FullHttpMessageFormatter;
use Psr\Log\LoggerInterface;

class HttpServiceFactory implements ServiceFactoryInterface
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $acceptLanguage;

    /**
     * HttpServiceFactory constructor.
     *
     * @param HttpClient $httpClient
     * @param string $acceptLanguage
     */
    public function __construct(HttpClient $httpClient, string $acceptLanguage = '')
    {
        $this->httpClient = $httpClient;
        $this->acceptLanguage = $acceptLanguage;
    }

    public function createShipmentService(
        string $username,
        string $password,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ShipmentServiceInterface {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Accept-Language' => $this->acceptLanguage,
        ];

        $client = new PluginClient(
            $this->httpClient,
            [
                new HeaderDefaultsPlugin(array_filter($headers)),
                new AuthenticationPlugin(new BasicAuth($username, $password)),
                new ContentLengthPlugin(),
                new LoggerPlugin($logger, new FullHttpMessageFormatter(null)),
                new ErrorPlugin(),
            ]
        );

        try {
            $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
            $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        } catch (NotFoundException $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        return new ShipmentService(
            $client,
            $sandboxMode ? self::SANDBOX_BASE_URL : self::PRODUCTION_BASE_URL,
            new JsonSerializer(),
            $requestFactory,
            $streamFactory,
            new ShipmentResponseMapper()
        );
    }

    public function createCancellationService(
        string $username,
        string $password,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): CancellationServiceInterface {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Accept-Language' => $this->acceptLanguage,
        ];

        $client = new PluginClient(
            $this->httpClient,
            [
                new HeaderDefaultsPlugin(array_filter($headers)),
                new AuthenticationPlugin(new BasicAuth($username, $password)),
                new ContentLengthPlugin(),
                new LoggerPlugin($logger, new FullHttpMessageFormatter(null)),
                new ErrorPlugin(),
            ]
        );

        try {
            $requestFactory = Psr17FactoryDiscovery::findRequestFactory();
            $streamFactory = Psr17FactoryDiscovery::findStreamFactory();
        } catch (NotFoundException $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        return new CancellationService(
            $client,
            $sandboxMode ? self::SANDBOX_BASE_URL : self::PRODUCTION_BASE_URL,
            new JsonSerializer(),
            $requestFactory,
            $streamFactory
        );
    }
}
