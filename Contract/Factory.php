<?php declare(strict_types = 1);

namespace Venta\Config\Contract;

/**
 * Interface Factory
 *
 * @package Venta\Config\Contracts
 */
interface Factory
{

    /**
     * Creates config instance from file
     *
     * @param $filename
     * @return Config
     */
    public function fromFile($filename): Config;

}