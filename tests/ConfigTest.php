<?php

use Venta\Config\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

    /**
     * @test
     */
    public function canCheckIfHasKey()
    {
        $config = new Config(['key' => 'value']);

        $this->assertTrue($config->has('key'));
        $this->assertFalse($config->has('other key'));
    }

    /**
     * @test
     */
    public function canCheckIssetOnNullValue()
    {
        $config = new Config(['key' => null]);

        $this->assertTrue($config->has('key'));
        $this->assertTrue(isset($config->key));
        $this->assertTrue(isset($config['key']));
    }

    /**
     * @test
     */
    public function canClone()
    {
        $array = [
            'key' => 'value',
            'another' => [
                'name' => 'qwerty',
            ],
        ];
        $config = new Config($array);
        $another = $config->get('another');
        $clone = clone $config;

        $this->assertSame($config->toArray(), $clone->toArray());
        $this->assertNotSame($another, $clone->get('another'));
    }

    /**
     * @test
     */
    public function canConvertArrayToSelf()
    {
        $array = [
            'key' => 'value',
            'another' => [
                'name' => 'qwerty',
            ],
        ];
        $config = new Config($array);

        $this->assertInstanceOf(Config::class, $config->get('another'));
        $this->assertSame('qwerty', $config->get('another')->get('name'));
    }

    /**
     * @test
     */
    public function canConvertToArray()
    {
        $array = ['key' => 'value'];
        $config = new Config($array);

        $this->assertSame($array, $config->toArray());
    }

    /**
     * @test
     */
    public function canConvertToJson()
    {
        $array = ['key' => 'value'];
        $config = new Config($array);

        $this->assertSame(json_encode($array), json_encode($config));
    }

    /**
     * @test
     */
    public function canCount()
    {
        $config = new Config(['key' => 'value']);

        $this->assertCount(1, $config);
    }

    /**
     * @test
     */
    public function canGetSetValues()
    {
        $config = new Config();
        $config->set('key', 'value');

        $this->assertSame('value', $config->get('key'));
    }

    /**
     * @test
     */
    public function canHandleIsset()
    {
        $config = new Config(['key' => 'value']);

        $this->assertTrue(isset($config->key));
        $this->assertFalse(isset($config->other_key));
    }

    /**
     * @test
     */
    public function canHandleIssetArraySyntax()
    {
        $config = new Config(['key' => 'value']);

        $this->assertTrue(isset($config['key']));
        $this->assertFalse(isset($config['other_key']));
    }

    /**
     * @test
     */
    public function canHandleMultidimensionalArray()
    {
        $array = [
            'key' => 'value',
            'another' => [
                'name' => 'qwerty',
            ],
        ];
        $config = new Config($array);

        $this->assertSame($array, $config->toArray());
    }

    /**
     * @test
     */
    public function canHandleNonAssociativeArray()
    {
        $array = ['value1', 'value2'];
        $config = new Config($array);

        $this->assertSame($array, $config->toArray());
    }

    /**
     * @test
     */
    public function canIterate()
    {
        $keys = ['key', 'another'];
        $values = ['value', 'val'];
        $config = new Config(array_combine($keys, $values));

        foreach ($config as $key => $value) {
            $this->assertContains($key, $keys);
            $this->assertContains($value, $values);
        }
    }

    /**
     * @test
     */
    public function canLockForModification()
    {
        $config = new Config();
        $this->assertFalse($config->isLocked());
        $config->lock();
        $this->assertTrue($config->isLocked());
    }

    /**
     * @test
     */
    public function canLockRecursively()
    {
        $array = [
            'key' => 'value',
            'another' => [
                'name' => 'qwerty',
            ],
        ];
        $config = new Config($array);
        $config->lock();

        $this->assertTrue($config->isLocked());
        $this->assertTrue($config->get('another')->isLocked());
    }

    /**
     * @test
     */
    public function canMerge()
    {
        $array = [
            'key' => 'value',
            'map' => [
                'name' => 'qwerty',
            ],
            'vector' => [
                'value 1',
                'value 2',
                'value 3',
            ],
            'this' => 'will be overwritten',
            'another' => [
                'config' => 'instance',
            ],
        ];
        $config = new Config($array);
        $merge = [
            'map' => [
                'name' => 'zxcv',
                'other' => 'poiuyt',
            ],
            'vector' => [
                'value 4',
                'value 5',
            ],
            'this' => 'is overwritten',
            'another' => 'plain string',
        ];
        $result = $config->merge(new Config($merge));

        $this->assertSame('value', $result->get('key'));
        $this->assertSame('zxcv', $result->get('map')->get('name'));
        $this->assertSame('poiuyt', $result->get('map')->get('other'));
        $this->assertSame(['value 1', 'value 2', 'value 3', 'value 4', 'value 5',], $result->get('vector')->toArray());
        $this->assertSame('is overwritten', $result->get('this'));
        $this->assertSame('plain string', $result->get('another'));
    }

    /**
     * @test
     */
    public function canPushValue()
    {
        $config = new Config();
        $config->push('value1');
        $config->push('value2');

        $this->assertSame(['value1', 'value2'], $config->toArray());
    }

    /**
     * @test
     */
    public function canPushValueToArray()
    {
        $config = new Config();
        $config[] = 'value1';
        $config[] = 'value2';

        $this->assertSame(['value1', 'value2'], $config->toArray());
    }

    /**
     * @test
     */
    public function canSetThroughConstructor()
    {
        $config = new Config(['key' => 'value']);

        $this->assertSame('value', $config->get('key'));
    }

    /**
     * @test
     */
    public function canSetUsingArraySyntax()
    {
        $config = new Config();
        $config['key'] = 'value';

        $this->assertSame('value', $config['key']);
    }

    /**
     * @test
     */
    public function canSetUsingProperties()
    {
        $config = new Config();
        $config->key = 'value';

        $this->assertSame('value', $config->key);
    }

    /**
     * @test
     */
    public function canUnsetKey()
    {
        $config = new Config();
        $config['key'] = 'value';

        $this->assertTrue(isset($config['key']));
        unset($config['key']);
        $this->assertFalse(isset($config['key']));
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Config is locked for modifications.
     */
    public function throwsExceptionOnLockedConfigModification()
    {
        $config = new Config();
        $config->lock();
        $config->set('abc', 'def');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Config is locked for modifications.
     */
    public function throwsExceptionOnLockedConfigModificationAsArray()
    {
        $config = new Config();
        $config->lock();
        $config['abc'] = 'def';
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Config is locked for modifications.
     */
    public function throwsExceptionOnLockedConfigPropertyModification()
    {
        $config = new Config();
        $config->lock();
        $config->abc = 'def';
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Config is locked for modifications.
     */
    public function throwsExceptionOnLockedConfigPush()
    {
        $config = new Config();
        $config->lock();
        $config->push('value');
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Config is locked for modifications.
     */
    public function throwsExceptionOnLockedConfigPushToArray()
    {
        $config = new Config();
        $config->lock();
        $config[] = 'value';
    }

    /**
     * @test
     * @expectedException RuntimeException
     * @expectedExceptionMessage Config is locked for modifications.
     */
    public function throwsExceptionOnLockedConfigUnset()
    {
        $config = new Config();
        $config->lock();
        unset($config['key']);
    }

    /**
     * @test
     */
    public function nameIsAttachedToConfig()
    {
        $array = [
            'key' => 'value',
            'another' => [
                'name' => 'qwerty',
            ],
        ];

        $config = new Config($array);
        $this->assertEquals('root', $config->getName());
        $this->assertEquals('another', $config->get('another')->getName());
    }
}
