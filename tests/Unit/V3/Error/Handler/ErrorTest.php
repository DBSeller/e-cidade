<?php

namespace ECidade\V3\Error\Handler;

use \ECidade\V3\Error\Handler\Error;
use PHPUnit_Framework_TestCase;

class ErrorTest extends PHPUnit_Framework_TestCase
{

    public function testHandle()
    {

        $type = E_ERROR;
        $message = 'Test message';
        $file = 'testfile';
        $line = 9;

        $entity = Error::handle($type, $message, $file, $line, array());
        $this->assertEquals($type, $entity->getType());
        $this->assertEquals($message, $entity->getMessage());
        $this->assertEquals($file, $entity->getFile());
        $this->assertEquals($line, $entity->getLine());
    }

    public function testHandleWithoutReporting()
    {

        $type = E_ERROR;
        $message = 'Test message';
        $file = 'testfile';
        $line = 9;

        error_reporting(null);

        $this->assertFalse(Error::handle($type, $message, $file, $line, array()));
    }
}
