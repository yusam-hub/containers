<?php

namespace YusamHub\JsonExt\Tests;

class DefaultTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testDemoJsonObject()
    {
        $source = '{"id":1,"title":"title","data":"data"}';
        $demoJsonObject = new DemoJsonObject();
        $demoJsonObject->import($source);

        print_r([
            'array' => $demoJsonObject->toArray(),
            'json' => $demoJsonObject->toJson(),
            'data' => $demoJsonObject->data,
        ]);

        $this->assertTrue($demoJsonObject->toJson() === $source);
    }
}