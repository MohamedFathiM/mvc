<?php

namespace System;

use PDO;
use PDOException;

class Database
{
    /**
     * Application Object 
     * 
     * @var \System\Application
     */
    private $app;

    /**
     * Connection variable
     * 
     * @var \PDO
     */
    private static $connection;

    /**
     * Table Name 
     * 
     * @var string
     */
    private $table;

    /**
     * Data Container
     * 
     * @var array
     */
    private $data = [];

    /**
     * Bindings Container
     * 
     * @var array
     */
    private $bindings = [];

    /**
     * Last insert id
     * 
     * @var array
     */
    private $lastId;

    /**
     * Wheres 
     * 
     * @var array
     */
    private $wheres = [];

    /**
     * Constructor 
     * 
     * @param \System\Application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        if (!$this->isConnected()) {
            $this->connect();
        }
    }

    /**
     * Determine if there is a connection to DB 
     * 
     * @return bool
     */
    public function isConnected()
    {
        return static::$connection instanceof PDO;
    }

    /**
     * connect to Database
     * 
     * @return void
     */
    private function connect()
    {
        $connectionData = $this->app->file->call('config.php');
        extract($connectionData);
        try {
            static::$connection = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $dbuser, $dbpass);
            static::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            static::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            static::$connection->exec('SET NAMES utf8');
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    /**
     * Get Database Connection Object PDO Object
     * 
     * @return \PDO
     */
    public function connection()
    {
        return static::$connection;
    }

    /**
     * Set Table Name 
     * 
     * @param string $table
     * 
     * @return $this
     */
    public function table($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * get Table 
     * 
     * @param string $table
     */
    public function from($table)
    {
        return $this->table($table);
    }

    /**
     * Set the data that will be stored in database
     * 
     * @param mixed $key
     * @param mixed $value
     * 
     * @return $this
     */
    public function data($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
            $this->addBindings($key);
        } else {
            $this->data[$key] = $value;
            $this->addBindings($value);
        }

        return $this;
    }

    /**
     * Insert Data to Database
     * 
     * @param string $table
     * 
     * @return $this
     */
    public function insert($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = 'INSERT INTO ' . $this->table . ' SET ';
        $sql .= $this->setFields();
        $this->query($sql, $this->bindings);
        $this->lastId = $this->connection()->lastInsertId();

        return $this;
    }

    /**
     * Update Data to Database
     * 
     * @param string $table
     * 
     * @return $this
     */
    public function update($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = 'UPDATE ' . $this->table . ' SET ';
        $sql .= $this->setFields();

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode('', $this->wheres);
        }

        $this->query($sql, $this->bindings);

        return $this;
    }
    /**
     * Set Fields for insert and update
     * 
     * @return string
     */
    public function setFields()
    {
        $sql = '';

        foreach ($this->data as $key => $value) {
            $sql .=  '`' . $key . '` = ? , ';
        }

        $sql = rtrim($sql, ', ');

        return $sql;
    }

    /**
     * Add New Where Clause 
     * 
     * @return $this
     */
    public function where(...$bindings)
    {
        $sql = array_shift($bindings);

        $this->addBindings($bindings);
        $this->wheres[] = $sql;

        return $this;
    }

    /**
     * Get The last insert id 
     * 
     * @return int
     */
    public function lastId()
    {
        return $this->lastId;
    }

    /**
     * Add the given value to Bindings
     * 
     * @param mixed $value
     * 
     * @return void
     */
    private function addBindings($value)
    {
        if (is_array($value)) {
            $this->bindings = array_merge($this->bindings, array_values($value));
        } else {
            $this->bindings[] = $value;
        }
    }

    /**
     * Execute the given sql statement
     * 
     * @return \PDOStatement 
     */
    public function query(...$bindings)
    {
        $sql = array_shift($bindings);

        if (count($bindings) == 1 && is_array($bindings[0])) {
            $bindings = $bindings[0];
        }

        try {
            $query = $this->connection()->prepare($sql);

            foreach ($bindings as $key => $value) {
                $query->bindValue($key + 1, _e($value));
            }

            $query->execute();

            return $query;
        } catch (PDOException $e) {
            pre($sql);
            pre($this->bindings);
            die($e->getMessage());
        }
    }
}
