<?php

namespace ECidade\V3\Extension;

use \ECidade\V3\Extension\Registry as R;

class RegistryTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSetHas()
    {

        $this->assertNull(R::get('invalid'));
        $this->assertFalse(R::get('invalid', false));

        R::set('test', 'teste');
        $this->assertTrue(R::has('test'));
        $this->assertFalse(R::has('invalid_key'));
    }
}
