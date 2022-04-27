<?php

namespace System;

class Html
{
    /**
     * Application Object 
     * 
     * @var \System\Application
     */
    protected $app;

    /**
     * Html Title
     * @var string 
     */
    private $title;

    /**
     * Html Description
     * @var string 
     */
    private $description;

    /**
     * Html keywords
     * @var string 
     */
    private $keywords;

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
     * set title 
     * 
     * @var string $title
     * 
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * set description 
     * 
     * @var string $description
     * 
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * set keywords 
     * 
     * @var string $keywords
     * 
     * @return void
     */
    public function setkeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * get title 
     *    
     * @return string
     */
    public function getTitle($title)
    {
        return $this->title;
    }

    /**
     * get description 
     *     
     * @return string
     */
    public function getDescription($description)
    {
        return $this->description;
    }

    /**
     * get keywords 
     * 
     * @return string
     */
    public function getkeywords($keywords)
    {
        return $this->keywords;
    }
}
