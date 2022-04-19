<?php

namespace System;

class File
{
    const DS = DIRECTORY_SEPARATOR;
    /**
     * Root File 
     * 
     * @var string
     */
    private $root;

    /**
     * Constructor 
     */

    public function __construct($root)
    {
        $this->root = $root;
    }

    /**
     * Determine if file is exits
     * 
     * @param string $file
     * @return void
     */
    public function exists($file)
    {
        return file_exists($this->to($file));
    }

    /**
     * require the given file
     * 
     * @param string $file
     * @return void 
     */

    public function require($file)
    {
        require $this->to($file);
    }

    /**
     *  Generate Full Path to the given path in vendor folder
     * @param string $path 
     * @return string
     */

    public function toVendor($path)
    {
        return $this->to("vendor/" . $path);
    }

    /**
     * generate full path to given path
     * 
     * @param string $path
     * @return string
     */

    public function to($path)
    {
        return $this->root . static::DS . str_replace(['/', '\\'], static::DS, $path);
    }
}
