<?php declare(strict_types = 1);

namespace Venta\Config\Contract;

/**
 * Interface Parser
 *
 * @package Venta\Config\Contracts
 */
interface Parser
{

    /**
     * Parses configuration string into Config object
     *
     * @param string $configuration
     * @return Config
     */
    public function parse(string $configuration): Config;

}