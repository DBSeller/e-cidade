<?php

namespace ECidade\V3\Error;

use \ECidade\V3\Error\Entity;
use \ECidade\V3\Error\Trace;
use \ECidade\V3\Error\EntityFactory;

class EntityTest extends \PHPUnit_Framework_TestCase
{

    public function testGenerateId()
    {

        $expectedId = 'd41d8cd98f00b204e9800998ecf8427e';

        $entity = new Entity();
        $entity->generateId();

        $this->assertEquals($expectedId, $entity->getId());
    }

  /**
   * @dataProvider provideErrorTypes
   */
    public function testStringTypes($type, $message)
    {

        $entity = new Entity();
        $entity->setType($type);
        $this->assertEquals($message, $entity->getTypeAsString());
    }

    public function provideErrorTypes()
    {

        return array(
        array(E_ERROR            , 'E_ERROR'),
        array(E_WARNING          , 'E_WARNING'),
        array(E_PARSE            , 'E_PARSE'),
        array(E_NOTICE           , 'E_NOTICE'),
        array(E_CORE_ERROR       , 'E_CORE_ERROR'),
        array(E_CORE_WARNING     , 'E_CORE_WARNING'),
        array(E_COMPILE_ERROR    , 'E_COMPILE_ERROR'),
        array(E_COMPILE_WARNING  , 'E_COMPILE_WARNING'),
        array(E_USER_ERROR       , 'E_USER_ERROR'),
        array(E_USER_WARNING     , 'E_USER_WARNING'),
        array(E_USER_NOTICE      , 'E_USER_NOTICE'),
        array(E_STRICT           , 'E_STRICT'),
        array(E_RECOVERABLE_ERROR, 'E_RECOVERABLE_ERROR'),
        array(E_DEPRECATED       , 'E_DEPRECATED'),
        array(E_USER_DEPRECATED  , 'E_USER_DEPRECATED') ,
        array(null, 'Unknown PHP error')
        );
    }

    public function testToArray()
    {

        $expected1 = array(
        'id' => 'd41d8cd98f00b204e9800998ecf8427e',
        'type' => '',
        'suppress' => '',
        'message' => '',
        'file' => '',
        'line' => '',
        'time' => '',
        'code' => '',
        'trace' => array()
        );

        $entity = EntityFactory::create();
        $this->assertEquals($expected1, $entity->toArray());

        $trace = new Trace();

        $expected2 = array(
        'id' => '1',
        'type' => '2',
        'suppress' => '3',
        'message' => '4',
        'file' => '5',
        'line' => '6',
        'time' => '7',
        'code' => '8',
        'trace' => $trace->getSanitizedData()
        );


        $entity = EntityFactory::create('2', '3', '4', '5', '6', '7', $trace);
        $entity->setId('1');
        $entity->setCode('8');
        $this->assertEquals($expected2, $entity->toArray());
    }
}
