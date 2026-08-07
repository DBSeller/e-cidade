<?php


namespace ECidade\Core\Relatorios\Campos;

use ECidade\Core\Relatorios\Interfaces\CampoDinamico as CampoDinamicoAlias;

/**
 * Class CampoDinamico
 * @package ECidade\Core\Relatorios\Campos
 */
abstract class CampoDinamico implements CampoDinamicoAlias
{
    /**
     * Label para apresentação no relatório
     * @var string
     */
    protected $label;
    /**
     * identifica o campo
     * @var mixed
     */
    protected $id;
    /**
     * Tamanho default no relatório
     * @var integer
     */
    protected $width;
    /**
     * valor apresentado
     * @var mixed
     */
    protected $value;

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param integer $width
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}
