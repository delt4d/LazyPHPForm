<?php

$DB_HOST = 'localhost';
$DB_NAME = 'db_at_php';
$DB_USER = 'root';
$DB_PASSWORD = '';


try {
    $pdo = new PDO(
        "mysql:host=$DB_HOST;dbname=$DB_NAME",
        $DB_USER,
        $DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
