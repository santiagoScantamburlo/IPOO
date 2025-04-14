<?php

namespace Ipoo\Core\Database;

class Table
{
    protected string $name = "";

    protected array $columns = [];

    public function __construct(string $_name)
    {
        $this->name = $_name;
    }

    /**
     * Returns the query that is going to be executed
     */
    public function getQuery()
    {
        $returnQuery = "CREATE TABLE IF NOT EXISTS {$this->name} (\n";
        $returnQuery .= implode(",\n", $this->columns);
        $returnQuery .= ");";

        return $returnQuery;
    }

    /**
     * Sets the column id as primary
     */
    public function id()
    {
        $column = new Column('id');

        $column->id();

        $this->columns[] = $column;
    }

    /**
     * Creates a column of type VARCHAR
     * 
     * @param string $columnName
     * @param int $size
     */
    public function varchar(string $columnName, int $size = 255): Column
    {
        $column = new Column($columnName);

        $column->varchar($size);

        $this->columns[] = $column;

        return $column;
    }

    /**
     * Creates a column of type INT
     * 
     * @param string $columnName
     * @param int $size
     * @return Column
     */
    public function int(string $columnName, int $size = 11): Column
    {
        $column = new Column($columnName);

        $column->int($size);

        $this->columns[] = $column;

        return $column;
    }

    /**
     * Creates a column of type BOOL
     * 
     * @param string $columnName
     */
    public function bool(string $columnName): Column
    {
        $column = new Column($columnName);

        $column->bool();

        $this->columns[] = $column;

        return $column;
    }

    /**
     * Creates a column of type TIMESTAMP
     * 
     * @param string $columnName
     */
    public function timestamp(string $columnName): Column
    {
        $column = new Column($columnName);

        $column->timestamp();

        $this->columns[] = $column;

        return $column;
    }

    /**
     * Creates the `created_at` and `updated_at` columns
     * 
     * @return void
     */
    public function timestamps()
    {
        $createdAtColumn = new Column('created_at');
        $updatedAtColumn = new Column('updated_at');

        $createdAtColumn->timestamp()->default('NOW()');
        $updatedAtColumn->timestamp()->default('NOW()');

        $this->columns[] = $createdAtColumn;
        $this->columns[] = $updatedAtColumn;
    }

    /**
     * Creates the `deleted_at` column
     * 
     * @return void
     */
    public function softDelete()
    {
        $deletedAtColumn = new Column('deleted_at');

        $deletedAtColumn->timestamp()->nullable()->default("NULL");

        $this->columns[] = $deletedAtColumn;
    }
}
