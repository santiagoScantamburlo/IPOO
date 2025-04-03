<?php

namespace Ipoo\Src;

use Ipoo\Src\Traits\ConditionQueryMethods;
use Ipoo\Src\Traits\ReadQueryMethods;
use Ipoo\Src\Traits\WriteQueryMethods;
use PDO;
use PDOException;

class BaseClass
{
    use WriteQueryMethods, ReadQueryMethods, ConditionQueryMethods;

    /**
     * Object to connect and query the database
     * 
     * @var PDO $pdo
     */
    protected ?PDO $pdo = null;

    /**
     * Name of the table that is going to be queried
     * 
     * @var string $table
     */
    protected string $table = "";

    /**
     * Query string that is going to be executed
     * 
     * @var string $query
     */
    protected string $query = '';

    /**
     * Query string that is going to be added to filter in the DB
     * 
     * @var string $where
     */
    protected string $where = '';

    /**
     * Query string that is going to be added to limit the amount of results
     * 
     * @var string $limit
     */
    protected string $limit = '';

    /**
     * Query string that is going to be added to order the data
     * 
     * @var string $orderBy
     */
    protected string $orderBy = '';

    /**
     * Query string that is going to be added to group the data
     * 
     * @var string $groupBy
     */
    protected string $groupBy = '';

    /**
     * Flag that is going to begin transactions, commits and rollbacks
     * 
     * @var bool $withTransaction
     */
    protected bool $withTransaction = false;

    /**
     * Flag that is going to filter soft deleted records
     * 
     * @var bool $withDeleted
     */
    protected bool $withDeleted = false;

    /**
     * Array that will contain the bindings to the query executions once they are prepared
     * 
     * @var array|array<string, mixed> $bindings
     */
    protected array $bindings = [];
    /**
     * All class fields with their values
     * 
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * All the attributes of the class that will be used to fill the data array
     * 
     * @var string[]
     */
    protected array $attributes = [];

    /**
     * Hidden properties that won't be shown in the __toString() method or in the get() method
     * 
     * @var string[]
     */
    protected array $hidden = [];

    /**
     * Unique attribute of the class that will be used to identify the object and make comparisons between them
     * 
     * @var string[]
     */
    protected array $unique = [];

    /**
     * ACCESSORS are not mandatory as the get(), __get() and __toString() methods will be able to find the variables as long as they are not declared as hidden
     * In case you want to perform some kind of modification to the variable, you can use them to return the mutated value.
     */

    /**
     * MUTATORS are not mandatory either as the __set() and fill() method will be able to find and set those variables
     */

    /**
     * CREATION METHODS:
     * - Constructor: __construct(array $_data = [])
     * - Static method: create(array $data)
     */

    public const OPERATORS = ['=', '<>', 'like'];

