<?php

namespace ECidade\V3\Error\Handler;

use \ECidade\V3\Error\Handler\Shutdown;

class ShutdownTest extends \PHPUnit_Framework_TestCase
{

    public function testHandleExpectNothing()
    {

      // trigger an warning
        trigger_error('Test', E_USER_WARNING);
        $this->assertNull(Shutdown::handle());
    }
}
