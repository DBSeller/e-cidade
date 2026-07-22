<?php

namespace ECidade\Tributario\Library;

abstract class ArrayCollection extends Collection
{
    protected $collection = array();

    public function __construct(array $collection = array())
    {
        parent::__construct();
        $this->size = count($collection);
        $this->collection = $collection;
    }

    public function get($index)
    {
        return $this->collection[$index];
    }

    public function getAll()
    {
        return $this->collection;
    }

    public function add($value)
    {
        $this->collection[$this->size] = $value;
        $this->size++;
    }
}
