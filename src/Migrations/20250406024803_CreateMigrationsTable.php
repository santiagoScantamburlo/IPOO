<?php

namespace Ipoo\Src\Migrations;

use Ipoo\Src\{TableBuilder, Table};
use Ipoo\Src\Interfaces\MigrationInterface;

class CreateMigrationsTable implements MigrationInterface
{
	function up(): void
	{
		TableBuilder::create('migrations', function (Table $table) {
			$table->id();
			$table->varchar('name');
			$table->int('batch')->default(0);
			$table->timestamps();
		});
	}

	function down(): void
	{
		TableBuilder::drop('migrations');
	}
}
