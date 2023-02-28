<?php

namespace YusamHub\JsonExt;

use YusamHub\JsonExt\Base\ArrayAccessBase;
use YusamHub\JsonExt\Interfaces\ArrayableInterface;

/**
 * Class AttributesContainer
 *
 */
class AttributesObject extends ArrayAccessBase
{
    /**
     * @var array
     */
    protected array $attributes = [];

    /**
     * AttributesContainer constructor.
     * @param array|string|null $source
     */
    public function __construct($source)
    {
        $this->init($source);
    }

    /**
     * @param array|string|null $source
     * @return void
     */
    protected function init($source): void
    {
        $this->import($source);
    }

    /**
     * @param array|string|null $source
     * @return void
     */
    public function import($source): void
    {
        if (is_null($source)) return;

        $source = is_array($source) ? $source : (array) json_decode($source, true);

        $this->assignAttributes($source);
    }

    /**
     * @param array|string|null $attributes
     * @return void
     */
    public function assignAttributes($attributes): void
    {
        if (is_null($attributes)) return;

        $attributes = is_array($attributes) ? $attributes : (array) json_decode($attributes, true);

        foreach ($attributes as $name => $value) {
            $this->setAttribute($name, $value);
        }
    }

    /**
     * @param array|string|null $attributes
     * @return void
     */
    public function assignAttributesWithOffsetSet($attributes): void
    {
        if (is_null($attributes)) return;

        $attributes = is_array($attributes) ? $attributes : (array) json_decode($attributes, true);

        foreach ($attributes as $name => $value) {
            $this->offsetSet($name, $value);
        }
    }

    /**
     * @param array $filterKeys
     * @return array
     */
    public function toArray(array $filterKeys = []): array
    {
        $out = [];

        foreach($this->attributes as $name => $value) {

            if ($value instanceof ArrayableInterface) {
                $value = $value->toArray();
            }

            $out[$name] = $value;
        }

        return array_filter($out, function($v, $k) use($filterKeys) {
            return empty($filterKeys) || in_array($k, $filterKeys);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param array $attributes
     * @return void
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setAttribute(string $name, $value): void
    {
        $this->offsetSet($name, $value);
    }

    /**
     * @param string $name
     * @param mixed|null|Closure $default
     * @return mixed
     */
    public function getAttribute(string $name, $default = null)
    {
        if (is_callable($default)) {
            $default = $default();
        }

        if ($this->attributeExists($name)) {
            return $this->offsetGet($name);
        }

        return $default;
    }

    /**
     * @param $name
     * @return bool
     */
    public function attributeExists($name): bool
    {
        return $this->offsetExists($name);
    }

    /**
     * @return void
     */
    public function clearAttributes(): void
    {
        $this->attributes = [];
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return in_array($offset, array_keys($this->attributes));
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->attributes[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->attributes[$offset]);
    }

}