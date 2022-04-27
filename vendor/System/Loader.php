<?php


namespace System;


class Loader
{
    /**
     * Application Object 
     * 
     * @var \System\Application
     */
    private $app;

    /**
     * controllers array
     * 
     * @var array $controllers
     */
    private array $controllers = [];

    /**
     * models array
     * 
     * @var array $models
     */
    private array $models = [];

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
     * call the given controller with the given method 
     * and pass the given arguments to the controller method 
     * 
     * @param string $controller 
     * @param string $method
     * @param array $arguments 
     * 
     * @return mixed
     */
    public function action($controller, $method, array $arguments)
    {
        $object = $this->controller($controller);

        return call_user_func([$object, $method], $arguments);
    }

    /**
     * get the full classname of given controller
     * 
     * @param string $controller 
     * 
     * @return string
     */
    public function getControllerName($controller)
    {
        $controller .= 'Controller';
        $controller  = 'App\\Controllers\\' . $controller;

        return str_replace('/', '\\', $controller);
    }

    /**
     * call the given controller 
     * 
     * @param string $controller 
     * 
     * @return object
     */
    public function controller($controller)
    {
        $controller = $this->getControllerName($controller);

        if (!$this->hasController($controller)) {
            $this->addController($controller);
        }

        return $this->getController($controller);
    }

    /**
     * determine if the given controller exists in the controllers container
     * 
     * @param string $controller 
     * 
     * @return bool
     */
    private function hasController($controller)
    {
        return array_key_exists($controller, $this->controllers);
    }

    /**
     * create new object for the given controller and store it 
     * in controllers container
     * 
     * @param string $controller
     * 
     * @return void 
     */
    private function addController($controller)
    {
        $object = new $controller($this->app);

        $this->controllers[$controller] = $object;
    }

    /**
     * Get the controller object
     * 
     * @param string $controller
     * 
     * @return object
     */
    private function getController($controller)
    {
        return $this->controllers[$controller];
    }

    /**
     * call the given model 
     * 
     * @param string $model 
     * 
     * @return object
     */
    public function model($model)
    {
        $model = $this->getModelName($model);

        if (!$this->hasModel($model)) {
            $this->addModel($model);
        }

        return $this->getModel($model);
    }

    public function getModelName($model)
    {
        $model .= 'Model';
        $model  = 'App\\Models\\' . $model;

        return str_replace('/', '\\', $model);
    }
    /**
     * determine if the given model exists in the models container
     * 
     * @param string $model 
     * 
     * @return bool
     */
    private function hasModel($model)
    {
        return array_key_exists($model, $this->models);
    }

    /**
     * create new object for the given model and store it 
     * in models container
     * 
     * @param string $model
     * 
     * @return void 
     */
    private function addModel($model)
    {
        $object = new $model($this->app);

        $this->models[$model] = $object;
    }

    /**
     * Get the model object
     * 
     * @param string $model
     * 
     * @return object
     */
    private function getModel($model)
    {
        return $this->models[$model];
    }
}
