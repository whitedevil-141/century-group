<?php
require_once __DIR__ . '/../../config/Database.php';

class Team {
    public static function all() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM team_members WHERE status=1 ORDER BY id ASC");
        return $stmt->fetchAll();
    }
}
