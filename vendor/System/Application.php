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
     * Run The Application
     * 
     * @return void
     */
    public function run()
    {
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

    /**
     * get the property from container
     * 
     * @param string $key
     * @return bool
     */
    public function get($key)
    {
        if (!$this->isSharing($key)) {
            if (!$this->isCoreAlias($key)) {
                die(ucfirst($key) . ' is Not Found in Application');
            }

            $this->share($key, $this->createNewCoreObject($key));
        }

        return  $this->container[$key];
    }


    /**
     * check if the property is sharing
     * 
     * @param $key
     */
    private function isSharing($key)
    {
        return isset($this->container[$key]);
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
     * check if key in core aliases
     * 
     * @return bool
     */
    private function isCoreAlias($alias)
    {
        $coreClasses = $this->coreClasses();

        return isset($coreClasses[$alias]);
    }

    /**
     * create new object from core class 
     * 
     * @param string $alias
     * @return object
     */
    private function createNewCoreObject($alias)
    {
        $coreClasses = $this->coreClasses();
        $object      = $coreClasses[$alias];

        return new $object($this);
    }


    /**
     * get shred value dynamically
     * 
     * @param string $key 
     * @return mixed
     */
    private function coreClasses()
    {
        return [
            'request'   => 'System\\Http\\Request',
            'response'  => 'System\\Http\\Response',
            'session'   => 'System\\Session',
            'cookie'    => 'System\\Cookie',
            'load'      => 'System\\Loader',
            'html'      => 'System\\Html',
            'db'        => 'System\\Database',
            'view'      => 'System\\View\\ViewFactory'
        ];
    }

    public function __get($key)
    {
        return $this->get($key);
    }
}
