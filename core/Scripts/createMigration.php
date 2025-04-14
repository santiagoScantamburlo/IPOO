<?php

namespace Ipoo\Src\Scripts;

$options = getopt('n:t:', ['name:', 'table:']);

$className = $options['n'] ?? $options['name'] ?? null;
$tableName = $options['t'] ?? $options['table'] ?? null;

createMigrationFile($className, $tableName);

function getMigrationContent($className, $tableName)
{
    return "<?php

namespace Ipoo\Src\Database\Migrations;

use Ipoo\Core\Database\{Table, TableBuilder};
use Ipoo\Core\Interfaces\MigrationInterface;

class {$className} implements MigrationInterface
{
\tfunction up(): void
\t{
\t\tTableBuilder::create('$tableName', function (Table \$table) {
\t\t\t\$table->id();
\t\t});
\t}

\tfunction down(): void
\t{
\t\tTableBuilder::dropIfExists('$tableName');
\t}
}\n";
}

function createMigrationFile($className, $tableName = "")
{
    $time = date('YmdHis');
    $migrationsDir = dirname(__DIR__) . '/Migrations/';
    $filePath = $migrationsDir . $time . '_' . $className . ".php";

    if (!file_exists($migrationsDir)) {
        mkdir($migrationsDir, 0755, true);
    }

    $content = getMigrationContent($className, $tableName);

    if (file_put_contents($filePath, $content)) {
        echo "File '{$className}.php' created successfully in {$migrationsDir}\n";
    } else {
        echo "Error creating the migration file\n";
    }
}
