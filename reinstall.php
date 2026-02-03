<?php

$dbHost = '127.0.0.1';
$dbPort = '5433';
$dbUser = 'oro_user';
$dbPass = 'StrongPassword123!';
$dbName = 'oro_commerce';
$adminDb = 'postgres'; // Connect to postgres, template1, or another existing DB

echo "Starting re-installation process...\n";

// 1. Drop and Recreate Database
echo "Connecting to '$adminDb' on port $dbPort...\n";
$dsn = "pgsql:host=$dbHost;port=$dbPort;dbname=$adminDb;sslmode=disable";

try {
    $pdo = new PDO($dsn, $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Terminating existing connections to '$dbName'...\n";
    $sqlKill = "SELECT pg_terminate_backend(pid) FROM pg_stat_activity WHERE datname = :dbname AND pid <> pg_backend_pid()";
    $stmt = $pdo->prepare($sqlKill);
    $stmt->execute(['dbname' => $dbName]);

    echo "Dropping database '$dbName'...\n";
    $pdo->exec("DROP DATABASE IF EXISTS $dbName");
    
    echo "Creating database '$dbName'...\n";
    $pdo->exec("CREATE DATABASE $dbName");
    
    echo "Database reset complete.\n";

} catch (PDOException $e) {
    die("Database operation failed: " . $e->getMessage() . "\n");
}

// 2. Run oro:install
echo "Running oro:install command...\n";

$cmd = "php bin/console oro:install " .
       "--env=prod " .
       "--no-debug " .
       "--timeout=1800 " . // 30 minutes timeout
       "--organization-name='My Company' " .
       "--user-name='admin' " .
       "--user-email='admin@example.com' " .
       "--user-firstname='Admin' " .
       "--user-lastname='User' " .
       "--user-password='admin' " .
       "--application-url='http://localhost:8000' " .
       "--sample-data=n " .
       "-n"; // Non-interactive

echo "Command: $cmd\n";

$descriptorspec = [
   0 => ["pipe", "r"],  // stdin
   1 => ["pipe", "w"],  // stdout
   2 => ["pipe", "w"]   // stderr
];

$process = proc_open($cmd, $descriptorspec, $pipes);

if (is_resource($process)) {
    while ($s = fgets($pipes[1])) {
        echo $s;
        flush();
    }
    while ($s = fgets($pipes[2])) {
        echo "ERR: " . $s;
        flush();
    }
    
    fclose($pipes[0]);
    fclose($pipes[1]);
    fclose($pipes[2]);

    $return_value = proc_close($process);

    echo "Command returned $return_value\n";
    
    if ($return_value === 0) {
        echo "Installation SUCCESSFUL.\n";
    } else {
        echo "Installation FAILED.\n";
        exit(1);
    }
} else {
    echo "Failed to launch installation command.\n";
    exit(1);
}