    /**
     * Constructor method that will be used to set the values of the attributes. It will be called when the class is instantiated.
     * It will receive an array with the values of the attributes and it will set them in the data array.
     */
    public function __construct(array $_data = [])
    {
        if ($this->table !== "") {
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
        foreach ($this->attributes as $attribute) {
            $this->data[$attribute] = $_data[$attribute] ?? null;
        }
    }

    /**
     * Return a new instanc of the class. Similar to using the constructor but without needing to call new Instance().
     * 
     * @param array<string, mixed> $data
     * 
     * @return static
     */
    public static function create(array $data = [])
    {
        return new static($data);
    }


    /**
     * MAGIC METHODS:
     * - __get(string $attribute): mixed
     * - __set(string $attribute, mixed $value): void
     * - __toString(): string
     */

    /**
     * Magic function to access the dynamic variables
     */
    public function __get(string $attribute): mixed
    {
        if ($this->isHidden($attribute)) return null;

        return $this->data[$attribute] ?? null;
    }

    /**
     * Magic function to set the dynamic variables
     */
    public function __set(string $attribute, mixed $value): void
    {
        $this->data[$attribute] = $value;
    }

    /**
     * Gets all the non private and protected attributes of a class and returns them in a string
     * 
     * @return string
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    public function __call($name, $args) {}


    /**
     * MUTATOR METHODS:
     * - fill(string|array $attribute, mixed $value = null): void
     */

    /**
     * Receives an attribute name or a list of attributes with their corresponding values to be set
     * 
     * @param string|array<string, mixed> $attribute
     * @param mixed $value
     * 
     * example of list: 
     * [
     *   "attributeName1" => attributeValue1,
     *   "attributeName2" => attributeValue2
     * ]
     * 
     * @return void
     */
    public function fill(string|array $attribute, mixed $value = null)
    {

        if (gettype($attribute) === "string") {
            $this->$attribute = $value;
        }

        if (gettype($attribute) === "array") {
            foreach ($attribute as $attributeName => $attributeValue) {
                $this->$attributeName = $attributeValue;
            }
        }
    }


    /**
     * TRANSFORM METHODS:
     * - toString(bool $includeHidden = false, int $depth = 0): string
     * - toArray(bool $includeHidden = false): array<string, mixed>
     */

    /**
     * Returns the attributes of the class as a string. This is useful for debugging purposes.
     * This is a recursive function that will call the toString() method of the BaseClass and of the child classes.
     * 
     * @param bool $includeHidden
     * @param int $depth
     * 
     * @return string
     */
    public function toString(bool $includeHidden = false, int $depth = 0): string
    {
        $result = [];

        foreach ($this->data as $attributeName => $attributeValue) {
            $tabs = str_repeat("  ", $depth); // add tabs to the string to make it more readable
            $words = $tabs . $attributeName;

            $getter = 'get' . str_replace('_', '', ucwords($attributeName, '_')) . 'Attribute';

            // check if the getter function exists
            if (method_exists($this, $getter)) {
                $result[] = "$words: " . $this->$getter();
                continue;
            }

            // check if the property exists
            if ($includeHidden || !$this->isHidden($attributeName)) {
                // if the property is a BaseClass instance, call the __toString() method of the BaseClass class
                if ($this->$attributeName instanceof BaseClass) {
                    $result[] = "$words:\n" . $this->$attributeName->toString($includeHidden, $depth + 1);
                    continue;
                }

                // if the property is an array, check if it is an array of BaseClass instances or an array of values
                if (gettype($this->$attributeName) === "array") {
                    $result[] = "$words: ";
                    foreach ($this->$attributeName as $key => $value) {
                        // if it is an array of BaseClass instances, call the toString() method of the BaseClass increasing the depth
                        if ($value instanceof BaseClass) {
                            $result[] = $value->toString($includeHidden, $depth + 1);
                            continue;
                        }

                        // if it is an array of values
                        if (is_array($value)) {
                            $result[] = "$words: " . implode(", ", $value);
                            continue;
                        }

                        $result[] = "$words: " . $value;
                    }
                    continue;
                }

                // if the property is a string, int, float or bool, just return it
                $result[] = "$words: " . ($this->$attributeName ?? "NULL");
            }
        }

        return implode("\n", array_filter($result));
    }

    /**
     * Returns the attributes of the class as an array.
     * This is a recursive function that will call the toArray() method of the BaseClass and of the child classes.
     * 
     * @return array<string, mixed>
     */
    public function toArray(bool $includeHidden = false): array
    {
        $result = [];
        foreach ($this->data as $attributeName => $attributeValue) {
            // check if the property exists
            if ($includeHidden || !$this->isHidden($attributeName)) {
                if ($this->$attributeName instanceof BaseClass) {
                    $result[$attributeName] = $this->$attributeName->toArray($includeHidden);
                } else {
                    $result[$attributeName] = $this->$attributeName;
                }
            }
        }
        return $result;
    }


    /**
     * UTILITY METHODS:
     * - isHidden(string $property): bool
     * - isEqualTo(BaseClass $object): bool
     */

    /**
     * Checks that the property is not in the hidden array
     * 
     * @param string $property
     * 
     * @return bool
     */
    private function isHidden(string $property): bool
    {
        return in_array($property, $this->hidden);
    }

    /**
     * Checks if the object is equal to another object. It will compare the unique attribute of the class.
     * 
     * @param static $object
     * 
     * @return bool
     */
    public function isEqualTo(BaseClass $object): bool
    {
        if (count($this->unique) !== count($object->unique)) return false;
        if (count($this->unique) === 0) return false;
        if (count($object->unique) === 0) return false;

        foreach ($this->unique as $uniqueKey) {
            if (!in_array($uniqueKey, $object->unique)) return false;
        }

        foreach ($this->unique as $uniqueKey) {
            if ($object->$uniqueKey !== $this->$uniqueKey) return false;
        }

        return true;
    }


    /**
     * Query methods
     */

    /**
     * Executes the query and returns the results as instances of the class
     * 
     * @return array
     */
    public function get(): array
    {
        if ($this->query === "") {
            $this->select();
        }

        if (method_exists($this, "softDelete") && !$this->withDeleted) {
            $this->where .= 'deleted_at <> NULL';
        }

        $statement = $this->pdo->prepare($this->query . $this->where . $this->orderBy . $this->groupBy . $this->limit);

        $statement->execute($this->bindings);

        $results = $statement->fetchAll();

        $this->reset(); // Reset all query strings

        return array_map(function ($item) {
            return $this->hydrate($item);
        }, $results);
    }

    /**
     * Sets the transaction flag to true
     * 
     * @return self
     */
    public function withTransaction(): self
    {
        $this->withTransaction = true;
        return $this;
    }

    /**
     * Sets all the variables to their default values
     * 
     * @return void
     */
    public function reset(): void
    {
        $this->query = '';
        $this->where = '';
        $this->limit = '';
        $this->orderBy = '';
        $this->groupBy = '';
        $this->withTransaction = false;
        $this->withDeleted = false;
        $this->bindings = [];
    }

    /**
     * Creates an instance of the class
     * 
     * @param object|array<string, mixed> $data
     * 
     * @return self
     */
    protected function hydrate(array|object $data): self
    {
        $class = get_called_class();
        $instance = new $class();

        foreach ($data as $key => $value) {
            $instance->$key = $value;
        }

        return $instance;
    }

    /**
     * Returns the query string that is being formed
     * 
     * @return string
     */
    public function toSql(): string
    {
        if (method_exists($this, "softDelete") && !$this->withDeleted) {
            $this->where .= "deleted_at <> NULL";
        }

        return $this->query . $this->where . $this->orderBy . $this->groupBy . $this->limit;
    }

    /**
     * Returns the query string that is being formed but instead of the placeholders, returs the values
     * 
     * @return string
     */
    public function queryWithBindings(): string
    {
        $query = $this->toSql();

        foreach ($this->bindings as $bindingKey => $bindingValue) {
            $query = str_replace($bindingKey, $bindingValue, $query);
        }

        return $query;
    }
}
