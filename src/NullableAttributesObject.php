<?php

namespace YusamHub\JsonExt;

/**
 * Если не найден аттрибут, то возвращаем всегда null
 * Class NullableAttributesObject
 */
class NullableAttributesObject extends AttributesObject
{
    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        if ($this->attributeExists($offset)) {
            return parent::offsetGet($offset);
        }

        return null;
    }
}