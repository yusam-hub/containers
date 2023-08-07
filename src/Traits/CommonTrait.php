<?php

namespace YusamHub\JsonExt\Traits;

trait CommonTrait
{
    /**
     * @param array|null $source
     * @param array $filterKeys
     * @return void
     * @throws \ReflectionException
     */
    public function fromArray(?array $source, array $filterKeys = []): void
    {
        $this->import($source, $filterKeys);
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }

    /**
     * @param array $data
     * @return void
     * @throws \ReflectionException
     */
    public function __unserialize(array $data): void
    {
        $this->fromArray($data);
    }
}