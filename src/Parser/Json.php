<?php declare(strict_types = 1);

namespace Venta\Config\Parser;

use Venta\Contracts\Config\ConfigParser;

/**
 * Class Json
 *
 * @package Venta\Config\Parser
 */
class Json implements ConfigParser
{
    /**
     * @inheritDoc
     */
    public function fromString(string $configuration): array
    {
        $array = json_decode($configuration, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                sprintf('Unable to parse configuration string: "%s".', json_last_error_msg()),
                json_last_error()
            );
        }

        return $array;
    }
}