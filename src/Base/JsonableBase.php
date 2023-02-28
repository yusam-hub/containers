<?php

namespace YusamHub\JsonExt\Base;

use YusamHub\JsonExt\Interfaces\ArrayableInterface;
use YusamHub\JsonExt\Interfaces\JsonableInterface;

/**
 * Class JsonableContainer
 */
abstract class JsonableBase implements ArrayableInterface, JsonableInterface, \JsonSerializable
{
    /**
     * @return string
     */
    public function iAmClass(): string
    {
        return static::class;
    }

    /**
     * @return $this|object|null
     */
    public function iAm()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @param array $filterKeys
     * @param int $jsonOptions
     * @return string
     */
    public function toJson(array $filterKeys = [], int $jsonOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): string
    {
        $out = $this->jsonSerialize();

        $out = array_filter($out, function($v, $k) use($filterKeys) {
            return empty($filterKeys) || in_array($k, $filterKeys);
        }, ARRAY_FILTER_USE_BOTH);

        return json_encode($out, $jsonOptions);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function __serialize()
    {
        return $this->jsonSerialize();
    }

    /**
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data)
    {
        $this->import($data);
    }
}