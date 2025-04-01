<?php

namespace Ipoo\Tps\Tp2\Ej1;

use Ipoo\BaseClass;

/**
 * @property string $name
 * @property string $surname
 * @property string $documentType
 * @property int $documentNumber
 */
class Person extends BaseClass
{
    protected array $attributes = ["name", "surname", "documentType", "documentNumber"];
}
