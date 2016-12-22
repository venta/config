<?php declare(strict_types = 1);

namespace Venta\Config;

use ArrayIterator;
use IteratorAggregate;
use Venta\Contracts\Config\Config as ConfigContract;

/**
 * Class Config
 *
 * @package Venta\Config
 */
class Config implements IteratorAggregate, ConfigContract
{
    /**
     * Array of configuration items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Construct function.
     *
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        $keys = explode('.', $key);
        $items = $this->items;

        foreach ($keys as $key) {
            if (array_key_exists($key, $items)) {
                $items = $items[$key];
            } else {
                return $default;
            }
        }

        return $items;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        $keys = explode('.', $key);
        $items = $this->items;

        foreach ($keys as $key) {
            if (array_key_exists($key, $items)) {
                $items = $items[$key];
            } else {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->items;
    }
}