<?php

namespace ECidade\Tributario\Cadastro\Iptu\Arquivo\Layout;

use \BusinessException;

abstract class Layout
{
    const SEQUENTIAL_LENGTH  = 6;
    const NAME_LENGTH        = 31;
    const DESCRIPTION_LENGTH = 81;
    const SIZE_LENGTH        = 4;

    /**
     * @var array
     *
     * Campos
     */
    protected $fields = array();

    /**
     * @var integer
     *
     * Contador dos blocos
     */
    protected $last  = 0;

    /**
     * @var integer
     *
     * Início do primeiro bloco
     */
    protected $start = null;

    /**
     * @var integer
     *
     * Fim do último bloco
     */
    protected $end = null;

    /**
     * @var integer
     *
     * Tamanho total do bloco
     */
    protected $length = 0;

    /**
     * Construtor de classe
     */ 
    public function __construct()
    {
        foreach($this->fields as $field => $properties) {

            $size = ((int)$properties['size']);
            $this->length += $size;
        }
    }

    /**
     * @return array|null
     *
     * Retorna os campos
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return integer|null
     *
     * Retorna o contador dos blocos
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @return integer|null
     *
     * Retorna o início do primeiro bloco
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @return integer|null
     *
     * Retorna o fim do último bloco
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return integer|null
     *
     * Retorna o tamanho total do bloco
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param array|null
     *
     * Define os campos
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param integer|null
     *
     * Define o contador dos blocos
     */
    public function setLast($last)
    {
        $this->last = $last;
        return $this;
    }

    /**
     * @param integer|null
     *
     * Define o início do primeiro bloco
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @param integer|null
     *
     * Retorna o fim do último bloco
     */
    protected function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @param integer|null
     *
     * Define o tamanho total do bloco
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @param string $field
     * @return bollean
     *
     * Retorna o tamanho do campo
     */
    protected function validate($field)
    {
        if(empty($this->fields[$field])) {
            throw new BusinessException("Campo {$field} não encontrado.");
        }

        return true;
    }

    /**
     * @param string $field
     * @return integer
     *
     * Retorna o tamanho do campo
     */
    public function getSize($field)
    {
        $this->validate($field);
        return $this->fields[$field]['size'];
    }

    /**
     * @param string $field
     * @return integer
     *
     * Retorna o string do campo
     */
    public function getName($field)
    {
        $this->validate($field);
        return $this->fields[$field]['name'];
    }

    /**
     * @param string $field
     * @return string
     *
     * Retorna a descrição do campo
     */
    public function getDescription($field)
    {
        $this->validate($field);
        return $this->fields[$field]['description'];
    }

    /**
     * @param integer $counter
     * @return string
     *
     * Retorna o layout
     */
    public function get($counter)
    {
        if(empty($counter)) {
            throw new BusinessException('Informe contador de inicio.'. get_class());
        }
        
        if($this->getStart() === null) {
            throw new BusinessException('Informe o início dos blocos.'. get_class());
        }

        $start = $this->getStart();

        $r = $this->build($counter, $start);

        $this->setLast($r->counter);
        $this->setEnd($r->end);

        return $r->layout;
    }

    protected function build($counter, $start, $closureSearchVariable = null)
    {
        $regex         = '/(\{\$\w+\})/';
        $end           = null;
        $l             = '';
        $fieldsIterate = 0;
        foreach($this->fields as $field => $properties) {

            $f   = array();
            $f[] = str_pad(substr($counter, 0, self::SEQUENTIAL_LENGTH),  self::SEQUENTIAL_LENGTH);
            
            $name = $properties['name'];
            if($closureSearchVariable !== null && preg_match_all($regex, $name, $found)) {
                $found = array_shift($found);
                $replaced = array_map($closureSearchVariable, $found);
                $name = str_replace($found, $replaced, $name);
            }
            $name = substr($name,   0, self::NAME_LENGTH);
            $name = str_pad($name, self::NAME_LENGTH);
            $f[]  = $name;
            
            $description = $properties['description'];
            if($closureSearchVariable !== null && preg_match_all($regex, $description, $found)) {
                $found = array_shift($found);
                $replaced = array_map($closureSearchVariable, $found);
                $description = str_replace($found, $replaced, $description);
            }
            $description = substr($description,   0, self::DESCRIPTION_LENGTH);
            $description = str_pad($description, self::DESCRIPTION_LENGTH);
            $f[]         = $description;
            
            $size = substr($properties['size'],  0, self::SIZE_LENGTH);
            $size = str_pad($size, self::SIZE_LENGTH, '0', STR_PAD_LEFT);
            $size = str_pad($size, (self::SIZE_LENGTH + 1));//, ' ', STR_PAD_BOTH);
            $f[]  = $size;
            
            if($fieldsIterate > 0) {
                $start = $end;
                $start++;
            }
            $start = str_pad($start,  self::SIZE_LENGTH, '0', STR_PAD_LEFT);
            $start = str_pad($start, (self::SIZE_LENGTH + 1));//, ' ', STR_PAD_BOTH);
            $f[]   = $start;
            
            $end = (($start + $size)-1);
            $end = substr($end,  0, self::SIZE_LENGTH);
            $end = str_pad($end, self::SIZE_LENGTH, '0', STR_PAD_LEFT);
            $f[] = $end;

            $l .= implode('| ', $f);
            $l .= PHP_EOL;
            $counter++;
            $fieldsIterate++;
        }

        return (object)array(
            'layout'   => $l
            ,'counter' => $counter
            ,'end'     => $end
        );
    }
}
