<?php

namespace System;

class Route
{
    /**
     * Application Object 
     * 
     * @var \System\Application
     */
    private $app;

    /**
     * Application Routes
     * 
     * @var array
     */
    private $routes = [];

    /**
     * not Found url 
     * 
     * @var string
     */
    private $notFoundUrl;

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
     * set not found url 
     * 
     * @param string $url
     * 
     * @return void
     */
    public function notFound($url)
    {
        $this->notFoundUrl = $url;
    }


    /**
     * Add New Route
     * 
     * @param string $url
     * @param string $action 
     * @param string $requestMethod
     * 
     * @return void
     */
    public function add($url, $action, $requestMethod = 'GET')
    {
        $route = [
            'url' => $url,
            'pattern' => $this->generatePattern($url),
            'action'  => $this->getAction($action),
            'method'  => strtoupper($requestMethod)
        ];

        $this->routes[] = $route;
    }

    /**
     * generate the regex pattern for the given url
     * 
     * @param string $url
     * 
     * @return string
     */
    private function generatePattern($url)
    {
        $pattern = '#^';
        $pattern .= str_replace([':text', ':id'], ['([a-zA-Z0-9-]+)', '(\d+)'], $url);
        $pattern .= '$#';

        return $pattern;
    }

    /**
     * return the proper action 
     * 
     *@param string $url
     *
     * @return string 
     */
    private function getAction($url)
    {
        $action = str_replace('/', '\\', $url);

        return (strpos($url, '@') !== false) ? $action : $action . '@index';
    }

    /**
     * get proper Route
     * 
     * @return array
     */
    public function getProperRoute()
    {
        foreach ($this->routes as $route) {
            if ($this->isMatching($route['pattern'])) {
                $arguments = $this->getArgumentsFrom($route['pattern']);

                list($controller, $method) = explode('@', $route['action']);

                return [$controller, $method, $arguments];
            }
        }
    }

    /**
     * Determine if the given pattern matches the current request url
     * 
     * @param string $pattern 
     * 
     * @return bool 
     */
    private function isMatching($pattern)
    {
        return preg_match($pattern, $this->app->request->url());
    }

    /**
     * get arguments from the given pattern matches the current request url
     * 
     * @param string $pattern 
     * 
     * @return array 
     */
    public function getArgumentsFrom($pattern)
    {
        preg_match($pattern, $this->app->request->url(), $matches);

        array_shift($matches);

        return $matches;
    }
}
