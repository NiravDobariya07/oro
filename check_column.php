<?php

$dsn = "pgsql:host=127.0.0.1;port=5433;dbname=oro_commerce;sslmode=disable";
$user = "oro_user";
$password = "StrongPassword123!";

try {
    $pdo = new PDO($dsn, $user, $password);
    
    // Check if column exists
    $stmt = $pdo->prepare("
        SELECT column_name 
        FROM information_schema.columns 
        WHERE table_name = 'acme_customer_note' 
        AND column_name = 'organization_id'
    ");
    $stmt->execute();
    $column = $stmt->fetchColumn();

    if ($column) {
        echo "SUCCESS: Column 'organization_id' exists in 'acme_customer_note'.\n";
    } else {
        echo "FAILURE: Column 'organization_id' DOES NOT EXIST in 'acme_customer_note'.\n";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
