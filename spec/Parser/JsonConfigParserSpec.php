<?php

namespace spec\Venta\Config\Parser;

use PhpSpec\ObjectBehavior;
use RuntimeException;
use Venta\Contracts\Config\ConfigFileParser;
use Venta\Contracts\Config\ConfigStringParser;
use Venta\Contracts\Filesystem\Filesystem;

class JsonConfigParserSpec extends ObjectBehavior
{
    function let(Filesystem $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ConfigFileParser::class);
        $this->shouldImplement(ConfigStringParser::class);
    }

    function it_parses_json_files(Filesystem $filesystem)
    {
        $config = ['foo' => 'bar', 'key' => 'value'];
        $filesystem->read('json.json')->willReturn(json_encode($config));

        $this->supportedExtensions()->shouldContain('json');
        $this->parseFile('json.json')->shouldBe($config);
        $this->shouldThrow(RuntimeException::class)->during('parseFile', ['non-existing-file.json']);
    }

    function it_parses_json_strings()
    {
        $config = ['foo' => 'bar', 'key' => 'value'];
        $this->parseString(json_encode($config))->shouldBeEqualTo($config);
        $this->shouldThrow(RuntimeException::class)->during('parseString', ['foo']);
    }
}