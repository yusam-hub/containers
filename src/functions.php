<?php

if (! function_exists('json_ext_json_encode_unescaped')) {

    /**
     * @param array $value
     * @param int $flags
     * @return string
     */
    function json_ext_json_encode_unescaped(array $value, int $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): string
    {
        $result = strval(json_encode($value, $flags));
        if (empty($result)) {
            $result = '{}';
        }
        return $result;
    }
}