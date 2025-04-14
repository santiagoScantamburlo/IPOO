<?php

namespace Ipoo\Core\Interfaces;

interface MigrationInterface
{
    public function up(): void;
    public function down(): void;
}
