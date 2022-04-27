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
     * Selects 
     * 
     * @var array
     */
    private $selects = [];

    /**
     * joins 
     * 
     * @var array
     */
    private $joins = [];

    /**
     * limit 
     * 
     * @var int
     */
    private $limit;

    /**
     * offset 
     * 
     * @var int
     */
    private $offset;

    /**
     * Total Rows 
     * 
     * @var int
     */
    private $rows = 0;

    /**
     * order by 
     * 
     * @var array
     */
    private $orderBy = [];


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
     * Set Select Clause 
     * 
     * @param string $select 
     * 
     * @return $this
     */
    public function select($select)
    {
        $this->select[] = $select;

        return $this;
    }

    /**
     * Set Select Clause 
     * 
     * @param string $select 
     * 
     * @return $this
     */
    public function join($join)
    {
        $this->joins[] = $join;

        return $this;
    }

    /**
     * Set Limit And Offset 
     * 
     * @param int $limit 
     * @param int $offset
     * 
     * @return $this
     */
    public function limit($limit, $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = $offset;

        return $this;
    }

    /**
     * Set Limit And Offset 
     * 
     * @param int $limit 
     * @param int $offset
     * 
     * @return $this
     */
    public function orderBy($orderBy, $sort = 'ASC')
    {
        $this->orderBy = [$orderBy, $sort];

        return $this;
    }

    /**
     * Fetch Table 
     * this will return only one record
     * 
     * @param string $table
     * 
     * @return \stdClass | null 
     */
    public function fetch($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = $this->fetchStatement();
        $result = $this->query($sql, $this->bindings)->fetch();
        $this->reset();

        return $result;
    }

    /**
     * Fetch all records from Table 
     * this will return only one record
     * 
     * @param string $table
     * 
     * @return array
     */
    public function fetchAll($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = $this->fetchStatement();
        $query = $this->query($sql, $this->bindings);
        $this->rows = $query->rowCount();
        $results = $query->fetchAll();
        $this->reset();

        return $results;
    }

    /**
     * get total rows from last fetch all statement
     * 
     * @return int
     */
    public function rows()
    {
        return $this->rows;
    }

    /**
     * prepare select statement 
     * 
     * @return string
     */
    private function fetchStatement()
    {
        $sql = 'SELECT ';

        if ($this->selects) {
            $sql .= implode(', ', $this->selects);
        } else {
            $sql .= '*';
        }

        $sql .= ' FROM ' . $this->table . ' ';

        if ($this->joins) {
            $sql .= implode(' ', $this->joins);
        }

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }

        if ($this->limit) {
            $sql .= ' LIMIT ' . $this->limit;
        }

        if ($this->offset) {
            $sql .= ' OFFSET ' . $this->offset;
        }

        if ($this->orderBy) {
            $sql .= ' ORDER BY ' . implode(' ', $this->orderBy);
        }

        return $sql;
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

        $this->reset();

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
        $this->reset();

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
            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }

        $this->query($sql, $this->bindings);
        $this->reset();

        return $this;
    }

    /**
     * Delete Data from Database
     * 
     * @param string $table
     * 
     * @return $this
     */
    public function delete($table = null)
    {
        if ($table) {
            $this->table($table);
        }

        $sql = 'DELETE FROM ' . $this->table . ' ';

        if ($this->wheres) {
            $sql .= ' WHERE ' . implode(' ', $this->wheres);
        }

        $this->query($sql, $this->bindings);
        $this->reset();

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

    /**
     * Reset All Data 
     * 
     * @return void
     */
    public function reset()
    {
        $this->limit = null;
        $this->offset = null;
        $this->table = null;
        $this->data = [];
        $this->selects = [];
        $this->joins = [];
        $this->wheres = [];
        $this->orderBy = [];
        $this->bindings = [];
    }
}
