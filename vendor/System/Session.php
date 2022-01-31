<?php


namespace System;

use System\Application;

class Session
{
    /**
     * Application Object 
     * 
     * @var \System\Application
     */
    private $app;

    /**
     * Constructor 
     * 
     * @param \System\Application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 
     * 
     */

    public function set($key, $value)
    {
        echo $key . "=> " . $value;
    }
}
