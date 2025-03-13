<?php

namespace ECidade\Tributario\Arrecadacao\Arquivo\Autoatendimento\Layout;

use BusinessException;

abstract class Layout
{
    /**
     * @var array
     *
     * Campos
     */
    protected $fields = array();

    /**
     * Construtor de classe
     */
    public function __construct()
    {
    }

    /**
     * @param string $field
     * @return bool
     * @throws BusinessException
     */
    protected function validate($field)
    {
        if(empty($this->fields[$field])) {
            throw new BusinessException("Campo {$field} não encontrado.");
        }

        return true;
    }

    /**
     * Retorna o tamanho do campo
     *
     * @param string $field
     * @return mixed
     * @throws BusinessException
     */
    public function getSize($field)
    {
        $this->validate($field);
        return $this->fields[$field]['size'];
    }

    /**
     * Retorna o valor defaulto para o campo
     *
     * @param string $field
     * @return mixed|null
     * @throws BusinessException
     */
    public function getDefault($field)
    {
        $this->validate($field);
        return !empty($this->fields[$field]['default']) ? $this->fields[$field]['default'] : null;
    }

    /**
     * Retorna a posição inicial do registro na linha
     * @param $field
     * @return mixed
     * @throws BusinessException
     */
    public function getFieldPosition($field)
    {
        if(!isset($this->fields[$field]['position'])) {
            throw new BusinessException("Campo {$field} sem a propriedade 'position' setada.");
        }

        return $this->fields[$field]['position'];
    }
}
