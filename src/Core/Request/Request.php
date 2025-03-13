<?php


namespace ECidade\Core\Request;

use Exception;
use Symfony\Component\HttpFoundation\ParameterBag;
use Valitron\Validator;

/**
 * Class Request
 * @example Exemplo de uso da classe de validação: https://github.com/vlucas/valitron
 * @package ECidade\Core\Request
 */
class Request extends ParameterBag
{
    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * Request constructor.
     * @param array $parameters
     * @throws Exception
     */
    public function __construct(array $parameters = array())
    {
        $this->validator = new Validator($parameters);
        $this->validator->rules($this->rules);
        if (!$this->validator->validate()) {
            /**
             * @todo ver como retornar os erros ($this->validator->errors())
             */
            throw new Exception("Request inválida.");
        }

        parent::__construct($parameters);
    }

    /**
     * @return Validator
     */
    public function getValidator()
    {
        return $this->validator;
    }
}
