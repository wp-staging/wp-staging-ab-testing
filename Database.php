<?php

namespace WpStaging\AbTesting;

use PDO;
use PDOException;

if (!defined('ABSPATH')) {
    exit;
}

class Database
{
    private PDO $pdo;
    private string $tableName;

    public function __construct()
    {
        // Define your database credentials and table name
        $dbHost = '172.201.0.1'; // Change as needed
        $dbName = 'wpstaging'; // Change as needed
        $dbUser = 'admin'; // Change as needed
        $dbPassword = 'admin'; // Change as needed
        $this->tableName = 'wpstgab_test_results'; // Table to store A/B test results

        try {
            $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4";
            $this->pdo = new PDO($dsn, $dbUser, $dbPassword);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }

        // Ensure the table exists
        $this->createTableIfNotExists();
    }

    // Create the table if it doesn't already exist
    private function createTableIfNotExists()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS $this->tableName (
                id INT AUTO_INCREMENT PRIMARY KEY,
                test_name VARCHAR(255),
                variant VARCHAR(50),
                event VARCHAR(255),
                timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            die('Failed to create table: ' . $e->getMessage());
        }
    }

    // Insert A/B test event into the database
    public function insertTestEvent($testName, $variant, $event): bool
    {
        $sql = "INSERT INTO $this->tableName (test_name, variant, event) VALUES (:test_name, :variant, :event)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':test_name', $testName);
            $stmt->bindParam(':variant', $variant);
            $stmt->bindParam(':event', $event);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log('Failed to insert A/B test event: ' . $e->getMessage());
            return false;
        }

        return true;
    }
}
