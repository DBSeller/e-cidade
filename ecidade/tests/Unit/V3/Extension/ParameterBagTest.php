<?php

namespace ECidade\V3\Extension;

use \ECidade\V3\Extension\ParameterBag;

class ParameterBagTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->obj = new ParameterBag();
    }

    public function tearDown()
    {
    }

  /**
   * @dataProvider provideDefaultData
   */
    public function testAll($data)
    {

        $this->assertEmpty($this->obj->all());
        $this->obj->add($data);
        $this->assertEquals($data, $this->obj->all());
    }

  /**
   * @dataProvider provideDefaultData
   */
    public function testKeys($data)
    {
        $this->obj->add($data);
        $this->assertEquals(array_keys($data), $this->obj->keys());
    }

  /**
   * @dataProvider provideDefaultData
   */
    public function testReplace($data)
    {

        $replace = array('test' => 'test');

        $this->obj->add($data);
        $this->obj->replace($replace);
        $this->assertEquals($replace, $this->obj->all());
    }

  /**
   * @dataProvider provideDefaultData
   */
    public function testGetSet($data)
    {

        $key = current(array_keys($data));
        $value = current($data);

        $this->obj->add($data);
        $this->assertEquals($value, $this->obj->get($key));

        $this->obj->set($key, '');
        $this->assertEmpty($this->obj->get($key));
    }

    public function testGetDefaultParam()
    {

        $this->assertNull($this->obj->get('invalid_key'));
        $this->assertFalse($this->obj->get('invalid_key', false));
        $this->assertEquals(array('test' => 'test'), $this->obj->get('invalid_key', array('test' => 'test')));
    }

  /**
   * @dataProvider provideDefaultData
   */
    public function testHas($data)
    {

        $key = current(array_keys($data));
        $this->obj->add($data);

        $this->assertTrue($this->obj->has($key));
        $this->assertFalse($this->obj->has('invalid_key'));
    }

  /**
   * @dataProvider provideDefaultData
   */
    public function testContains($data)
    {

        $value = current($data);
        $this->obj->add($data);

        $this->assertTrue($this->obj->contains($value));
        $this->assertFalse($this->obj->contains('invalid_value'));
    }

  /**
   * @dataProvider provideDefaultData
   */
    public function testRemove($data)
    {

        $key = current(array_keys($data));
        $this->obj->add($data);

        $this->obj->remove($key);
        $this->assertNull($this->obj->get($key));
    }

  /**
   * @dataProvider provideDefaultData
   */
    public function testCount($data)
    {

        $this->assertEquals(0, $this->obj->count());
        $this->obj->add($data);
        $this->assertEquals(1, $this->obj->count());
    }

    public function testEmpty()
    {

        $this->assertTrue($this->obj->isEmpty());
        $this->assertTrue($this->obj->isEmpty('invalid_key'));

        $this->obj->set('valid_key', 'test');
        $this->assertFalse($this->obj->isEmpty('valid_key'));

        $this->obj->set('valid_key', '');
        $this->assertTrue($this->obj->isEmpty('valid_key'));
    }

    public function testJSON()
    {

        $this->assertJson($this->obj->toJSON());

        $this->obj->set('test', 'test');
        $this->assertJsonStringEqualsJsonString("{\"test\": \"test\"}", $this->obj->toJSON(), 'message');
    }

  /**
   * @dataProvider provideArrayData
   */
    public function testFromObject($data)
    {

        $this->assertInstanceOf('\ECidade\V3\Extension\ParameterBag', ParameterBag::fromObject($data));

        $obj = ParameterBag::fromObject($data);

        $_this = $this;

        array_walk_recursive($obj, function ($value) use ($_this) {
            $_this->assertInstanceOf('\ECidade\V3\Extension\ParameterBag', $value);
        });
    }

    public function testInstanceIterator()
    {
        $this->assertInstanceOf('\ArrayIterator', $this->obj->getIterator());
    }

    public function provideDefaultData()
    {

        $data1 = array(
        'teste' => 'Lorem ipsum Minim ut deserunt.'
        );

        $data2 = array(
        'obj' => (object) array('teste' => 1)
        );

        $data3 = array(
        'field' => array('field' => array('field' => (object) array('teste' => 'teste')))
        );

        return array(
        array($data1),
        array($data2),
        array($data3)
        );
    }

    public function provideArrayData()
    {

        $data1 = array(
        'test' => array()
        );

        $data2 = array(
        'test' => (object) array('teste' => 'teste')
        );

        $data3 = array(
        'test' => array('a' => (object) array('b' => array('c' => (object) array('d' => array()))))
        );

        return array(
        array($data1),
        array($data2),
        array($data3),
        );
    }
}
