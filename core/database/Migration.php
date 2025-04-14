<?php

namespace Ipoo\Core\Database;

use Ipoo\Core\BaseClass;

class Migration extends BaseClass
{
    protected string $table = 'migrations';
    protected array $attributes = ['id', 'name', 'batch'];

    public function getLastMigrationBatch(): int
    {
        $lastMigration = $this->last();
        return $lastMigration ? $lastMigration->batch : 0;
    }

    public function getMigrationNames(): array
    {
        $migrations = $this->select('name')->get();
        return array_map(function ($migration) {
            return $migration->name;
        }, $migrations);
    }
}
