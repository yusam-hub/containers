<?php

if (!defined('JSON_EXT_CONTENT_TYPE')) {
    define('JSON_EXT_CONTENT_TYPE', 'application/json');
}

if (! function_exists('json_ext_json_encode_unescaped')) {

    function json_ext_json_encode_unescaped(array $value, int $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES): string
    {
        $result = strval(json_encode($value, $flags));
        if (empty($result)) {
            $result = '{}';
        }
        return $result;
    }
}

if (! function_exists('json_ext_ok')) {

    function json_ext_ok(array $data = []): array
    {
        return [
            'status' => 'ok',
            'data' => $data,
        ];
    }
}

if (! function_exists('json_ext_error')) {

    function json_ext_error(string $errorMessage, array $errorData = []): array
    {
        return [
            'status' => 'error',
            'errorMessage' => $errorMessage,
            'errorData' => $errorData,
        ];
    }
}

if (! function_exists('json_ext_throwable')) {

    function json_ext_throwable(\Throwable $e, array $errorData = []): array
    {
        if (method_exists($e, 'getData')) {
            $errorData = array_merge($e->getData(), $errorData);
        }
        return json_ext_error($e->getMessage(), $errorData);
    }
}