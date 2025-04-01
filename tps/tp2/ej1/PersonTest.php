<?php

namespace Ipoo\Tps\Tp2\Ej1;

require_once __DIR__ . '/../../../autoload.php';

/** Creating a new instance of the Person class and setting the values of the attributes */

// Using the constructor to set the values of the attributes
$person = new Person([
    "name" => "Juan",
    "surname" => "Pérez",
    "documentType" => "DNI",
    "documentNumber" => 12345678,
]);

//Using the create() method to set the values of the attributes
$person2 = Person::create([
    "name" => "Juan",
    "surname" => "Pérez",
    "documentType" => "DNI",
    "documentNumber" => 12345678,
]);

// Using the fill() method to set the values of the attributes
$person3 = new Person();
// Setting the value of a single attribute
$person3->fill("name", "Juan");

// Setting the value of multiple attributes
$person3->fill([
    "surname" => "Pérez",
    "documentType" => "DNI",
    "documentNumber" => 12345678,
]);

// Setting the value of a single attribute using magic method
$person4 = new Person();
$person4->name = "Juan";
$person4->surname = "Pérez";
$person4->documentType = "DNI";
$person4->documentNumber = 12345678;

/** Accessing the instance attributes */

// Using the get() method to get the value of a single attribute
$name = $person->get("name");

// Using the get() method to get the value of multiple attributes
// this will return an array with the values of the attributes. ex: ["name" => "Juan", "surname" => "Pérez"]
$nameAndSurname = $person->get(["name", "surname"]);

// Getting the value of a single attribute using the __get() magic method
$name = $person->name;

/** Transforming the object */

// Transforming to string using the __toString() magic method
echo $person;

// Transforming to string using the toString() method
$personString = $person->toString();

// Transforming to array using the toArray() method
// This will return an array with the values of the attributes. ex: ["name" => "Juan", "surname" => "Pérez", ...]
$personArray = $person->toArray();
