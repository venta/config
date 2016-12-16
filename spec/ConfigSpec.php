<?php

namespace spec\Venta\Config;

use PhpSpec\ObjectBehavior;
use Venta\Config\Config;
use Venta\Contracts\Config\Config as ConfigContract;

class ConfigSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            [
                'database' => [
                    'name' => 'database',
                    'user' => 'root',
                    'password' => 'root',
                ],
                'logger' => [
                    'level' => 'debug',
                ],
            ]
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Config::class);
        $this->shouldImplement(ConfigContract::class);
    }

    public function it_can_get_values()
    {
        $this->logger->shouldBeAnInstanceOf(ConfigContract::class);
        $this->logger->level->shouldBe('debug');
        $this->non_existing_config->shouldBe(null);
    }
}