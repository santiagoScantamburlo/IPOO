<?php

namespace Ipoo\Tests;

use Ipoo\Src\BaseClass;
use Ipoo\Src\Traits\SoftDeletes;

class Book extends BaseClass
{
    // use SoftDeletes;

    protected array $attributes = ["id", "isbn", "title", "price", "author_name", "author_surname"];

    protected string $table = "person";
}
