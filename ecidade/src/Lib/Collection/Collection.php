<?php

namespace ECidade\Lib\Collection;

abstract class Collection implements \Countable, \Iterator, \ArrayAccess
{
    protected $size;

    protected $position;

    protected $itens = array();

    public function __construct()
    {
        $this->size = 0;
        $this->position = 0;
    }

    public function count()
    {
        return $this->size;
    }

    public function current()
    {
        return $this->offsetGet($this->position);
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
    }

    public function previous()
    {
        $this->position--;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return $this->position < $this->size;
    }

    public function offsetExists($offset)
    {
        $offset = (int) $offset;
        return (is_int($offset) && $offset >= 0 && $offset < $this->size && !empty($itens[$offset]));
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (empty($this->itens[$offset])) {
            $this->size++;
        }

        $this->itens[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (empty($this->itens[$offset])) {
            return;
        }

        unset($this->itens[$offset]);
        $this->size--;
    }

    public function isEmpty()
    {
        return ($this->size == 0);
    }

    protected function get($index)
    {
        return !empty($this->itens[$index]) ? $this->itens[$index] : null;
    }

    abstract protected function set($itens);
}
