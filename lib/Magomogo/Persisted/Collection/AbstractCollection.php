<?php

namespace Magomogo\Persisted\Collection;

use Magomogo\Persisted\AbstractProperties;
use Magomogo\Persisted\Exception;
use Magomogo\Persisted\ModelInterface;

abstract class AbstractCollection implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * @var AbstractProperties[]
     */
    protected $items = array();

    /**
     * @param AbstractProperties $properties
     * @return ModelInterface
     */
    abstract protected function constructModel($properties);

    /**
     * @return AbstractProperties
     */
    abstract public function constructProperties();

    public function propertiesOperation($function)
    {
        $this->items = $function($this->items);
    }

    /**
     * @return ModelInterface[]
     */
    public function asArray()
    {
        $models = array();
        foreach ($this->items as $offset => $properties) {
            $models[$offset] = $this->constructModel($properties);
        }
        return $models;
    }

//----------------------------------------------------------------------------------------------------------------------

    public function count()
    {
        return count($this->items);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param mixed $offset
     * @return ModelInterface
     */
    public function offsetGet($offset)
    {
        return $this->constructModel($this->items[$offset]);
    }

    /**
     * @param mixed $offset
     * @param MemberInterface $value
     */
    public function offsetSet($offset, $value)
    {
        $value->appendToCollection($this, $offset);
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->asArray());
    }
}
