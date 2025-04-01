<?php

namespace Ipoo\Tps\Tp2\Ej1;

require_once __DIR__ . '/../../../autoload.php';

$persona = Persona::Create(["nombre" => "Santiago"]);

$persona2 = new Persona();

$persona2->apellido = "Gonzalez";

$persona->fill(["nombre" => "Juan"]);

echo $persona; // automatically invokes __toString() function
echo $persona2;
