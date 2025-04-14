<?php

namespace Ipoo\Src\Migrations;

use Ipoo\Core\Database\{Table, TableBuilder};
use Ipoo\Core\Interfaces\MigrationInterface;

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
