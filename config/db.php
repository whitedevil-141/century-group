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

            // Industries
            "CREATE TABLE IF NOT EXISTS industries (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150),
                slug VARCHAR(150) UNIQUE,
                description TEXT,
                icon VARCHAR(255),
                image_url TEXT,
                status TINYINT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",

            // Jobs
            "CREATE TABLE IF NOT EXISTS jobs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(150) NOT NULL,
                department_name VARCHAR(100),
                location VARCHAR(100),
                job_type ENUM('Full Time','Part Time','Internship') NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",

            // Applications
            "CREATE TABLE IF NOT EXISTS applications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                job_id INT NOT NULL,
                name VARCHAR(150) NOT NULL,
                email VARCHAR(150) NOT NULL,
                phone VARCHAR(50),
                cv_path VARCHAR(255),
                status ENUM('Pending','Reviewed','Shortlisted','Rejected') DEFAULT 'Pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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

        // Seed default admin
        $check = $pdo->query("SELECT COUNT(*) as c FROM users")->fetch();
        if ($check['c'] == 0) {
            $password = password_hash('admin123', PASSWORD_BCRYPT);
            $pdo->exec("INSERT INTO users (username,password,role) VALUES ('admin','$password','admin')");
        }

        return true;
    }
}
