<?php


namespace System;

use System\Application;

class Cookie
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
     * set key/value into cookie
     * 
     * @param string|int $key
     * @param mixed $value
     * @param int $hours
     */
    public function set($key, $value, $hours = 1800)
    {
        setcookie($key, $value, time() + $hours * 3600, '', '', true);
    }

    /**
     * get value using its key from cookie
     * 
     * @param int|string $key
     * @param mixed $default
     */
    public function get($key, $default = null)
    {
        return array_get($_COOKIE, $key, $default);
    }

    /**
     * check if cookie has value 
     * 
     * @param int|string $key
     */
    public function has($key)
    {
        return array_key_exists($key, $_COOKIE);
    }

    /**
     * remove key from cookie 
     * 
     * @param $key
     */
    public function remove($key)
    {
        setcookie($key, null, -1);
        unset($_SESSION[$key]);
    }

    /**
     * get all data from cookie
     */
    public function all()
    {
        return $_COOKIE;
    }

    /**
     * destroy cookie
     */
    public function destroy()
    {
        foreach (array_keys($this->all()) as $key) {
            $this->remove($key);
        }

        unset($_SESSION);
    }
}
