<?php declare(strict_types = 1);

namespace Venta\Config;

use Venta\Contracts\Config\MutableConfig as MutableConfigContract;

/**
 * Class MutableConfig
 *
 * @package Venta\Config
 */
class MutableConfig extends Config implements MutableConfigContract
{
    /**
     * MutableConfig constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct($items);
    }

    /**
     * @inheritdoc
     */
    public function merge(array $config)
    {
        $this->items = array_replace_recursive($this->items, $config);
    }

    /**
     * @inheritdoc
     */
    public function push(string $path, $value)
    {
        $keys = explode('.', $path);
        $array = &$this->items;

        while (count($keys) > 0) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        array_push($array, $value);
    }

    /**
     * @inheritdoc
     */
    public function set(string $path, $value)
    {
        $keys = explode('.', $path);
        $array = &$this->items;

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
    }
}
