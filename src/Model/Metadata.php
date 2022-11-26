<?php

declare(strict_types=1);

namespace CometChat\Chat\Model;

class Metadata implements \ArrayAccess, \JsonSerializable
{
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /**
     * @param string $key
     */
    public function __get($key)
    {
        return $this->data[$key];
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return \json_encode($this->data);
    }
}
