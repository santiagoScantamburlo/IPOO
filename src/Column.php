<?php

namespace Ipoo\Src;

class Column
{
    protected string $query = "";

    protected string $name = "";

    /**
     * Receives the column name and sets it
     */
    public function __construct(string $_name)
    {
        $this->name = $_name;
    }

    /**
     * Defines the primary key
     */
    public function id()
    {
        $this->query = "{$this->name} INT NOT NULL AUTO_INCREMENT, PRIMARY KEY ({$this->name})";
    }

    /**
     * Sets the column type to VARCHAR
     * 
     * @param int $size
     * 
     * @return $this
     */
    public function varchar(int $size)
    {
        $this->query = "{$this->name} VARCHAR({$size}) NOT NULL";

        return $this;
    }

    /**
     * Removes the "NOT NULL" condition
     * 
     * @return $this
     */
    public function nullable()
    {
        $this->query = str_replace(" NOT NULL", "", $this->query);

        return $this;
    }

    /**
     * Sets the column type to BOOL
     * 
     * @return $this
     */
    public function bool()
    {
        $this->query = "{$this->name} BOOL NOT NULL";

        return $this;
    }

    /**
     * Sets the column type to TIMESTAMP
     * 
     * @return $this
     */
    public function timestamp()
    {
        $this->query = "{$this->name} TIMESTAMP NOT NULL";

        return $this;
    }

    /**
     * Sets the default value of the column
     * 
     * @param mixed $defaultValue
     * 
     * @return $this
     */
    public function default(mixed $defaultValue)
    {
        if (gettype($defaultValue) === "string" && !in_array($defaultValue, ["NULL", "NOW()"])) {
            $defaultValue = "'{$defaultValue}'";
        }

        $this->query .= " DEFAULT {$defaultValue}";

        return $this;
    }

    /**
     * Returns the query string for the column creation
     */
    public function __toString()
    {
        return $this->query;
    }
}
