<?php

namespace Ipoo\Core\Database;

use PDO;
use PDOException;

class TableBuilder
{
    /**
     * PDO instance for database connection
     * @var PDO $pdo
     */
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
     * @return void
     * @throws PDOException
     */
    public static function create(string $tableName, callable $callback)
    {
        self::initPDO();

        $table = new Table($tableName);

        $callback($table);

        try {
            $statement = self::$pdo->prepare($table->getQuery());
            $statement->execute();
        } catch (PDOException $e) {
            echo "Error creating the table: " . $e->getMessage();
        }
    }

    /**
     * Drops the table.
     * 
     * @param string $tableName
     * @return void
     * @throws PDOException
     */
    public static function drop(string $tableName)
    {
        self::initPDO();

        try {
            $statement = self::$pdo->prepare("DROP TABLE {$tableName}");
            $statement->execute();
        } catch (PDOException $e) {
            echo "Error dropping the table: " . $e->getMessage();
        }
    }

    /**
     * Drops the table if it exists.
     * 
     * @param string $tableName
     * @return void
     * @throws PDOException
     */
    public function dropIfExists(string $tableName)
    {
        self::initPDO();

        try {
            $statement = self::$pdo->prepare("DROP TABLE IF EXISTS {$tableName}");
            $statement->execute();
        } catch (PDOException $e) {
            echo "Error dropping the table: " . $e->getMessage();
        }
    }

    /**
     * Initializes the PDO instance
     * 
     * @return void
     * @throws PDOException
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
