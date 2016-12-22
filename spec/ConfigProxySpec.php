<?php

namespace spec\Venta\Config;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Config\Config;
use Venta\Contracts\Config\MutableConfig;

class ConfigProxySpec extends ObjectBehavior
{
    function let(MutableConfig $mutableConfig)
    {
        $this->beConstructedWith($mutableConfig);
    }

    function it_allows_to_check_value_existence(MutableConfig $mutableConfig)
    {
        $mutableConfig->has('logger')->willReturn(true);
        $mutableConfig->has('logger.level')->willReturn(true);
        $mutableConfig->has('unknown')->willReturn(false);

        $this->has('logger')->shouldBe(true);
        $this->has('logger.level')->shouldBe(true);
        $this->has('unknown')->shouldBe(false);

        $mutableConfig->has('logger')->shouldHaveBeenCalled();
        $mutableConfig->has('logger.level')->shouldHaveBeenCalled();
        $mutableConfig->has('unknown')->shouldHaveBeenCalled();
    }

    function it_can_be_json_serialised(MutableConfig $mutableConfig)
    {
        $mutableConfig->jsonSerialize()->willReturn($this->stubConfig());
        $this->jsonSerialize()->shouldBe($this->stubConfig());
        $mutableConfig->jsonSerialize()->shouldHaveBeenCalled();
    }

    function it_can_return_all_values(MutableConfig $mutableConfig)
    {
        $mutableConfig->all()->willReturn($this->stubConfig());
        $this->all()->shouldBe($this->stubConfig());
        $mutableConfig->all()->shouldHaveBeenCalled();
    }

    function it_can_return_value_by_key(MutableConfig $mutableConfig)
    {
        $mutableConfig->get('logger', null)->willReturn(['level' => 'debug']);
        $mutableConfig->get('logger.level', null)->willReturn('debug');
        $mutableConfig->get('unknown', null)->willReturn(null);
        $mutableConfig->get('unknown', 'default')->willReturn('default');

        $this->get('logger')->shouldBe(['level' => 'debug']);
        $this->get('logger.level')->shouldBe('debug');
        $this->get('unknown')->shouldBeNull();
        $this->get('unknown', 'default')->shouldBe('default');

        $mutableConfig->get('logger', null)->shouldHaveBeenCalled();
        $mutableConfig->get('logger.level', null)->shouldHaveBeenCalled();
        $mutableConfig->get('unknown', null)->shouldHaveBeenCalled();
        $mutableConfig->get('unknown', 'default')->shouldHaveBeenCalled();
    }

    function it_is_initializable()
    {
        $this->shouldImplement(Config::class);
    }

    protected function stubConfig()
    {
        return [
            'logger' => [
                'level' => 'debug',
            ],
        ];
    }
}
