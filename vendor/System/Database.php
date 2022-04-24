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
}
