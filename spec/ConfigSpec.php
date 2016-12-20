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

    public function it_can_be_counted()
    {
        $this->shouldImplement(\Countable::class);
        $this->count()->shouldBe(2);
    }

    public function it_can_be_iterated()
    {
        $this->shouldImplement(\IteratorAggregate::class);
        $this->getIterator()->shouldBeAnInstanceOf(\ArrayIterator::class);
    }

    public function it_can_be_json_serialised()
    {
        $this->shouldImplement(\JsonSerializable::class);
        $this->jsonSerialize()->shouldBeArray();
    }

    public function it_can_check_value_existence()
    {
        $this->has('logger')->shouldBe(true);
        $this->has('logger.level')->shouldBe(true);
        $this->has('database.name')->shouldBe(true);
        $this->has('logger.format')->shouldBe(false);
    }

    public function it_can_get_values()
    {
        $this->get('logger')->shouldBeArray();
        $this->get('logger.level')->shouldBe('debug');
        $this->get('logger.format')->shouldBe(null);
        $this->get('database.port', '8080')->shouldBe('8080');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Config::class);
        $this->shouldImplement(ConfigContract::class);
    }
}