<?php

namespace YusamHub\JsonExt\Interfaces;

/**
 * Interface ArrayableInterface
 */
interface ArrayableInterface
{
    /**
     * @param array|null $source
     * @param array $filterKeys
     * @return void
     */
    public function fromArray(?array $source, array $filterKeys = []): void;

    /**
     * @param array $filterKeys
     * @return array
     */
    public function toArray(array $filterKeys = []): array;
}