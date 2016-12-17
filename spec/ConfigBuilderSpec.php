<?php

namespace spec\Venta\Config;

use PhpSpec\ObjectBehavior;
use Venta\Config\ConfigBuilder;
use Venta\Contracts\Config\Config;
use Venta\Contracts\Config\ConfigBuilder as ConfigBuilderContract;
use Venta\Contracts\Config\ConfigFileParser;

class ConfigBuilderSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ConfigBuilder::class);
        $this->shouldImplement(ConfigBuilderContract::class);
    }

    public function it_can_build_config()
    {
        $this->set('database.username', 'root');
        $this->set('database.password', 'root');
        $this->set('list', [1, 2, 3]);
        $this->set('list_double', 'foo');

        $this->push('list', 4);
        $this->push('list_double', 'bar');

        $this->merge(['logger' => ['level' => 'debug']]);

        $config = $this->build();
        $config->shouldBeAnInstanceOf(Config::class);
        $config->toArray()->shouldBeArray();
        $config->toArray()->shouldBe([
            'database' => [
                'username' => 'root',
                'password' => 'root'
            ],
            'list' => [1, 2, 3, 4],
            'list_double' => ['foo', 'bar'],
            'logger' => [
                'level' => 'debug'
            ]
        ]);
    }

    public function it_can_parse_files(ConfigFileParser $parser)
    {
        $parser->supportedExtensions()->willReturn(['json']);
        $parser->fromFile('json.json')->willReturn(['file_config' => ['foo', 'bar']]);

        $this->set('database.username', 'root');
        $this->set('database.password', 'root');

        $this->addFileParser($parser);
        $this->mergeFile('json.json');

        $config = $this->build();
        $config->toArray()->shouldBe([
             'database' => [
                 'username' => 'root',
                 'password' => 'root'
             ],
             'file_config' => ['foo', 'bar'],
         ]);
    }
}