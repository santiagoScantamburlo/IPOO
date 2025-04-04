<?php

namespace Ipoo\Src\Traits;

trait ModificationQueryMethods
{
    /**
     * Deletes the current instance of an object from the database
     * 
     * @return bool
     */
    public function delete(): bool
    {
        if (method_exists($this, "softDelete")) {
            return $this->softDelete();
        }

        $this->query = "DELETE FROM {$this->table} WHERE id = :id";

        $this->bindings[":id"] = $this->id;

        if ($this->withTransaction) {
            $this->pdo->beginTransaction();
        }

        $statement = $this->pdo->prepare($this->query);

        $result = $statement->execute($this->bindings);

        $this->reset();

        if ($result && $this->withTransaction) {
            $this->pdo->commit();
            return true;
        }

        if (!$result && $this->withTransaction) {
            $this->pdo->rollBack();
            return false;
        }

        return $result;
    }

    /**
     * Updates the data of the columns passed as parameter as an associative array
     * 
     * @return bool
     */
    public function update(array $data): bool
    {
        $set = [];

        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
            $this->bindings[":{$key}"] = $value;
        }

        $this->query = "UPDATE {$this->table} SET " . implode(", ", $set);

        if ($this->withTransaction) {
            $this->pdo->beginTransaction();
        }

        $statement = $this->pdo->prepare($this->query . $this->where);

        $result = $statement->execute($this->bindings);

        $this->reset();

        if ($result && $this->withTransaction) {
            $this->pdo->commit();
            return true;
        }

        if (!$result && $this->withTransaction) {
            $this->pdo->rollBack();
            return false;
        }

        return $result;
    }
}
