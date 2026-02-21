<?php
try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=postgres', 'postgres', 'natallya');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = 'crm_kid'");
    if (!$stmt->fetchColumn()) {
        $pdo->exec('CREATE DATABASE crm_kid');
        echo "Database crm_kid created successfully.\n";
    } else {
        echo "Database crm_kid already exists.\n";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    exit(1);
}
