<?php

namespace Ipoo\Tests;

use Ipoo\Src\BaseClass;
use Ipoo\Src\Traits\SoftDeletes;

class Person extends BaseClass
{
    // use SoftDeletes;

    protected array $attributes = ["id", "name", "surname", "document_type", "document_number"];

    protected string $table = "person";
}
