<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace GlsGroup\Sdk\ParcelProcessing\Serializer;

/**
 * JsonSerializer
 *
 * Serializer for outgoing request types and incoming responses.
 */
class JsonSerializer
{
    /**
     * @var string[]
     */
    private $classMap;

    /**
     * JsonSerializer constructor.
     * @param string[] $classMap
     */
    public function __construct(array $classMap = [])
    {
        $this->classMap = $classMap;
    }

    /**
     * @param \JsonSerializable $request
     * @return string
     */
    public function encode(\JsonSerializable $request): string
    {
        // remove empty entries from serialized data (after all objects were converted to array)
        $payload = \json_encode($request);
        if ($payload !== false) {
            $payload = \json_decode($payload, true);
            $payload = $this->filterRecursive($payload);

            return \json_encode($payload) ?: '';
        }

        return '';
    }

    /**
     * @param mixed[] $element
     * @return mixed[]
     */
    private function filterRecursive(array $element): array
    {
        // Filter null, empty strings, empty arrays
        $filterFunction = function ($entry): bool {
            return ($entry !== null) && ($entry !== '') && (!is_array($entry) || count($entry) > 0);
        };

        foreach ($element as &$value) {
            if (\is_array($value)) {
                $value = $this->filterRecursive($value);
            }
        }

        return array_filter($element, $filterFunction);
    }

    /**
     * @param string $jsonResponse
     * @param string $className
     * @return mixed
     * @throws \JsonMapper_Exception
     */
    public function decode(string $jsonResponse, string $className = '')
    {
        $jsonMapper = new \JsonMapper();
        $jsonMapper->bIgnoreVisibility = true;
        $jsonMapper->classMap = $this->classMap;

        $response = \json_decode($jsonResponse);

        if (isset($this->classMap[$className])) {
            $className = $this->classMap[$className];
        }

        return $jsonMapper->map($response, new $className());
    }
}
