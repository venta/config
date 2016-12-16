<?php

namespace Venta\Config;

use Venta\Contracts\Config\ConfigParserCollection as ConfigParserCollectionContract;

/**
 * Class ConfigParserCollection
 *
 * @package Venta\Config
 */
class ConfigParserCollection implements ConfigParserCollectionContract
{
    /**
     * Parsers collection holder.
     *
     * @var array
     */
    private $parsers = [];

    /**
     * @inheritDoc
     */
    public function add(string $parser)
    {
        $this->parsers[] = $parser;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return array_unique($this->parsers);
    }
}