<?php

namespace YusamHub\JsonExt\Interfaces;

/**
 * Interface ArrayableInterface
 */
interface ArrayableInterface
{
    /**
     * @param array|string|null $source
     * @return void
     */
    public function import($source): void;

    /**
     * @param array $filterKeys
     * @return array
     */
    public function toArray(array $filterKeys = []): array;
}