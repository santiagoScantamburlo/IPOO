<?php

namespace Ipoo\Src\Interfaces;

interface MigrationInterface
{
    public function up(): void;
    public function down(): void;
}
