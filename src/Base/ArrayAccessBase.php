<?php

namespace YusamHub\JsonExt\Base;

/**
 * Class ArrayAccessContainer
 */
abstract class ArrayAccessBase extends JsonableBase implements \ArrayAccess
{
    /**
     * @param mixed $offset
     * @return bool
     */
    abstract public function offsetExists($offset): bool;

    /**
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function __set(string $key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @param string $key
     * @return void
     */
    public function __unset(string $key)
    {
        $this->offsetUnset($key);
    }
}