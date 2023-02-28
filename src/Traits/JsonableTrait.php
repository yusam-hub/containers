<?php

namespace YusamHub\JsonExt\Traits;

trait JsonableTrait
{
    /**
     * @param callable $callable
     * @return int
     * @throws \ReflectionException
     */
    protected function getFunctionNumberOfParameters(callable $callable): int
    {
        $CReflection = is_array($callable) ? new \ReflectionMethod($callable[0], $callable[1]) : new \ReflectionFunction($callable);
        return $CReflection->getNumberOfParameters();
    }

    /**
     * @param array $filterKeys
     * @param int $jsonOptions
     * @return string
     * @throws \ReflectionException
     */
    public function toJson(array $filterKeys = [], int $jsonOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): string
    {
        $out = [];

        $method = 'toArray';

        if (method_exists($this, $method)) {

            $n = $this->getFunctionNumberOfParameters([$this, $method]);

            if ($n === 1) {
                $out = call_user_func_array([$this, $method], [$filterKeys]);
            } elseif($n === 0) {
                $out = call_user_func_array([$this, $method], []);
            }

        } else {

            $out = (array) $this;

            $out = array_filter($out, function($v, $k) use($filterKeys) {
                return empty($filterKeys) || in_array($k, $filterKeys);
            }, ARRAY_FILTER_USE_BOTH);

        }

        return json_encode($out, $jsonOptions);
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
