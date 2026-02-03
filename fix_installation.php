<?php

use Doctrine\DBAL\DriverManager;

require __DIR__ . '/vendor/autoload.php';

// Correct credentials for port 5433
$dbUrl = 'postgres://oro_user:StrongPassword123!@127.0.0.1:5433/oro_commerce?sslmode=disable&charset=utf8&serverVersion=13.7';

$connectionParams = ['url' => $dbUrl];

try {
    $conn = DriverManager::getConnection($connectionParams);
} catch (\Exception $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}

$tables = [
    'oro_config' => "CREATE TABLE oro_config (id SERIAL PRIMARY KEY, entity VARCHAR(255) DEFAULT NULL, record_id INT DEFAULT NULL)",
    'oro_config_value' => "CREATE TABLE oro_config_value (id SERIAL PRIMARY KEY, config_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, section VARCHAR(50) DEFAULT NULL, text_value TEXT DEFAULT NULL, object_value BYTEA DEFAULT NULL, array_value BYTEA DEFAULT NULL, type VARCHAR(20) NOT NULL, created_at TIMESTAMP NOT NULL, updated_at TIMESTAMP NOT NULL)",
    'oro_entity_config' => "CREATE TABLE oro_entity_config (id SERIAL PRIMARY KEY, class_name VARCHAR(255) NOT NULL, created TIMESTAMP NOT NULL, updated TIMESTAMP DEFAULT NULL, mode VARCHAR(8) NOT NULL, data TEXT DEFAULT NULL)",
    'oro_entity_config_field' => "CREATE TABLE oro_entity_config_field (id SERIAL PRIMARY KEY, entity_id INT DEFAULT NULL, field_name VARCHAR(255) NOT NULL, type VARCHAR(60) NOT NULL, created TIMESTAMP NOT NULL, updated TIMESTAMP DEFAULT NULL, mode VARCHAR(8) NOT NULL, data TEXT DEFAULT NULL)",
];

foreach ($tables as $name => $sql) {
    try {
        // Check if table exists first using a simple query
        try {
            $conn->executeQuery("SELECT 1 FROM $name LIMIT 1");
            echo "Table $name already exists, skipping creation.\n";
        } catch (\Exception $e) {
            // Table doesn't exist (likely), so create it
             echo "Creating $name...\n";
            $conn->executeStatement($sql);
        }
    } catch (\Exception $e) {
        echo "Failed to manage $name: " . $e->getMessage() . "\n";
    }
}

// Add index and foreign key (idempotent checks are harder in raw SQL without knowing schema details, 
// strictly catching errors if they exist)

$indexesAndConstraints = [
    "CREATE UNIQUE INDEX CONFIG_UQ_ENTITY ON oro_config (entity, record_id)",
    "CREATE UNIQUE INDEX CONFIG_VALUE_UQ_ENTITY ON oro_config_value (name, section, config_id)",
    "ALTER TABLE oro_config_value ADD CONSTRAINT config_id_fk FOREIGN KEY (config_id) REFERENCES oro_config (id) ON DELETE CASCADE",
    "ALTER TABLE oro_entity_config_field ADD CONSTRAINT entity_id_fk FOREIGN KEY (entity_id) REFERENCES oro_entity_config (id) ON DELETE CASCADE"
];

foreach ($indexesAndConstraints as $sql) {
    try {
        $conn->executeStatement($sql);
        echo "Executed: " . substr($sql, 0, 50) . "...\n";
    } catch (\Exception $e) {
        echo "Skipping (likely exists): " . $e->getMessage() . "\n";
    }
}

// Set is_installed to true
try {
    // Check if duplicate entry exists to avoid unique constraint violation if re-running
    $check = $conn->fetchOne("SELECT count(*) FROM oro_config_value WHERE name = 'is_installed' AND section = 'oro_distribution'");
    
    if ($check == 0) {
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        $conn->executeStatement("INSERT INTO oro_config_value (name, section, type, created_at, updated_at, text_value) VALUES ('is_installed', 'oro_distribution', 'boolean', '$now', '$now', '1')");
        echo "Set is_installed to true.\n";
    } else {
        echo "is_installed flag already set.\n";
    }
} catch (\Exception $e) {
    echo "Error setting installation flag: " . $e->getMessage() . "\n";
}
