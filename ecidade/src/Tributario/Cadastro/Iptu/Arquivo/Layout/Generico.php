<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \DateTime;
use \BusinessException;

final class Generico extends Layout
{
    /**
     * @var string|null
     *
     * Nome do layout
     */
    private $name;
    
    /**
     * @var string|null
     *
     * Descrição do layout
     */
    private $description;

    /**
     * @var integer|null
     *
     * Variável contem o tamanho do campo
     */
    private $size;

    public function __construct ($name, $description, $size)
    {
        if(empty($size)) {
            throw BusinessException("Informe o tamanho do campo.");
        }

        $this->name        = $name;
        $this->description = $description;
        $this->size        = $size;

        $this->fields = array(
            'DEFAULT' => array(
                'name'         => $this->name
                ,'description' => $this->description
                ,'size'        => $this->size
            )
        );
    }
}
