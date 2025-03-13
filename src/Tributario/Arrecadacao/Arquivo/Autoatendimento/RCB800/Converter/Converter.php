<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 18/02/19
 * Time: 17:17
 */

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB800\Converter;

use ECidade\Tributario\Library\Entity;
abstract class Converter
{
    protected $layout;
    protected $format;

    public function __construct($layout, $format = null)
    {
        $this->layout = $layout;
        $this->format = $format;
    }

    abstract public function build(Entity $entity);
}
