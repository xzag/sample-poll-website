<?php

return [
    'name' => 'migrations',
    'migrations_namespace' => 'app\migrations',
    'table_name' => 'doctrine_migration_versions',
    'column_name' => 'version',
    'column_length' => 14,
    'executed_at_column_name' => 'executed_at',
    'migrations_directory' => __DIR__ . '/app/migrations',
    'all_or_nothing' => true,
];