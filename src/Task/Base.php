<?php
namespace ECidade\Task;

abstract class Base implements TaskInterface
{
    private $state = TaskInterface::STATE_IDLE;
    private $output;
    private $elapsed;
    private $result;
    private $errorHandler;
    private $errors = array();

    public function state($state = null)
    {
        if ($state !== null) {
            $this->state = $state;
        }

        return $this->state;
    }

    public function output($output = null)
    {
        if ($output !== null) {
            $this->output = $output;
        }

        return $this->output;
    }

    public function errorHandler($errorHandler = null)
    {
        if ($errorHandler !== null) {
            $this->errorHandler = $errorHandler;
        }
        return $this->errorHandler;
    }

    public function result($result = null)
    {
        if ($result !== null) {
            $this->result = $result;
        }
        return $this->result;
    }

    public function elapsed($elapsed = null)
    {
        if ($elapsed !== null) {
            $this->elapsed = $elapsed;
        }
        return $this->elapsed;
    }

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function errors($errors = null)
    {
        if ($errors !== null) {
            $this->errors = $errors;
        }
        return $this->errors;
    }

    public function run()
    {
        $this->result = $this->doRun();
    }

    abstract function doRun();
}
