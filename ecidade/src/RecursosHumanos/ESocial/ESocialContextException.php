<?php
namespace ECidade\RecursosHumanos\ESocial;

class ESocialContextException extends \Exception
{
    /**
     * @var mixed
     */
    private $context;

    public function __construct($message = '', $code = 500, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }
}
