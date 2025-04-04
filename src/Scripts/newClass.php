<?php

$options = getopt('n:t:s', ['name:', 'table:', 'soft']);

$className = $options['n'] ?? $options['name'] ?? null;

if (!$className) {
    echo "Please specify a class name using <ClassName> [--table] [--soft-deletes]";
    exit(1);
}

$connectToTable = isset($options['t']) || isset($options['table']);

$tableName = $options['t'] ?? $options['table'] ?? null;

$hasSoftDeletes = isset($options['s']) || isset($options['soft-deletes']);

$content = "<?php\n\nnamespace Ipoo\Src\Classes;\n\nuse Ipoo\Src\BaseClass;\n\n";

$tableDeclarationLine = "";

$traitUseLine = "";

if ($connectToTable) {
    $tableDeclarationLine = "\tprotected string \$table = '$tableName';\n\n";
}

if ($connectToTable && $hasSoftDeletes) {
    $content .= "use Ipoo\Src\Traits\SoftDeletes;\n\n";
    $traitUseLine = "\tuse SoftDeletes;\n\n";
}

$content .= "class {$className} extends BaseClass\n{\n{$traitUseLine}{$tableDeclarationLine}";

$content .= "\tprotected array \$attributes = ['id'";

$content .= ($connectToTable && $hasSoftDeletes) ? ", 'deleted_at'];\n" : "];\n";

$content .= "}\n";

$classesDir = dirname(__DIR__) . '/Classes/';

$filePath = $classesDir . $className . ".php";

if (!file_exists($classesDir)) {
    mkdir($classesDir, 0755, true);
}

if (file_put_contents($filePath, $content)) {
    echo "File '{$className}.php' created successfully in {$classesDir}\n";
} else {
    echo "Error creating the class";
}
