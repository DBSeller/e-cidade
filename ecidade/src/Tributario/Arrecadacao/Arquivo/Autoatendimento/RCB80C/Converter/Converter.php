<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 18/02/19
 * Time: 17:17
 */

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\RCB80C\Converter;

use ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Layout\Layout;

abstract class Converter
{
    protected $layout;
    protected $format;

    public function __construct(Layout $layout, $format = null)
    {
        $this->layout = $layout;
        $this->format = $format;
    }
}
