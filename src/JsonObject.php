<?php

namespace YusamHub\JsonExt;

use YusamHub\JsonExt\Interfaces\ArrayableInterface;
use YusamHub\JsonExt\Interfaces\JsonObjectInterface;
use YusamHub\JsonExt\Traits\JsonableTrait;

class JsonObject implements JsonObjectInterface
{
    use JsonableTrait;

    /**
     * @param array|string|null $source
     * @return void
     * @throws \ReflectionException
     */
    public function import($source): void
    {
        if (is_null($source)) return;

        $source = is_array($source) ? $source : (array) json_decode($source, true);

        $refObj = new \ReflectionClass($this);

        foreach($source as $k => $v) {
            if ($refObj->hasProperty($k)) {
                $p = $refObj->getProperty($k);
                if ($p->isPublic() && !$p->isStatic()) {
                    $pv = $p->getValue($this);
                    if ($pv instanceof ArrayableInterface) {
                        $pv->import($v);
                    } else {
                        $p->setValue($this, $v);
                    }
                }
            } elseif ($refObj->hasProperty('_' . $k)) {
                $this->reflectionGetPropertyInvoke($refObj, $k, $v);
            }
        }
    }

    /**
     * @param array $filterKeys
     * @return array
     * @throws \ReflectionException
     */
    public function toArray(array $filterKeys = []): array
    {
        $out = [];

        $refObj = new \ReflectionClass($this);

        $properties = $refObj->getProperties();

        foreach ($properties as $property) {

            if ($property->isPublic() && !$property->isStatic()) {

                if ($property->hasType()) {

                    $v = $property->getValue($this);

                    if ($v instanceof ArrayableInterface) {

                        $out[$property->getName()] = $v->toArray();

                    } elseif (is_array($v)) {

                        if (!empty($v)) {
                            foreach ($v as $vKey => $vValue) {

                                if ($vValue instanceof ArrayableInterface) {

                                    $out[$property->getName()][$vKey] = $vValue->toArray();

                                } else {

                                    $out[$property->getName()][$vKey] = $vValue;

                                }
                            }
                        } else {
                            $out[$property->getName()] = [];
                        }

                    } else {

                        $out[$property->getName()] = $v;

                    }
                }
            } elseif (($property->isPrivate() || $property->isProtected()) && !$property->isStatic()) {

                $methodName = "get" . ltrim(ucfirst($property->getName()),'_');

                if ($refObj->hasMethod($methodName)) {

                    $m = $refObj->getMethod($methodName);

                    if ($m->isPublic()) {

                        if ($m->getNumberOfParameters() === 0) {

                            $out[ltrim($property->getName(),'_')] = $m->invoke($this);

                        }
                    }
                }
            }
        }

        return array_filter($out, function($v, $k) use($filterKeys) {
            return empty($filterKeys) || in_array($k, $filterKeys);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param string $name
     * @return mixed|void
     * @throws \ReflectionException
     */
    public function __get(string $name)
    {
        $refObj = new \ReflectionClass($this);
        if ($refObj->hasProperty('_' . $name)) {
            $p = $refObj->getProperty('_' . $name);
            if (($p->isPrivate() || $p->isProtected()) && !$p->isStatic()) {
                $methodName = "get" . ucfirst($name);
                if ($refObj->hasMethod($methodName)) {
                    $m = $refObj->getMethod($methodName);
                    if ($m->isPublic()) {
                        if ($m->getNumberOfParameters() === 0) {
                            return $m->invoke($this);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param string $name
     * @param $value
     * @return void
     * @throws \ReflectionException
     */
    public function __set(string $name, $value): void
    {
        $refObj = new \ReflectionClass($this);
        if ($refObj->hasProperty('_' . $name)) {
            $this->reflectionGetPropertyInvoke($refObj, $name, $value);
        }
    }

    /**
     * @param \ReflectionClass $refObj
     * @param string $name
     * @param $value
     * @return void
     * @throws \ReflectionException
     */
    private function reflectionGetPropertyInvoke(\ReflectionClass $refObj, string $name, $value): void
    {
        $p = $refObj->getProperty('_' . $name);
        if (($p->isPrivate() || $p->isProtected()) && !$p->isStatic()) {
            $methodName = "set" . ucfirst($name);
            if ($refObj->hasMethod($methodName)) {
                $m = $refObj->getMethod($methodName);
                if ($m->isPublic()) {
                    if ($m->getNumberOfParameters() === 1) {
                        $m->invoke($this, $value);
                    }
                }
            }
        }
    }
}
