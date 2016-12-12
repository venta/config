<?php declare(strict_types = 1);

namespace Venta\Config;

use ArrayIterator;
use RuntimeException;
use Venta\Contracts\Config\Config as ConfigContract;

/**
 * Class Config
 *
 * @package Venta\Config
 */
final class Config implements ConfigContract
{

    /**
     * Config data holder
     *
     * @var array
     */
    private $data = [];

    /**
     * Locked flag
     *
     * @var bool
     */
    private $isMutable = true;

    /**
     * Node name
     *
     * @var string
     */
    private $name;

    /**
     * Config constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [], $nodeName = '')
    {
        $this->name = ($nodeName === '') ? 'root' : $nodeName;
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @inheritDoc
     */
    public function __clone()
    {
        $array = [];

        foreach ($this->data as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = clone $value;
            } else {
                $array[$key] = $value;
            }
        }

        $this->data = $array;
        $this->isMutable = true;
    }

    /**
     * @inheritDoc
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @inheritDoc
     */
    public function isLocked(): bool
    {
        return !$this->isMutable;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function lock()
    {
        $this->isMutable = false;
        foreach ($this->data as $value) {
            if ($value instanceof self) {
                $value->lock();
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function merge(ConfigContract $config): ConfigContract
    {
        $merge = clone $this;
        $merge->isMutable = true;
        foreach ($config as $key => $value) {
            if ($value instanceof self) {
                // We don't want the same instance to be used in merge result
                $value = clone $value;
            }
            if ($merge->has($key)) {
                // replace the value gracefully
                if (is_int($key)) {
                    // append the new value to config data
                    $merge->push($value);
                } elseif ($merge->get($key) instanceof self) {
                    if ($value instanceof self) {
                        // merge 2 config objects recursively
                        $merge->set($key, $merge->get($key)->merge($value));
                    } else {
                        // overwrite config object with plain value
                        $merge->set($key, $value);
                    }
                } else {
                    // replace one plain value with the new one
                    $merge->set($key, $value);
                }
            } else {
                // just set the new value
                $merge->set($key, $value);
            }
        }

        return $merge;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        if ($this->isLocked()) {
            throw new RuntimeException('Config is locked for modifications.');
        }
        unset($this->data[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function push($value)
    {
        if ($this->isLocked()) {
            throw new RuntimeException('Config is locked for modifications.');
        }
        $this->data[] = $value;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value)
    {
        if ($this->isLocked()) {
            throw new RuntimeException('Config is locked for modifications.');
        }
        if (is_array($value)) {
            $value = new self($value, $key);
        }
        if (empty($key) || is_int($key)) {
            $this->push($value);
        } else {
            $this->data[$key] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $array = [];

        foreach ($this->data as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

}