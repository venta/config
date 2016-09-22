<?php declare(strict_types = 1);

namespace Venta\Config;

use Venta\Config\Parser\Json;
use Venta\Contracts\Config\Config as ConfigContract;
use Venta\Contracts\Config\ConfigFactory as ConfigFactoryContract;

/**
 * Class ConfigFactory
 *
 * @package Venta\Config
 */
class ConfigFactory implements ConfigFactoryContract
{

    /**
     * @inheritDoc
     */
    public function fromFile($filename): ConfigContract
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new \InvalidArgumentException(sprintf('File "%s" does not exist or is not readable', $filename));
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        switch ($extension) {
            case 'php':
                return new Config(include $filename);
            case 'json':
                return (new Json())->parse(file_get_contents($filename));
            default:
                throw new \RuntimeException(sprintf('Unknown config format "%s"', $extension));
        }
    }

}