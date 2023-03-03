<?php

namespace YusamHub\JsonExt\Tests;

use YusamHub\JsonExt\Tests\Demo\DemoJsonArray;
use YusamHub\JsonExt\Tests\Demo\DemoJsonObject;

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
            'data' => $demoJsonObject->data,
            'isEqual1' => $demoJsonObject->isEqual($source),
            'isEqual2' => $demoJsonObject->isEqual($demoJsonObject->toArray()),
            'isNotEqual' => $demoJsonObject->isEqual(['id' => null])
        ]);

        $this->assertTrue($demoJsonObject->toJson() === $source);
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function testDemoJsonArray()
    {
        $demoJsonArray = new DemoJsonArray();
        $demoJsonArray->addRow('{"id":1,"title":"title1","data":"data1"}')->data = 'data1-1-1-1';
        $demoJsonArray->addRow('{"id":2,"title":"title2","data":"data2"}');
        $demoJsonArray->addRow('{"id":3,"title":"title3","data":"data3"}');

        $source = $demoJsonArray->toJson();
        $demoJsonArray->clearRows();
        $demoJsonArray->import($source);

        $demoJsonArray->findFirst(['id' => 2])->data = 'data2-2-2-2-2';

        print_r([
            'array' => $demoJsonArray->toArray(),
        ]);

        $newJsonArray = $demoJsonArray->findAll([
            'id' => 3
        ]);

        print_r([
            'array' => $newJsonArray->toArray(),
        ]);

        $this->assertTrue(true);
    }


}