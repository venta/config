<?php declare(strict_types = 1);

namespace Venta\Config;

use ArrayIterator;
use RuntimeException;
use Traversable;
use Venta\Contracts\Config\Config as ConfigContract;

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
     * Returns config value for provided key.
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        if ($this->__isset($key)) {
            return is_array($this->items[$key]) ? new self($this->items[$key]) : $this->items[$key];
        }

        return null;
    }

    /**
     * Checks if config contains value for provided key.
     *
     * @param $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return array_key_exists($key, $this->items);
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
    public function getIterator()
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    function jsonSerialize()
    {
        return $this->items;
    }

    /**
     * Returns array representation of config.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }
}