<?php

namespace YusamHub\JsonExt\Interfaces;

interface JsonObjectInterface extends ArrayableInterface, JsonableInterface, ImportableInterface, \JsonSerializable
{
    /**
     * @param array|string|null $keyValuePairs
     * @return bool
     */
    public function isEqual($keyValuePairs): bool;
}
