<?php

namespace Ipoo;

/**
 * @method static static create(array $data)
 * @method string|array<string, mixed> get(string|array $attribute)
 * @method void fill(string|array $attribute, mixed $value = null)
 * @method string toString(bool $includeHidden = false,int $depth = 0)
 * @method array<string, mixed> toArray(bool $includeHidden = false)
 */
class BaseClass
{
    /**
     * ACCESSORS are not mandatory as the get(), __get() and __toString() methods will be able to find the variables as long as they are not declared as hidden
     * In case you want to perform some kind of modification to the variable, you can use them to return the mutated value.
     */

    /**
     * MUTATORS are not mandatory either as the __set() and fill() method will be able to find and set those variables
     */

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
     * CREATION METHODS:
     * - Constructor: __construct(array $_data = [])
     * - Static method: create(array $data)
     */

    /**
     * Constructor method that will be used to set the values of the attributes. It will be called when the class is instantiated.
     * It will receive an array with the values of the attributes and it will set them in the data array.
     */
    public function __construct(array $_data = [])
    {
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


    /**
     * ACCESSOR METHODS:
     * - get(string|array $attribute): null|string|array<string, mixed>
     */

    /**
     * Receive an attribute name or a list of attribute names and return them. In case it is only one, return it as a string, if it is an array return it as a list of attributes with their values.
     * Returns an empty array if there were no attributes to get.
     * 
     * @param string|string[] $attribute
     * 
     * @return null|string|array<string, mixed>
     */
    public function get(string|array $attribute)
    {
        if (gettype($attribute) === "string") {
            if ($this->isHidden($attribute)) return null;

            $getter = 'get' . ucfirst($attribute) . 'Attribute';

            if (method_exists($this, $getter)) {
                return $this->$getter();
            }

            return $this->$attribute;
        }

        if (gettype($attribute) === "array") {
            $return = [];
            foreach ($attribute as $attributeName) {
                if ($this->isHidden($attributeName)) continue;

                $getter = 'get' . ucfirst($attributeName) . 'Attribute';

                if (method_exists($this, $getter)) {
                    $return[$attributeName] = $this->$getter();
                    continue;
                }

                $return[$attributeName] = $this->$attributeName;
            }

            return $return;
        }

        return null;
    }


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
            // transform the attribute name: camelCaseAttribute => Camel Case Attribute
            $words = preg_replace('/(?<!\ )[A-Z]/', ' $0', $attributeName);
            $words = ucwords($words);
            $tabs = str_repeat("  ", $depth); // add tabs to the string to make it more readable
            $words = $tabs . $words;

            // check if the getter function exists
            $getter = 'get' . ucfirst($attributeName) . 'Attribute';

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
     * TRANSFORM METHODS:
     * - toString(bool $includeHidden = false, int $depth = 0): string
     * - toArray(bool $includeHidden = false): array<string, mixed>
     */

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
}
