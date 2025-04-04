<?php

namespace Ipoo\Src\Traits;

use Ipoo\Src\BaseClass;

trait ConditionQueryMethods
{
    /**
     * Creates the where statement adding the column name, operator and binds the searched value
     * 
     * @param string $column
     * @param mixed $operator
     * @param mixed $searchValue
     */
    public function where(string $column, mixed $operator, mixed $searchValue = null)
    {
        $this->where .= $this->where === '' ? " WHERE " : " AND ";

        $this->where .= $column;

        $param = ":$column";

        // Check that the operator is included in the operators list
        if (in_array(strtoupper($operator), BaseClass::OPERATORS)) {
            // Concat the operator
            $this->where .= " $operator ";

            if (is_array($searchValue)) {
                if (strtoupper($operator) === "IN") {
                    $searchValue = "(" . implode(", ", $searchValue) . ")";
                } else {
                    $searchValue = implode(" AND ", $searchValue);
                }
            }

            $this->bindings[$param] = $searchValue;
        } else {
            // Default the operator to "="
            $this->where .= " = ";
            $this->bindings[$param] = $operator;
        }
        $this->where .= $param;

        return $this;
    }

    /**
     * Similar to where() but adds the OR WHERE condition
     * 
     * @param string $column
     * @param mixed $operator
     * @param mixed $searchValue
     * 
     * @return self
     */
    public function orWhere(string $column, mixed $operator, mixed $searchValue = null): self
    {
        $this->where .= $this->where === '' ? " WHERE " : " OR ";

        $this->where .= $column;

        $param = ":$column";

        // Check that the operator is included in the operators list
        if (in_array(strtoupper($operator), BaseClass::OPERATORS)) {
            // Concat the operator
            $this->where .= " $operator ";

            if (is_array($searchValue)) {
                $searchValue = "(" . implode(", ", $searchValue) . ")";
            }

            $this->bindings[$param] = $searchValue;
        } else {
            // Default the operator to "="
            $this->where .= " = ";
            $this->bindings[$param] = $operator;
        }
        $this->where .= $param;

        return $this;
    }

    /**
     * Creates the statement of the limit of the results expected from the query
     * 
     * @param int $limit
     * 
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = " LIMIT $limit";

        return $this;
    }

    /**
     * Creates the statement of the order of the results
     * 
     * @param string $column
     * @param string $direction
     * 
     * @return self
     */
    public function orderBy(string $column = "id", string $direction = "ASC"): self
    {
        if ($this->orderBy !== "") {
            $this->orderBy .= ", {$column} {$direction}";
        } else {
            $this->orderBy = " ORDER BY {$column} {$direction}";
        }

        return $this;
    }

    /**
     * Creates the statement of the grouping of the results
     * 
     * @param string $column
     * 
     * @return self
     */
    public function groupBy(string $column)
    {
        $this->groupBy = " GROUP BY {$column}";
        return $this;
    }
}
