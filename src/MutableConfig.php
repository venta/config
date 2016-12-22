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
     * @inheritdoc
     */
    public function merge(array $config)
    {
        $this->items = array_merge_recursive($this->items, $config);
    }

    /**
     * @inheritdoc
     */
    public function push(string $path, $value)
    {
        $keys = explode('.', $path);
        $array = &$this->items;

        while (count($keys) > 0) {
            $activeKey = array_shift($keys);

            if (!isset($array[$activeKey]) || !is_array($array[$activeKey])) {
                $array[$activeKey] = [$array[$activeKey]];
            }

            $array = &$array[$activeKey];
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
            $activeKey = array_shift($keys);

            if (!isset($array[$activeKey]) || !is_array($array[$activeKey])) {
                $array[$activeKey] = [];
            }

            $array = &$array[$activeKey];
        }

        $array[array_shift($keys)] = $value;
    }
}
