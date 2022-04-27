<?php

namespace System;

abstract class Model
{
    /**
     * Application Object 
     * 
     * @var \System\Application
     */
    protected $app;

    /**
     * name of table
     * 
     * @var string
     */
    protected $table;

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

    /**
     * @param string $method
     * @param array $args
     * 
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array([$this->app->db, $method], $args);
    }
    /**
     * get the all records of current table
     * 
     * @return array
     */
    public function all()
    {
        return $this->fetchAll($this->table);
    }

    /**
     * get the record with id 
     * 
     * @param int $id
     * 
     * @return \StdClass | null
     */
    public function get($id)
    {
        return $this->where('id = ?', $id)->fetch($this->table);
    }
}
