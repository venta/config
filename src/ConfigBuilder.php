<?php

namespace Venta\Config;

use Venta\Contracts\Config\Config as ConfigContract;
use Venta\Contracts\Config\ConfigBuilder as ConfigBuilderContract;
use Venta\Contracts\Config\ConfigFactory as ConfigFactoryContract;
use Venta\Contracts\Config\ConfigFileParser;

/**
 * Class ConfigBuilder
 *
 * @package Venta\Config
 */
class ConfigBuilder implements ConfigBuilderContract
{
    /**
     * @var ConfigFactoryContract
     */
    private $configFactory;

    /**
     * Items holder array.
     *
     * @var array
     */
    private $items = [];

    /**
     * @var ConfigFileParser[]
     */
    private $parsers = [];

    /**
     * Construct function.
     *
     * @param null|ConfigFactoryContract $configFactory
     */
    public function __construct(ConfigFactoryContract $configFactory = null)
    {
        $this->configFactory = $configFactory ?? new ConfigFactory();
    }

    /**
     * @inheritDoc
     */
    public function addFileParser(ConfigFileParser $parser)
    {
        $this->parsers[] = $parser;
    }

    /**
     * @inheritDoc
     */
    public function build(): ConfigContract
    {
        return $this->configFactory->create($this->items);
    }

    /**
     * Merges configuration data.
     *
     * @param array $config
     * @return void
     */
    public function merge(array $config)
    {
        $this->items = array_merge_recursive($this->items, $config);
    }

    /**
     * Merges configuration data form file.
     *
     * @param string $filename
     * @return void
     */
    public function mergeFile(string $filename)
    {
        // TODO: extension of file can be empty. Exception in that case, or just ignore it?
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        foreach ($this->parsers as $parser) {
            if (($parser instanceof ConfigFileParser) && in_array($extension, $parser->supportedExtensions())) {
                $this->merge($parser->fromFile($filename));
                break;
            }
        }
    }

    /**
     * Appends a value to a config array.
     *
     * @param string $path
     * @param mixed  $value
     * @return void
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
     * Sets value to the configuration data.
     *
     * @param string $path
     * @param        $value
     * @return void
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
