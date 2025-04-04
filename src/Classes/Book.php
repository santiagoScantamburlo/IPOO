<?php

namespace Ipoo\Src\Classes;

use Ipoo\Src\BaseClass;

use Ipoo\Src\Traits\SoftDeletes;

class Book extends BaseClass
{
	use SoftDeletes;

	protected string $table = 'books';

	protected array $attributes = ['id', 'deleted_at'];
}
