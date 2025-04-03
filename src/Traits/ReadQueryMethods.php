<?php

namespace Ipoo\Src\Traits;

use Exception;

trait ReadQueryMethods
{
    /**
     * Creates the select statement adding the name of the colums selected. Defaults to select all columns
     * 
     * @param string|string[] $columns
     * 
     * @return self
     */
    public function select(string|array $columns = "*"): self
    {
        if (is_array($columns)) {
            $columns = implode(", ", $columns);
        }

        $this->query = "SELECT $columns FROM " . $this->table;

        return $this;
    }

    /**
     * Looks for the record with the specified id and returns it as an instance of the class. If not found, returns null
     * 
     * @param int $id
     * 
     * @return ?self
     */
    public function find(int $id): ?self
    {
        $result = $this->where('id', $id)->limit(1)->get();

        return $result[0] ?? null;
    }

    /**
     * Looks for the record with the specified id and returs it as an instance of the class. If not found, throws an exception
     * 
     * @param int $id
     * 
     * @return ?self
     */
    public function findOrFail(int $id): ?self
    {
        $result = $this->find($id);

        if (!$result) {
            throw new Exception("Record with id $id not found in $this->table table");
        }

        return $result;
    }

    /**
     * Returns the first record of the specified query
     * 
     * @return self
     */
    public function first(): ?self
    {
        $record = $this->limit(1)->get();

        return $record[0] ?? null;
    }

    /**
     * Returns the last record of the specified query based on the id
     * 
     * @return self
     */
    public function last(): ?self
    {
        return $this->orderBy('id', 'DESC')->first();
    }
}
