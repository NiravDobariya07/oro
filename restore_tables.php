<?php

use Doctrine\DBAL\DriverManager;

require __DIR__ . '/vendor/autoload.php';

// Hardcoded for this project based on .env-app
$dbUrl = 'postgres://oro_db_user:oro_db_pass@127.0.0.1:5432/oro_db?sslmode=disable&charset=utf8&serverVersion=13.7';

$connectionParams = ['url' => $dbUrl];
$conn = DriverManager::getConnection($connectionParams);

$tables = [
    'oro_config' => "CREATE TABLE oro_config (id SERIAL PRIMARY KEY, entity VARCHAR(255) DEFAULT NULL, record_id INT DEFAULT NULL)",
    'oro_config_value' => "CREATE TABLE oro_config_value (id SERIAL PRIMARY KEY, config_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, section VARCHAR(50) DEFAULT NULL, text_value TEXT DEFAULT NULL, object_value BYTEA DEFAULT NULL, array_value BYTEA DEFAULT NULL, type VARCHAR(20) NOT NULL, created_at TIMESTAMP NOT NULL, updated_at TIMESTAMP NOT NULL)",
    'oro_entity_config' => "CREATE TABLE oro_entity_config (id SERIAL PRIMARY KEY, class_name VARCHAR(255) NOT NULL, created TIMESTAMP NOT NULL, updated TIMESTAMP DEFAULT NULL, mode VARCHAR(8) NOT NULL, data TEXT DEFAULT NULL)",
    'oro_entity_config_field' => "CREATE TABLE oro_entity_config_field (id SERIAL PRIMARY KEY, entity_id INT DEFAULT NULL, field_name VARCHAR(255) NOT NULL, type VARCHAR(60) NOT NULL, created TIMESTAMP NOT NULL, updated TIMESTAMP DEFAULT NULL, mode VARCHAR(8) NOT NULL, data TEXT DEFAULT NULL)",
];

foreach ($tables as $name => $sql) {
    try {
        echo "Creating $name...\n";
        $conn->executeStatement($sql);
    } catch (\Exception $e) {
        echo "Failed to create $name: " . $e->getMessage() . "\n";
    }
}

// Add index and foreign key
try {
    echo "Adding unique index CONFIG_UQ_ENTITY...\n";
    $conn->executeStatement("CREATE UNIQUE INDEX CONFIG_UQ_ENTITY ON oro_config (entity, record_id)");
    echo "Adding unique index CONFIG_VALUE_UQ_ENTITY...\n";
    $conn->executeStatement("CREATE UNIQUE INDEX CONFIG_VALUE_UQ_ENTITY ON oro_config_value (name, section, config_id)");
    echo "Adding foreign key config_id_fk...\n";
    $conn->executeStatement("ALTER TABLE oro_config_value ADD CONSTRAINT config_id_fk FOREIGN KEY (config_id) REFERENCES oro_config (id) ON DELETE CASCADE");
    echo "Adding foreign key entity_id_fk...\n";
    $conn->executeStatement("ALTER TABLE oro_entity_config_field ADD CONSTRAINT entity_id_fk FOREIGN KEY (entity_id) REFERENCES oro_entity_config (id) ON DELETE CASCADE");
    
    // Set is_installed to true
    $now = (new \DateTime())->format('Y-m-d H:i:s');
    $conn->executeStatement("INSERT INTO oro_config_value (name, section, type, created_at, updated_at, text_value) VALUES ('is_installed', 'oro_distribution', 'boolean', '$now', '$now', '1')");
    echo "Set is_installed to true.\n";
} catch (\Exception $e) {
    echo "Error during finalization: " . $e->getMessage() . "\n";
}
