<?php

namespace Ipoo\Src;

use PDO;
use PDOException;

class TableBuilder
{
    protected static PDO $pdo;

    public function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST;

        if (defined('DB_PORT') && DB_PORT !== '3306') {
            $dsn .= ';port=' . DB_PORT;
        }

        $dsn .= ';dbname=' . DB_NAME;

        try {
            $this->pdo = new PDO(
                $dsn,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]
            );
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }

    /**
     * Receives the table name that will be created and the function that creates all the columns. After that executes the statement that creates the table
     * 
     * @param string $tableName
     * @param callable $callback
     */
    public static function create(string $tableName, callable $callback)
    {
        self::initPDO();

        $table = new Table($tableName);

        $callback($table);

        try {
            // echo $table->getQuery();
            $statement = self::$pdo->prepare($table->getQuery());
            $statement->execute();
        } catch (PDOException $e) {
            echo "Error creating the table: " . $e->getMessage();
        }
    }

    /**
     * Initializes the PDO instance
     */
    protected static function initPDO()
    {
        $dsn = 'mysql:host=' . DB_HOST;

        if (defined('DB_PORT') && DB_PORT !== '3306') {
            $dsn .= ';port=' . DB_PORT;
        }

        $dsn .= ';dbname=' . DB_NAME;

        try {
            self::$pdo = new PDO(
                $dsn,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]
            );
        } catch (PDOException $e) {
            throw new PDOException('Database connection failed: ' . $e->getMessage());
        }
    }
}
