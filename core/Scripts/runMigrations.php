<?php

namespace Ipoo\Core\Scripts;

use Ipoo\Core\Database\Migration;

require_once __DIR__ . '/../../autoload.php';

$options = getopt('r:fR', ['rollback:', 'fresh', 'reset']);

$migration = null;

try {
    $migration = new Migration();
} catch (\Exception $e) {
    echo "Error initializing migration: " . $e->getMessage() . "\n";
    exit(1);
}

$isRollback = isset($options['r']) || isset($options['rollback']);
$isFresh = isset($options['f']) || isset($options['fresh']);
$isReset = isset($options['R']) || isset($options['reset']);

if (!$isRollback && !$isFresh && !$isReset) {
    runMigrations($migration);
    echo "Migrations executed successfully.\n";
    exit(0);
}

if ($isRollback) {
    $batch = $options['r'] ?? $options['rollback'] ?? null;
    rollbackMigrations($migration, $batch);
    echo "Rollback executed successfully.\n";
    exit(0);
}

/**
 * Get the list of migration files in the Migrations directory.
 * 
 * @return array List of migration file paths.
 */
function getMigrationFiles(): array
{
    $migrationsDir = dirname(__DIR__) . '/Migrations/';
    $migrationFiles = glob($migrationsDir . '*.php');

    return $migrationFiles;
}

/**
 * Run the migrations by executing the up method of each migration class.
 * 
 * @param Migration $migrationObj The Migration object to handle database operations.
 * @return void
 */
function runMigrations(Migration $migrationObj)
{
    $migrationFiles = checkNewMigrations($migrationObj);

    if (count($migrationFiles) === 0) {
        echo "No new migrations to run.\n";
        exit(0);
    }

    $newMigrations = [];
    if ($migrationObj->tableExists()) {
        $lastBatch = $migrationObj->getLastMigrationBatch();
        $migrationNames = $migrationObj->getMigrationNames();
    } else {
        $lastBatch = 0;
        $migrationNames = [];
    }

    foreach ($migrationFiles as $file) {
        require_once $file;

        if (in_array($file, $migrationNames)) continue;

        $fileName = explode("/", $file)[count(explode("/", $file)) - 1];

        $className = explode("_", basename($file, '.php'))[1];
        $className = "Ipoo\Src\Database\Migrations\\{$className}";
        if (class_exists($className)) {
            $migration = new $className();
            if (method_exists($migration, 'up')) {
                $migration->up();
                $newMigrations[] = [
                    'name' => $fileName,
                    'batch' => $lastBatch + 1
                ];
            }
        }
    }

    $migrationObj->insert($newMigrations);
}

/**
 * Rollback the migrations by executing the down method of each migration class.
 * 
 * @param int $batch The batch number to rollback. Default is 1.
 * @return void
 */
function rollbackMigrations(Migration $migrationObj, int $batch = 1)
{
    $migrationFiles = checkRollbackMigrations($migrationObj, $batch);

    $rolledBackMigrations = [];

    foreach ($migrationFiles as $file) {
        require_once $file;

        $fileName = explode("/", $file)[count(explode("/", $file)) - 1];

        $className = explode("_", basename($file, '.php'))[1];

        $className = "Ipoo\Src\Database\Migrations\\{$className}";

        if (class_exists($className)) {
            $migration = new $className();
            if (method_exists($migration, 'down')) {
                if ($migrationObj->tableExists()) {
                    $rolledBackMigrations[] = $migrationObj->where('name', $fileName)->first();
                }
                $migration->down();
            }
        }
    }

    array_map(function ($migration) use ($migrationObj) {
        if ($migrationObj->tableExists()) {
            $migration->delete();
        }
    }, $rolledBackMigrations);
}

/**
 * Check for new migrations that have not been executed yet.
 * 
 * @param Migration $migrationObj The Migration object to handle database operations.
 * @return array List of new migration files.
 */
function checkNewMigrations(Migration $migrationObj): array
{
    $migrationFiles = getMigrationFiles();

    if (!$migrationObj->tableExists()) return $migrationFiles;

    $migrationNames = $migrationObj->getMigrationNames();

    return array_filter($migrationFiles, function ($file) use ($migrationNames) {
        $fileName = explode("/", $file)[count(explode("/", $file)) - 1];
        return !in_array($fileName, $migrationNames);
    });
}

/**
 * Check for migrations that need to be rolled back.
 * 
 * @param Migration $migrationObj The Migration object to handle database operations.
 * @param int $batch The batch number to check for rollback migrations.
 * @return array List of migration files that need to be rolled back.
 */
function checkRollbackMigrations(Migration $migrationObj, int $batch): array
{
    $migrationFiles = getMigrationFiles();

    if (!$migrationObj->tableExists()) exit(0);

    $lastBatch = $migrationObj->getLastMigrationBatch();

    $migrations = $migrationObj->select('name')->where('batch', '>', $lastBatch - $batch)->get();

    $migrations = array_map(function ($migration) {
        return $migration->name;
    }, $migrations);

    return array_filter($migrationFiles, function ($file) use ($migrations) {
        $fileName = explode("/", $file)[count(explode("/", $file)) - 1];
        return in_array($fileName, $migrations);
    });
}
