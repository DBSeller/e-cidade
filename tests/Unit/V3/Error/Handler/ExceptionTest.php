<?php

namespace ECidade\V3\Error\Handler;

use ECidade\V3\Error\Sanitizer;

/**
 * Class ExceptionTest
 * @package ECidade\V3\Error\Handler
 */
class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testHandle()
    {
        $type = E_ERROR;
        $message = 'Test message';
        $file = Sanitizer::clearPath(__FILE__);

        try {
            throw new \Exception($message);
        } catch (\Exception $e) {
            $exception = $e;
        }

        $expected = sprintf(
            "Uncaught exception 'Exception' with message '%s' in %s on line %s",
            $message,
            Sanitizer::clearPath($exception->getFile()),
            $exception->getLine()
        );

        $entity = Exception::handle($exception);
        $this->assertEquals($type, $entity->getType());
        $this->assertEquals($expected, $entity->getMessage());
        $this->assertEquals($file, $entity->getFile());
    }
}
