<?php

namespace spec\Venta\Config;

use PhpSpec\ObjectBehavior;
use Venta\Config\Config;
use Venta\Contracts\Config\Config as ConfigContract;

class ConfigSpec extends ObjectBehavior
{
    function can_be_constructed_with_data()
    {
        $this->beConstructedWith(['key' => 'value']);
        $this->shouldHaveKeyWithValue('key', 'value');
    }

    public function getMatchers()
    {
        return [
            'matchJson' => function ($subject, $json) {
                return json_encode($subject) == $json;
            },
            'havePropertyWithValue' => function ($subject, $key, $value) {
                return $subject->{$key} === $value;
            },
            'haveProperty' => function ($subject, $key) {
                return property_exists($subject, $key);
            },
        ];
    }

    function it_can_be_locked_for_modifications()
    {
        $this->beConstructedWith([
            'key' => 'value',
            'sub' => [
                'key' => 'value',
            ],
        ]);

        $this->isLocked()->shouldBe(false);
        $this->lock();
        $this->isLocked()->shouldBe(true);
        $this->get('sub')->isLocked()->shouldBe(true);
    }

    function it_can_be_presented_as_array()
    {
        $array = ['key' => 'value'];
        $this->beConstructedWith($array);
        $this->toArray()->shouldBe($array);
    }

    function it_can_be_presented_as_json()
    {
        $array = ['key' => 'value'];
        $this->beConstructedWith($array);
        $this->shouldMatchJson(json_encode($array));
    }

    function it_can_push_values_as_array()
    {
        $this[] = 'value1';
        $this[] = 'value2';
        $this->toArray()->shouldBe(['value1', 'value2']);
    }

    function it_can_push_values_as_object()
    {
        $this->push('value1');
        $this->push('value2');
        $this->toArray()->shouldBe(['value1', 'value2']);
    }

    function it_checks_keys_for_existence()
    {
        $this->beConstructedWith([
            'key' => 'value',
            'nullable' => null,
        ]);

        $this->has('key')->shouldReturn(true);
        $this->has('nullable')->shouldReturn(true);
        $this->has('unknown')->shouldReturn(false);
    }

    function it_counts_number_of_keys()
    {
        $array = range(1, rand(1, 20));
        $this->beConstructedWith($array);
        $this->count()->shouldBe(count($array));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Config::class);
        $this->shouldImplement(ConfigContract::class);
    }

    function it_returns_subtree_as_a_config_object()
    {
        $this->beConstructedWith([
            'key' => [],
        ]);

        $this->getName()->shouldBe('root');
        $this->get('key')->shouldBeAnInstanceOf(Config::class);
    }

    function it_works_as_array()
    {
        $this->shouldNotHaveKey('key');
        $this['key'] = 'value';
        $this->shouldHaveKeyWithValue('key', 'value');
        $this['key'] = 'new_value';
        $this->shouldHaveKeyWithValue('key', 'new_value');
        unset($this['key']);
        $this->shouldNotHaveKey('key');
    }

    function it_works_as_object()
    {
        $this->shouldNotHaveProperty('key');
        $this->key = 'value';
        $this->shouldHavePropertyWithValue('key', 'value');
        $this->key = 'new_value';
        $this->shouldHavePropertyWithValue('key', 'new_value');
        unset($this->key);
        $this->shouldNotHaveProperty('key');
    }
}
