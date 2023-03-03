<?php

namespace YusamHub\JsonExt\Tests\Demo;

use YusamHub\JsonExt\JsonArray;

/**
 * @method DemoJsonObject addRow($source = null)
 * @method DemoJsonObject getRow(int $index)
 * @method DemoJsonObject delRow(int $index)
 * @method DemoJsonObject|null firstRow()
 * @method DemoJsonObject|null findFirst($keyValuePairs)
 * @method DemoJsonArray findAll($keyValuePairs)
 * @method DemoJsonObject current()
 * @method int key()
 */
class DemoJsonArray extends JsonArray
{
    public function __construct()
    {
        parent::__construct(DemoJsonObject::class);
    }
}