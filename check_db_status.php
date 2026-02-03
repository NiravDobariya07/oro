<?php

$dsn = "pgsql:host=127.0.0.1;port=5433;dbname=oro_commerce;sslmode=disable";
$user = "oro_user";
$password = "StrongPassword123!";

try {
    $pdo = new PDO($dsn, $user, $password);
    echo "Connected successfully to database on port 5433.\n";

    // List all tables
    $stmt = $pdo->query("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "Database is EMPTY (no tables found).\n";
    } else {
        echo "Database contains " . count($tables) . " tables.\n";
        
        $targetTables = [
            'oro_config', 
            'oro_config_value',
            'oro_entity_config',
            'oro_entity_config_field',
            'oro_entity_config_index_value', // The one that was missing before
            'oro_user'
        ];
        
        foreach ($targetTables as $target) {
            if (in_array($target, $tables)) {
                echo "Table '$target' EXISTS.\n";
            } else {
                echo "Table '$target' MISSING.\n";
            }
        }
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
