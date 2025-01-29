<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'iskolarlink_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'getAll':
        getAllScholarships();
        break;
    case 'get':
        getScholarship();
        break;
    case 'create':
        createScholarship();
        break;
    case 'update':
        updateScholarship();
        break;
    case 'delete':
        deleteScholarship();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function getAllScholarships() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM scholarships ORDER BY created_at DESC");
    $scholarships = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($scholarships);
}

function getScholarship() {
    global $pdo;
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("SELECT * FROM scholarships WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $scholarship = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($scholarship);
}

function createScholarship() {
    global $pdo;
    $data = $_POST;
    $sql = "INSERT INTO scholarships (title, description, num_slots, eligible_courses, application_link, image_url) 
            VALUES (:title, :description, :num_slots, :eligible_courses, :application_link, :image_url)";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        ':title' => $data['title'],
        ':description' => $data['description'],
        ':num_slots' => $data['num_slots'],
        ':eligible_courses' => $data['eligible_courses'],
        ':application_link' => $data['application_link'],
        ':image_url' => $data['image_url']
    ]);
    echo json_encode(['success' => $success, 'id' => $pdo->lastInsertId()]);
}

function updateScholarship() {
    global $pdo;
    $data = $_POST;
    $sql = "UPDATE scholarships SET 
            title = :title, 
            description = :description, 
            num_slots = :num_slots, 
            eligible_courses = :eligible_courses, 
            application_link = :application_link, 
            image_url = :image_url 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        ':id' => $data['id'],
        ':title' => $data['title'],
        ':description' => $data['description'],
        ':num_slots' => $data['num_slots'],
        ':eligible_courses' => $data['eligible_courses'],
        ':application_link' => $data['application_link'],
        ':image_url' => $data['image_url']
    ]);
    echo json_encode(['success' => $success]);
}

function deleteScholarship() {
    global $pdo;
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("DELETE FROM scholarships WHERE id = :id");
    $success = $stmt->execute([':id' => $id]);
    echo json_encode(['success' => $success]);
}

