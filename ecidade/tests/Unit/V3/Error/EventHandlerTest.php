<?php

namespace ECidade\V3\Error;

use \ECidade\V3\Error\EventHandler;
use \ECidade\V3\Error\Entity;
use Exception;
use \Zend\EventManager\Event as ZendEvent;

class EventHandlerTest extends \PHPUnit_Framework_TestCase
{

    public function testExecuteShouldNotLog()
    {

        $event = new EventHandler();
        $event->config->set('app.error.log', false);
        $entity = new Entity();

        $this->assertFalse($event->execute(new ZendEvent('app.error', null, array($entity))));
    }

    public function testFormatMessageWithEmptyEntity()
    {

        $time = time();
        $event = new EventHandler();
        $mask = $event->config->get('app.error.log.mask');

        $entity = new Entity();
        $entity->setTime($time);

        $expected = strtr($mask, array(
        '{date}' => date('Y-m-d H:i:s', $time),
        '{type}' => 'Unknown PHP error',
        '{message}' => '',
        '{file}' => '',
        '{line}' => '',
        '{trace}' => ''
        ));

        $this->assertEquals($expected, $event->formatMessage($entity));
    }

    public function testFormatMessage()
    {

        $time = time();
        $line = 99;
        $file = 'test';

        $exception = new Exception('Test');
        $entity = EntityFactory::createFromException($exception);
        $entity->setTime($time);
        $entity->setFile($file);
        $entity->setLine($line);
        $entity->setTrace(null);

        $event = new EventHandler();
        $mask = $event->config->get('app.error.log.mask');
        $traceMask = $event->config->get('app.error.log.mask.trace');


        $expected = strtr($mask, array(
        '{date}' => date('Y-m-d H:i:s', $time),
        '{type}' => 'E_ERROR',
        '{message}' => 'Test',
        '{file}' => $file,
        '{line}' => $line,
        '{trace}' => ''
        ));

        $this->assertEquals($expected, $event->formatMessage($entity));
    }
}
