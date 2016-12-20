<?php declare(strict_types = 1);

namespace Venta\Config;

use ArrayIterator;
use RuntimeException;
use Traversable;
use Venta\Contracts\Config\Config as ConfigContract;
use Venta\Routing\ProcessingRouteCollection;

/**
 * Class Config
 *
 * @package Venta\Config
 */
final class Config implements ConfigContract
{
    /**
     * Array of configuration items.
     *
     * @var array
     */
    private $items = [];

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
    public function count()
    {
        return count($this->items);
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

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->items;
    }
}