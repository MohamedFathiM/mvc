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
     * start session 
     */
    public function start()
    {
        ini_set('session.use_only_cookies', 1);

        if (!session_id()) {
            session_start();
        }
    }

    /**
     * set key/value into session
     * 
     * @param string|int $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * get value using its key from session
     * 
     * @param int|string $key
     * @param mixed $default
     */
    public function get($key, $default = null)
    {
        return array_get($_SESSION, $key, $default);
    }

    /**
     * check if session has value 
     * 
     * @param int|string $key
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * remove key from session 
     * 
     * @param $key
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * get value from session and delete key 
     * 
     * @param int|string $key
     */
    public function pull($key)
    {
        $value = $this->get($key);
        $this->remove($key);

        return $value;
    }


    /**
     * get all data from session
     */
    public function all()
    {
        return $_SESSION;
    }

    /**
     * destroy session
     */
    public function destroy()
    {
        session_destroy();

        unset($_SESSION);
    }
}
