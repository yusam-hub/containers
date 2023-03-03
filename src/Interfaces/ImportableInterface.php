<?php

namespace YusamHub\JsonExt\Interfaces;

interface ImportableInterface
{
    /**
     * @param array|string|null $source
     * @param array $filterKeys
     * @return void
     */
    public function import($source, array $filterKeys = []): void;
}