<?php declare(strict_types = 1);

namespace Venta\Config\Contract;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Interface Config
 *
 * @package Venta\Config\Contracts
 */
interface Config extends Countable, ArrayAccess, IteratorAggregate, JsonSerializable
{

    /**
     * Returns config value for provided key
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Checks if config contains value for provided key
     *
     * @param $key
     * @return bool
     */
    public function has($key): bool;

    /**
     * Checks if config is mutable
     *
     * @return bool
     */
    public function isLocked(): bool;

    /**
     * Locks config for later modifications. Makes it immutable.
     *
     * @return void
     */
    public function lock();

    /**
     * Merges current config with provided instance and returns the result
     *
     * @param Config $config
     * @return Config
     */
    public function merge(Config $config): Config;

    /**
     * Append value to config
     *
     * @param $value
     * @return void
     */
    public function push($value);

    /**
     * Sets value to config
     *
     * @param string|integer $key
     * @param $value
     * @return void
     * @throws \RuntimeException if config is locked
     */
    public function set($key, $value);

    /**
     * Returns array representation of config
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * @return string
     */
    public function getName(): string;

}