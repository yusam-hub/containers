<?php

namespace YusamHub\JsonExt\Interfaces;

interface JsonSerializable
{
    public function jsonSerialize(): array;
}