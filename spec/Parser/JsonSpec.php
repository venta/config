<?php

namespace spec\Venta\Config\Parser;

use PhpSpec\ObjectBehavior;
use Venta\Config\Parser\Json;
use VirtualFileSystem\FileSystem;

class JsonSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(Json::class);
    }

    public function it_can_parse_items()
    {
        $config = ['foo' => 'bar', 'key' => 'value'];
        $filesystem = new FileSystem();
        file_put_contents($filesystem->path('/json.json'), json_encode($config));

        $this->fromString(json_encode($config))->shouldBeArray();
        $this->fromString(json_encode($config))->shouldBeEqualTo($config);

        $this->fromFile($filesystem->path('/json.json'))->shouldBeArray();
        $this->fromFile($filesystem->path('/json.json'))->shouldBeEqualTo($config);
    }

    public function it_can_throw_errors()
    {
        $this->shouldThrow('\RuntimeException')->during('fromString', [json_encode([]) . 'foo']);
        $this->shouldThrow('\RuntimeException')->during('fromFile', ['non-existing-file.json']);
    }
}