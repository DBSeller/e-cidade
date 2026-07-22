<?php

namespace ECidade\Task;

use Closure as BaseClosure;
use SuperClosure\SerializableClosure;

/**
 * Class Closure
 * @package ECidade\Task
 */
class Closure extends Base
{
    private $closure;

    public function __construct($closure)
    {
        if ($closure instanceof BaseClosure) {
            $this->closure = new SerializableClosure($closure);
            return;
        }

        $this->closure = $closure;
    }

    /**
     * @return mixed
     */
    public function doRun()
    {
        return call_user_func($this->closure);
    }
}
