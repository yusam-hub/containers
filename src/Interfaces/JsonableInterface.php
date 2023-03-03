<?php

namespace YusamHub\JsonExt\Interfaces;

/**
 * Interface JsonableInterface
 */
interface JsonableInterface
{
    /**
     * @param string|null $source
     * @param array $filterKeys
     * @return void
     */
    public function fromJson(?string $source, array $filterKeys = []): void;
    /**
     * @param array $filterKeys
     * @param int $jsonOptions
     * @return string
     */
    public function toJson(array $filterKeys = [], int $jsonOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): string;
}