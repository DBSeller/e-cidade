<?php
namespace ECidade\Core\Relatorios\Interfaces;

/**
 * Interface CampoDinamico
 * @package ECidade\Core\Interfaces
 */
interface CampoDinamico
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param $id
     * @return mixed
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getLabel();

    /**
     * @param $label
     * @return mixed
     */
    public function setLabel($label);

    /**
     * @return integer
     */
    public function getWidth();

    /**
     * @param $width
     * @return integer
     */
    public function setWidth($width);

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param $value
     * @return mixed
     */
    public function setValue($value);
}
