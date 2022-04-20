<?php

namespace System\Http;

use System\Application;

class Request
{
    /**
     * Application Object 
     * 
     * @var \System\Application
     */
    private $app;

    /**
     * url 
     * 
     * @var string 
     */
    private $url;

    /**
     * base url 
     * 
     * @var string 
     */
    private $baseUrl;

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
     * prepare url 
     * 
     * @return void
     */
    public function prepareUrl()
    {
        $script =  dirname($this->server('SCRIPT_NAME'));
        $requestUri = $this->server('REQUEST_URI');

        if (strpos($requestUri, '?') !== false) {
            list($requestUri, $queryString) = explode('?', $requestUri);
        }

        $this->url = preg_replace('#^' . $script . '$#', '', $requestUri);
        $this->baseUrl = $this->server('REQUEST_SCHEME') ?? 'Http' . '://' . $this->server('HTTP_HOST') . $script;
    }


    /**
     * get data from $_SERVER
     * 
     * @param string $key
     * @param mixed $defualt
     * 
     * @return mixed 
     */
    public function server($key, $default = null)
    {
        return array_get($_SERVER, $key, $default);
    }

    /**
     * get data from $_GET
     * 
     * @param string $key
     * @param mixed $defualt
     * 
     * @return mixed 
     */
    public function get($key, $default = null)
    {
        return array_get($_GET, $key, $default);
    }

    /**
     * get data from $_POST
     * 
     * @param string $key
     * @param mixed $defualt
     * 
     * @return mixed 
     */
    public function post($key, $default = null)
    {
        return array_get($_POST, $key, $default);
    }

    /**
     * return clean url 
     * 
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * return request method
     * 
     * @return string
     */
    public function method()
    {
        return $this->server('REQUEST_METHOD');
    }

    /**
     * return base url 
     * 
     * @return string
     */
    public function baseUrl()
    {
        return $this->baseUrl;
    }
}
