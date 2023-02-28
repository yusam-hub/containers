<?php

namespace YusamHub\JsonExt\Interfaces;

/**
 * Interface JsonableInterface
 */
interface JsonableInterface
{
    /**
     * @param array $filterKeys
     * @param int $jsonOptions
     * @return string
     */
    public function toJson(array $filterKeys = [], int $jsonOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): string;

    /**
     * @return string
     */
    public function __toString(): string;
}