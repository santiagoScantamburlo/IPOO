<?php

namespace Ipoo\Core\Traits;

trait WriteQueryMethods
{
    /**
     * Evaluates the data received and executes the corresponding insert function depending on the data received
     * 
     * @param array|array<string, mixed> $data
     * 
     * @return bool
     */
    public function insert(array $data): bool
    {
        // If it was specified to run as a transaction, start it
        if ($this->withTransaction) {
            $this->pdo->beginTransaction();
        }

        // If it is an associative array
        if (array_keys($data) !== range(0, count($data) - 1)) {
            $result = $this->insertSingle($data);
        } else {
            // If it is an array with multiple associative arrays
            $result = $this->insertMultiple($data);
        }

        // If the insertion was executed correctly and it was specified to run as a transaction, commit the changes
        if ($result && $this->withTransaction) {
            $this->pdo->commit();
            return true;
        }

        // If the insertion failed and it was specified to run as a transaction, undo the changes
        if (!$result && $this->withTransaction) {
            $this->pdo->rollBack();
            return false;
        }

        $this->reset();
        return $result;
    }

    /**
     * Creates and executes the statement for a single record insert
     * 
     * @param array<string, mixed> $data
     * 
     * @return bool
     */
    protected function insertSingle(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $this->query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->pdo->prepare($this->query);
        return $stmt->execute($data);
    }

    /**
     * Creates and executes the statement for multiple records insertion.
     * 
     * @param array $records
     * 
     * @return bool
     */
    protected function insertMultiple(array $records): bool
    {
        if (empty($records)) {
            return false;
        }

        // Get columns from first record
        $columns = array_keys($records[0]);
        $columnsList = implode(', ', $columns);

        // Build placeholders for all records
        $placeholders = [];
        $allValues = [];
        $paramCounter = 0;

        foreach ($records as $record) {
            $recordPlaceholders = [];
            foreach ($columns as $column) {
                $paramName = ":{$column}_{$paramCounter}";
                $recordPlaceholders[] = $paramName;
                $allValues[$paramName] = $record[$column] ?? null;
            }
            $placeholders[] = '(' . implode(', ', $recordPlaceholders) . ')';
            $paramCounter++;
        }

        $placeholdersList = implode(', ', $placeholders);

        $this->query = "INSERT INTO {$this->table} ({$columnsList}) VALUES {$placeholdersList}";

        $stmt = $this->pdo->prepare($this->query);
        return $stmt->execute($allValues);
    }

    /**
     * Stores the current instance of the object
     * 
     * @return bool
     */
    public function save()
    {
        if (!is_null($this->id)) {
            return $this->where('id', $this->id)->update($this->toArray(includeHidden: true));
        }

        return $this->insert($this->toArray(includeHidden: true));
    }
}
