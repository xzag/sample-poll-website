<?php

namespace app\core;

abstract class Configurable
{
    private static function _wrap($data, callable $callback = null)
    {
        if (!is_callable($callback)) {
            return $data;
        }

        if (!is_array($data)) {
            return $callback($data);
        }

        foreach ($data as $key => $value) {
            $data[$key] = $callback($value);
        }

        return $data;
    }

    /**
     * @param array $data
     * @return static
     */
    public static function make(array $data, callable $callback = null)
    {
        $obj = new static();
        foreach ($data as $key => $value) {
            if (property_exists($obj, $key)) {
                $obj->$key = static::_wrap($value, $callback);
            }
        }
        return $obj;
    }

    /**
     * @param array $data
     * @return static[]
     */
    public static function collection(array $data)
    {
        return array_map(function ($item) {
            return static::make($item);
        }, $data);
    }

    public function toArray()
    {
        $properties = get_object_vars($this);
        return $properties;
    }
}
