<?php

namespace ECidade\V3\Error;

use \ECidade\V3\Error\EntityFactory;

class EntityFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateFromException()
    {
    
        $exception = new \Exception('Test');

        $entity = EntityFactory::createFromException($exception);

        $this->assertInstanceOf('\ECidade\V3\Error\Entity', $entity);
    }
}
