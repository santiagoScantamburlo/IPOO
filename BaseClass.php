<?php

namespace Ipoo;

abstract class BaseClass
{
    /**
     * ACCESSORS are not mandatory as the get() and __toString() methods will be able to find the variables as long as they are not declared as protected or private
     * In case you want to perform some kind of modification to the variable, you can use them to return the mutated value.
     */

    /**
     * MUTATORS are not mandatory either as the set() method will be able to find and set those variables as long as they are not declared as readonly
     */

    /**
     * All class fields
     * 
     * @var array<string, mixed>
     */
    private array $data = [];

    /**
     * Hidden properties
     */
    private array $hidden = [];

    /**
     * All the attributes of the class
     * 
     * @var string[]
     */
    protected array $attributes = [];

    /**
     * The attributes that may have a prefix when returning them
     * 
     * @var array<string, string>
     */
    protected array $prefixes = [];

    /**
     * The attributes that may have a sufix when returning them
     * 
     * @var array<string, string>
     */
    protected array $sufixes = [];

    public function __construct(array $_data = [])
    {
        foreach ($this->attributes as $attribute) {
            $this->data[$attribute] = $_data[$attribute] ?? null;
        }
    }

    /**
     * Gets all the non private and protected attributes of a class and returns them in a string
     * 
     * @return string
     */
    public function __toString(): string
    {
        $result = [];

        foreach ($this->data as $attributeName => $attributeValue) {
            // transform the attribute name: camelCaseAttribute => Camel Case Attribute
            $words = preg_replace('/(?<!\ )[A-Z]/', ' $0', $attributeName);

            // check if the getter function exists
            $getter = 'get' . ucfirst($attributeName);

            if (method_exists($this, $getter)) {
                $result[] = ucwords($words) . ": " . $this->$getter();
                continue;
            }

            // check if the property exists
            if (!$this->isHidden($attributeName)) {
                $result[] = ucwords($words) . ": " . $this->data[$attributeName];
                continue;
            }
        }

        return implode("\n", array_filter($result));
    }

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
        if (gettype($attribute) === "string" && $value) {
            $this->data[$attribute] = $value;
        }

        if (gettype($attribute) === "array") {
            foreach ($attribute as $attributeName => $attributeValue) {
                $this->data[$attributeName] = $attributeValue;
            }
        }
    }

    public function __set(string $attribute, mixed $value): void
    {
        $this->data[$attribute] = $value;
    }

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

            return $this->data[$attribute];
        }

        if (gettype($attribute) === "array") {
            $return = [];
            foreach ($attribute as $attributeName) {
                if ($this->isHidden($attributeName)) continue;

                $return[$attributeName] = $this->data[$attribute];
            }

            return $return;
        }

        return null;
    }

    /**
     * Magic function to access the dynamic variables
     */
    public function __get(string $attribute): mixed
    {
        if ($this->isHidden($attribute)) return null;

        return $this->data[$attribute] ?? null;
    }

    /**
     * Checks that the property exists and that it isn't protected or private
     * 
     * @param string $property
     * 
     * @return bool
     */
    private function isHidden(string $property): bool
    {
        return in_array($property, $this->hidden);
    }

    public static function Create(array $data)
    {
        return new static($data);
    }
}
