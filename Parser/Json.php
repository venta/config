<?php declare(strict_types = 1);

namespace Venta\Config\Parser;

use Venta\Config\Config;
use Venta\Config\Contract\Config as ConfigContract;
use Venta\Config\Contract\Parser;

/**
 * Class Json
 *
 * @package Venta\Config\Parser
 */
class Json implements Parser
{
    /**
     * @inheritDoc
     */
    public function parse(string $configuration): ConfigContract
    {
        $array = json_decode($configuration, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException(
                sprintf('Unable to parse configuration string: "%s".', json_last_error_msg()),
                json_last_error()
            );
        }

        return new Config($array);
    }
}