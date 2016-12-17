<?php

namespace spec\Venta\Config\Parser;

use League\Flysystem\FilesystemInterface;
use PhpSpec\ObjectBehavior;
use Venta\Config\Parser\Json;

class JsonSpec extends ObjectBehavior
{
    public function let(FilesystemInterface $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Json::class);
    }

    public function it_can_parse_items(FilesystemInterface $filesystem)
    {
        $config = ['foo' => 'bar', 'key' => 'value'];

        $filesystem->has('json.json')->willReturn(true);
        $filesystem->read('json.json')->willReturn(json_encode($config));
        $this->beConstructedWith($filesystem);

        $this->fromString(json_encode($config))->shouldBeArray();
        $this->fromString(json_encode($config))->shouldBeEqualTo($config);

        $this->supportedExtensions()->shouldBeEqualTo(['json']);
        $this->fromFile('json.json')->shouldBeArray();
        $this->fromFile('json.json')->shouldBeEqualTo($config);
    }

    public function it_can_throw_errors()
    {
        $this->shouldThrow(\RuntimeException::class)->during('fromString', [json_encode([]) . 'foo']);
        $this->shouldThrow(\RuntimeException::class)->during('fromFile', ['non-existing-file.json']);
    }
}