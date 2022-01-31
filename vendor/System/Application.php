<?php

namespace System;

class Application
{
    /**
     * Container 
     * @var array
     */
    private $container = [];

    /* Constructor 
     * 
     * @param \System\File $file
     */
    public function __construct(File $file)
    {
        $this->share('file', $file);
        $this->registerClasses();
        $this->loadHelpers();
    }

    /**
     * Register Classes in spl auto load register
     * 
     * @return void
     */
    private function registerClasses()
    {
        spl_autoload_register([$this, "load"]);
    }

    /**
     * Load Helpers file
     * 
     * @return void
     */
    public function loadHelpers()
    {
        $this->file->require($this->file->toVendor("Helpers.php"));
    }
    /**
     * Load Class Through autoloading
     * 
     * @param string $class
     * @return void
     */
    public function load($class)
    {
        if (strpos($class, "App") === 0) {
            $file = $this->file->to($class . ".php");
        } else {
            $file = $this->file->toVendor($class . ".php");
        }

        if ($this->file->exists($file))
            $this->file->require($file);
    }

    public function get($key)
    {
        return isset($this->container[$key]) ? $this->container[$key] : null;
    }

    /**
     * Share the given key|value through Application
     * 
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function share($key, $value)
    {
        $this->container[$key] = $value;
    }

    /**
     * get shred value dynamically
     * 
     * @param string $key 
     * @return mixed
     */

    public function __get($key)
    {
        return $this->get($key);
    }
}
