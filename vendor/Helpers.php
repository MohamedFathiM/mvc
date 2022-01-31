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
