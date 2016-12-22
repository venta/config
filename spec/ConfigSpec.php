<?php

namespace spec\Venta\Config;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Config\Config as ConfigContract;

class ConfigSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith($this->stubConfig());
    }

    function it_allows_to_check_value_existence()
    {
        $this->has('logger')->shouldBe(true);
        $this->has('logger.level')->shouldBe(true);
        $this->has('unknown')->shouldBe(false);
    }

    function it_can_be_json_serialised()
    {
        $this->jsonSerialize()->shouldBe($this->stubConfig());
    }

    function it_can_return_all_values()
    {
        $this->all()->shouldBe($this->stubConfig());
    }

    function it_can_return_value_by_key()
    {
        $this->get('logger')->shouldBe(['level' => 'debug']);
        $this->get('logger.level')->shouldBe('debug');
        $this->get('unknown')->shouldBeNull();
        $this->get('unknown', 'default')->shouldBe('default');
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ConfigContract::class);
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