<?php

use PHPUnit\Framework\TestCase;
use Venta\Config\Parser\Json;
use Venta\Contracts\Config\Config;

class ParserJsonTest extends TestCase
{

    /**
     * @test
     */
    public function canParse()
    {
        $json = json_encode(['key' => 'value']);
        $parser = new Json();
        $config = $parser->fromString($json);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertSame('value', $config->get('key'));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwsExceptionOnInvalidJsonString()
    {
        $parser = new Json();
        $config = $parser->fromString('{"key":"value"');
    }

}
