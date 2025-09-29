<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../config/db.php';

$pdo = Database::connect();

$where = [];
$params = [];

if (!empty($_GET['location'])) {
    $where[] = "location = :location";
    $params[':location'] = $_GET['location'];
}
if (!empty($_GET['type'])) {
    $where[] = "job_type = :type";
    $params[':type'] = $_GET['type'];
}

$sql = "SELECT j.id, j.title, j.location, j.job_type, j.description, i.name as industry 
        FROM jobs j 
        LEFT JOIN industries i ON j.industry_id=i.id";

if ($where) $sql .= " WHERE " . implode(" AND ", $where);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetchAll());
