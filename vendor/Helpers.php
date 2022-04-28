<?php

use System\Application;

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

if (!function_exists("_e")) {
    /**
     * Escape the given value
     * 
     * @param string $value
     *  
     * @return string  
     */

    function _e($value)
    {
        return htmlspecialchars($value);
    }
}

if (!function_exists("assets")) {
    /**
     * Generate the full path for the given path 
     * in public directory
     * 
     * @param string $path
     *  
     * @return string  
     */
    function assets($path)
    {
        $app = Application::getInstance();

        return $app->url->link('public/' . $path);
    }
}
