<?php declare(strict_types = 1);

namespace Venta\Config;

use IteratorAggregate;
use Venta\Contracts\Config\Config as ConfigContract;
use Venta\Contracts\Config\MutableConfig as MutableConfigContract;

/**
 * Class ConfigProxy
 *
 * @package Venta\Config
 */
class ConfigProxy implements IteratorAggregate, ConfigContract
{

    /**
     * @var MutableConfigContract
     */
    private $config;

    /**
     * ConfigProxy constructor.
     *
     * @param MutableConfigContract $config
     */
    public function __construct(MutableConfigContract $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->config->all();
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return $this->config->count();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->config->has($key);
    }

    /**
     * @inheritDoc
     */
    function jsonSerialize()
    {
        return $this->config->jsonSerialize();
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->config instanceof IteratorAggregate ? $this->config->getIterator() : $this->config;
    }

}