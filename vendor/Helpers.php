<?php

if (!function_exists("pre")) {
    /**
     * visualize the given data
     * 
     * @param mixed $var
     */
    function pre($var)
    {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }
}

if (!function_exists("array_get")) {
    /**
     * get value from an array 
     * 
     * @param array $array 
     * @param int|string $key
     * @param mixed $default
     *  
     */

    function array_get($array, $key, $default = null)
    {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}
