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
    case 'getOverview':
        getOverview();
        break;
    case 'getAllUsers':
        getAllUsers();
        break;
    case 'getUser':
        getUser();
        break;
    case 'createUser':
        createUser();
        break;
    case 'updateUser':
        updateUser();
        break;
    case 'deleteUser':
        deleteUser();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
}

function getOverview() {
    global $pdo;
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $newUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)")->fetchColumn();
    echo json_encode([
        'totalUsers' => $totalUsers,
        'newUsers' => $newUsers
    ]);
}

function getAllUsers() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, first_name, last_name, email, student_number, gender, date_of_birth, place_of_birth, contact_number, address, created_at FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

function getUser() {
    global $pdo;
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("SELECT id, first_name, last_name, email, student_number, gender, date_of_birth, place_of_birth, contact_number, address FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
}

function createUser() {
    global $pdo;
    $data = $_POST;
    $sql = "INSERT INTO users (first_name, last_name, email, student_number, password, gender, date_of_birth, place_of_birth, contact_number, address, created_at) 
            VALUES (:first_name, :last_name, :email, :student_number, :password, :gender, :date_of_birth, :place_of_birth, :contact_number, :address, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':first_name' => $data['first_name'],
        ':last_name' => $data['last_name'],
        ':email' => $data['email'],
        ':student_number' => $data['student_number'],
        ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ':gender' => $data['gender'],
        ':date_of_birth' => $data['date_of_birth'],
        ':place_of_birth' => $data['place_of_birth'],
        ':contact_number' => $data['contact_number'],
        ':address' => $data['address']
    ]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
}

function updateUser() {
    global $pdo;
    $data = $_POST;
    $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, student_number = :student_number, 
            gender = :gender, date_of_birth = :date_of_birth, place_of_birth = :place_of_birth, contact_number = :contact_number, 
            address = :address WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([
        ':id' => $data['id'],
        ':first_name' => $data['first_name'],
        ':last_name' => $data['last_name'],
        ':email' => $data['email'],
        ':student_number' => $data['student_number'],
        ':gender' => $data['gender'],
        ':date_of_birth' => $data['date_of_birth'],
        ':place_of_birth' => $data['place_of_birth'],
        ':contact_number' => $data['contact_number'],
        ':address' => $data['address']
    ]);
    echo json_encode(['success' => $success]);
}

function deleteUser() {
    global $pdo;
    $id = $_GET['id'] ?? 0;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $success = $stmt->execute([':id' => $id]);
    echo json_encode(['success' => $success]);
}
?>
