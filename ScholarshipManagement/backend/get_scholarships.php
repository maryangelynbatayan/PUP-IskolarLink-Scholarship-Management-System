<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'iskolarlink_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM scholarships ORDER BY created_at DESC");
    $stmt->execute();
    $scholarships = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($scholarships);

} catch(PDOException $e) {
    echo json_encode(['error' => 'Database Error: ' . $e->getMessage()]);
}
?>
