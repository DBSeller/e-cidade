<?php

namespace ECidade\V3\Extension;

use RuntimeException;

class ContainerTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->container = new Container();
    }

  /**
   * @dataProvider provideDefaultData
   */
    public function testContainer($name, $value, $expected = null)
    {
        $this->assertFalse($this->container->has($name));
        $this->assertTrue($this->container->register($name, $value));
        $this->assertTrue($this->container->has($name));
        $this->assertFalse($this->container->isActive($name));
        $this->assertEquals($expected ? $expected : $value, $this->container->get($name));
    }

    public function provideDefaultData()
    {
        return array(
        array('name1', 'string'),
        array('name2', new \stdClass),
        array('name3', function () {
            return 'foo';
        }, 'foo')
        );
    }

  /**
   * @expectedException RuntimeException
   */
    public function testExpectFailOnGet()
    {
        $this->container->get('invalid_name');
    }

  /**
   * @expectedException RuntimeException
   */
    public function testExpectFailOnRegister()
    {
        $this->container->register('foo', 'bar');
        $this->container->get('foo');
        $this->container->register('foo', 'baz');
    }
}
