<?php

namespace System;

abstract class Controller
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
     * get shared application objects dynamically
     * 
     * @param string $key 
     * 
     * @return mixed
     */
    public function __get($key)
    {
        return $this->app->get($key);
    }
}
