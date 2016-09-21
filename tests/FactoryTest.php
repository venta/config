<?php

use Venta\Config\Contract\Config;
use Venta\Config\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{

    /**
     * @test
     */
    public function canCreateFromJson()
    {
        $factory = new Factory();
        $config = $factory->fromFile(__DIR__ . '/config.json');

        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame('value', $config->get('key'));
    }

    /**
     * @test
     */
    public function canIncludePhpFile()
    {
        $factory = new Factory();
        $config = $factory->fromFile(__DIR__ . '/config.php');

        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame('value', $config->get('key'));
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function throwsExceptionOnInvalidFile()
    {
        $factory = new Factory();
        $config = $factory->fromFile(__DIR__ . '/non_existing_file');
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwsExceptionOnUnknownConfigFormat()
    {
        $factory = new Factory();
        $config = $factory->fromFile(__DIR__ . '/stub.qwerty');
    }

}
