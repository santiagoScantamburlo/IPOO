<?php

namespace Ipoo\Src\Migrations;

use Ipoo\Src\{TableBuilder, Table};
use Ipoo\Src\Interfaces\MigrationInterface;

class CreateBooksTable implements MigrationInterface
{
	function up(): void
	{
		TableBuilder::create('books', function (Table $table) {
			$table->id();
			$table->varchar('isbn', 13);
			$table->varchar('title', 255);
			$table->int('price');
			$table->timestamps();
			$table->softDelete();
		});
	}

	function down(): void
	{
		TableBuilder::drop('books');
	}
}
