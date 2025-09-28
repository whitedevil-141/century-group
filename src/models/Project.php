<?php
require_once __DIR__ . '/../../config/Database.php';

class Project {
    public static function latest($limit = 6) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM projects WHERE status=1 ORDER BY id DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
