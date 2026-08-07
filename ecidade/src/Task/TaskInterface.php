<?php
namespace ECidade\Task;

interface TaskInterface
{
    const STATE_IDLE = 1;
    const STATE_RUNNING = 2;
    const STATE_FAIL = 4;
    const STATE_SUCCESS = 8;

    public function run();

    public function output($output = null);
    public function errorHandler($errorHandler = null);
    public function result($result = null);
    public function elapsed($elapsed = null);
    public function addError($error);
    public function errors($errors = null);
}
