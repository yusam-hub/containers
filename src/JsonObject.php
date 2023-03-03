<?php

namespace YusamHub\JsonExt;

use YusamHub\JsonExt\Interfaces\ArrayableInterface;
use YusamHub\JsonExt\Interfaces\JsonObjectInterface;
use YusamHub\JsonExt\Traits\JsonableTrait;
use YusamHub\JsonExt\Traits\CommonTrait;

class JsonObject implements JsonObjectInterface
{
    use JsonableTrait;
    use CommonTrait;

    /**
     * @param array|string|null $source
     * @param array $filterKeys
     * @return void
     * @throws \ReflectionException
     */
    public function import($source, array $filterKeys = []): void
    {
        if (is_null($source)) return;

        $source = is_array($source) ? $source : (array) json_decode($source, true);

        $refObj = new \ReflectionClass($this);

        foreach($source as $k => $v) {
            if ($refObj->hasProperty($k)) {
                $p = $refObj->getProperty($k);
                if (!$p->isStatic()) {
                    if ($p->isPublic()) {
                        $pv = $p->getValue($this);
                        if ($pv instanceof ArrayableInterface) {
                            $pv->import($v);
                        } else {
                            $p->setValue($this, $v);
                        }
                    } elseif ($p->isPrivate() || $p->isProtected()) {
                        $this->reflectionSetMethodInvoke($k, $v);
                    }
                }
            } else  {
                $this->reflectionSetMethodInvoke($k, $v);
            }
        }
    }

    /**
     * @param array|string|null $keyValuePairs
     * @return bool
     * @throws \ReflectionException
     */
    public function isEqual($keyValuePairs): bool
    {
        if (empty($keyValuePairs)) return false;

        if (is_string($keyValuePairs)) {
            $keyValuePairs = (array) json_decode($keyValuePairs, true);
        }

        if (!is_array($keyValuePairs)) {
            throw new \RuntimeException("Invalid incoming type value, required string or array");
        }

        return empty(array_diff($keyValuePairs, $this->toArray(array_keys($keyValuePairs))));
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

            if (!$property->isStatic()) {

                if ($property->isPublic()) {

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
                } elseif ($property->isPrivate() || $property->isProtected()) {

                    $valueInvoked = $this->reflectionGetMethodInvoke($property->getName(), $isInvoked);
                    if ($isInvoked) {
                        $out[$property->getName()] = $valueInvoked;
                    }

                }

            } //end !$property->isStatic()

        }

        return array_filter($out, function($v, $k) use($filterKeys) {
            return empty($filterKeys) || in_array($k, $filterKeys);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \ReflectionException
     */
    public function __get(string $name)
    {
        return $this->reflectionGetMethodInvoke($name, $isInvoked);
    }

    /**
     * @param string $name
     * @param $value
     * @return void
     * @throws \ReflectionException
     */
    public function __set(string $name, $value): void
    {
        $this->reflectionSetMethodInvoke($name, $value);
    }

    /**
     * @param string $name
     * @param $value
     * @return void
     * @throws \ReflectionException
     */
    private function reflectionSetMethodInvoke(string $name, $value): void
    {
        $refObj = new \ReflectionClass($this);
        $methodName = "set" . ucfirst($name);
        if ($refObj->hasMethod($methodName)) {
            $m = $refObj->getMethod($methodName);
            if ($m->isPublic() && !$m->isStatic()) {
                if ($m->getNumberOfParameters() === 1) {
                    $m->invoke($this, $value);
                }
            }
        }
    }

    /**
     * @param string $name
     * @param $isInvoked
     * @return mixed|null
     * @throws \ReflectionException
     */
    private function reflectionGetMethodInvoke(string $name, &$isInvoked)
    {
        $isInvoked = false;
        $refObj = new \ReflectionClass($this);
        $methodName = "get" . ucfirst($name);
        if ($refObj->hasMethod($methodName)) {
            $m = $refObj->getMethod($methodName);
            if ($m->isPublic() && !$m->isStatic()) {
                if ($m->getNumberOfParameters() === 0) {
                    $isInvoked = true;
                    return $m->invoke($this);
                }
            }
        }
        return null;
    }

}
