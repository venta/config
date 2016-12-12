<?php declare(strict_types = 1);

namespace Venta\Config;

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
    public function create(array $data): ConfigContract
    {
        return new Config($data);
    }

}