<?php

namespace ECidade\Tributario\Library;

abstract class Collection implements \Countable, \Iterator, \ArrayAccess
{
    protected $size;

    protected $position;

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
        return (is_int($offset) && $offset >= 0 && $offset < $this->size);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value) { }

    public function offsetUnset($offset) { }

    public function isEmpty()
    {
        return ($this->size == 0);
    }

    protected abstract function get($index);
}
