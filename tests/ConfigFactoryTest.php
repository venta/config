<?php

use PHPUnit\Framework\TestCase;
use Venta\Config\ConfigFactory;
use Venta\Contracts\Config\Config;

class ConfigFactoryTest extends TestCase
{

    /**
     * @test
     */
    public function canCreateFromJson()
    {
        $factory = new ConfigFactory();
        $config = $factory->fromFile(__DIR__ . '/config.json');

        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame('value', $config->get('key'));
    }

    /**
     * @test
     */
    public function canIncludePhpFile()
    {
        $factory = new ConfigFactory();
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
        $factory = new ConfigFactory();
        $config = $factory->fromFile(__DIR__ . '/non_existing_file');
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwsExceptionOnUnknownConfigFormat()
    {
        $factory = new ConfigFactory();
        $config = $factory->fromFile(__DIR__ . '/stub.qwerty');
    }

}
