<?php

namespace YusamHub\JsonExt\Tests\Demo;

use YusamHub\JsonExt\JsonObject;

/**
 * @property string|null $data
 */
class DemoJsonObject extends JsonObject
{
    public ?int $id = null;
    public ?string $title = null;
    protected ?string $data = null;
    protected ?string $dataProtected = null;
    private ?string $dataPrivate = null;

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string|null $data
     */
    public function setData(?string $data): void
    {
        $this->data = $data;
    }


}