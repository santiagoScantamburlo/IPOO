<?php

namespace Ipoo\Tests;

use Ipoo\Src\Table;
use Ipoo\Src\TableBuilder;

require_once __DIR__ . '/../autoload.php';

TableBuilder::create('books', function (Table $table) {
    $table->id();
    $table->varchar('isbn', 20);
    $table->varchar('title');
    $table->bool('has_stock')->default(true);
    $table->timestamps();
    $table->softDelete();
});
