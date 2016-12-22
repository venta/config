<?php

namespace spec\Venta\Config;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Config\MutableConfig;

class MutableConfigSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldImplement(MutableConfig::class);
    }


    function it_merges_array_configs()
    {
        $this->beConstructedWith($this->stubConfig());
        $this->merge(
            [
                'logger' => [
                    'level' => 'alert',
                ],
                'db' => [
                    'mysql' => [
                        'password' => 'password',
                    ],
                ],
            ]
        );

        $this->get('logger.level')->shouldBe('alert');
        $this->get('db.mysql.host')->shouldBe('localhost');
        $this->get('db.mysql.password')->shouldBe('password');
    }


    function it_pushes_values_into_array_under_specific_key()
    {
        $this->push('foo.bar', 1);
        $this->push('foo.bar', 2);
        $this->push('foo.bar', 3);

        $this->get('foo')->shouldBe(['bar' => [1, 2, 3]]);
        $this->get('foo.bar')->shouldBe([1, 2, 3]);
    }


    function it_sets_value_for_specific_key()
    {
        $this->set('logger.level', 'debug');
        $this->get('logger.level')->shouldBe('debug');
    }


    protected function stubConfig()
    {
        return [
            'logger' => [
                'level' => 'debug',
            ],
            'db' => [
                'mysql' => [
                    'host' => 'localhost',
                    'user' => 'root',
                ],
            ],
        ];
    }
}
