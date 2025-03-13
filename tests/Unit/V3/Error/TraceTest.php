<?php

namespace ECidade\V3\Error;

use ECidade\V3\Error\Trace;
use Exception;

class TraceTeste extends \PHPUnit_Framework_TestCase
{

    public function testTraceFilter()
    {

        try {
            throw new Exception();
        } catch (Exception $e) {
            $exception = $e;
            $expected = $e->getTrace();
        }

        $trace = new Trace($exception);
        $data = $trace->filter(function ($obj) {
            return $obj;
        });

        $traceWithoutExcpetion = new Trace();
        $dataWithoutExcpetion = $traceWithoutExcpetion->filter(function ($obj) {
            return $obj;
        });


        $this->assertSame($expected, $data);
        $this->assertSame($expected, $trace->getData());
        $this->assertSame($expected, $dataWithoutExcpetion);
        $this->assertSame($expected, $traceWithoutExcpetion->getData());
    }
}
