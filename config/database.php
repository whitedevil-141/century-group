<?php
require_once __DIR__ . '/../vendor/autoload.php';

class Database {
    private static $pdo = null;

    public static function connect() {
        if (self::$pdo === null) {
            $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
            $dotenv->load();

            try {
                $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=utf8mb4";
                self::$pdo = new PDO(
                    $dsn,
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASS'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                die("❌ DB Connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    /**
     * Initialize database tables if they don't exist
     */
    public static function initTables() {
        $pdo = self::connect();

        $tables = [
            // Admin Users
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) UNIQUE,
                password VARCHAR(255),
                role ENUM('admin','editor','hr') DEFAULT 'admin',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            // // Hero Slides
            // "CREATE TABLE IF NOT EXISTS hero_slides (
            //     id INT AUTO_INCREMENT PRIMARY KEY,
            //     title_line1 VARCHAR(255),
            //     title_line2 VARCHAR(255),
            //     description TEXT,
            //     button_text VARCHAR(100),
            //     button_link VARCHAR(255),
            //     image_url VARCHAR(255),
            //     status TINYINT DEFAULT 1,
            //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            // )",

            // // Counters
            // "CREATE TABLE IF NOT EXISTS counters (
            //     id INT AUTO_INCREMENT PRIMARY KEY,
            //     label VARCHAR(100),
            //     value INT,
            //     icon VARCHAR(100),
            //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            // )",

            // Industries
            "CREATE TABLE IF NOT EXISTS industries (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150),
                slug VARCHAR(150) UNIQUE,
                description TEXT,
                icon VARCHAR(255),
                image_url VARCHAR(255),
                status TINYINT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",

            // // Projects
            // "CREATE TABLE IF NOT EXISTS projects (
            //     id INT AUTO_INCREMENT PRIMARY KEY,
            //     title VARCHAR(150),
            //     description TEXT,
            //     industry_id INT,
            //     image_url VARCHAR(255),
            //     status TINYINT DEFAULT 1,
            //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            //     FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE CASCADE
            // )",

            // // Team Members
            // "CREATE TABLE IF NOT EXISTS team_members (
            //     id INT AUTO_INCREMENT PRIMARY KEY,
            //     name VARCHAR(150),
            //     designation VARCHAR(150),
            //     photo VARCHAR(255),
            //     bio TEXT,
            //     social_links TEXT,
            //     status TINYINT DEFAULT 1,
            //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            // )",

            // // Testimonials
            // "CREATE TABLE IF NOT EXISTS testimonials (
            //     id INT AUTO_INCREMENT PRIMARY KEY,
            //     name VARCHAR(150),
            //     designation VARCHAR(150),
            //     message TEXT,
            //     photo VARCHAR(255),
            //     status TINYINT DEFAULT 1,
            //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            // )",

            // Jobs
            "CREATE TABLE IF NOT EXISTS jobs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(150),
                department VARCHAR(100),
                location VARCHAR(100),
                type VARCHAR(50),
                description TEXT,
                status TINYINT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",

            // Applications
            "CREATE TABLE IF NOT EXISTS applications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                job_id INT,
                name VARCHAR(150),
                email VARCHAR(150),
                phone VARCHAR(50),
                cv_file VARCHAR(255),
                status TINYINT DEFAULT 0,
                applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
            )",

            // Messages
            "CREATE TABLE IF NOT EXISTS messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150),
                email VARCHAR(150),
                subject VARCHAR(200),
                message TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )"
        ];

        foreach ($tables as $sql) {
            $pdo->exec($sql);
        }

        return true;
    }
}
