<?php
// classes/Database.php

class Database {
    private static $instance = null;
    private $pdo;

    // The constructor is private so no one can instantiate it multiple times with 'new Database()'
    private function __construct() {
        $host    = 'localhost';
        $db      = 'nexus';
        $user    = 'root';
        $pass    = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on SQL syntax errors
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch records as clean associative arrays
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Use real prepared statements for security
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // In production, log this error securely instead of echoing it
            die("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Public static method to get the single PDO connection instance.
     * Use throughout the app as: Database::connect()
     */
    public static function connect() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}