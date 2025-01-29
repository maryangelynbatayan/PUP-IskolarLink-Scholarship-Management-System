<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$dbname = "iskolarlink_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => "Database connection failed: " . $e->getMessage()]);
    exit;
}

// Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email']) && isset($_POST['student_number']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $student_number = $_POST['student_number'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND student_number = ?");
    $stmt->execute([$email, $student_number]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        echo json_encode(['success' => true, 'name' => $user['first_name'] . ' ' . $user['last_name']]);
    } else {
        echo json_encode(['success' => false, 'message' => "Invalid email, student number, or password."]);
    }
    exit;
}

// Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    echo json_encode(['success' => true]);
    exit;
}

// Edit Profile
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit_profile') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => "Not logged in."]);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];
    $place_of_birth = $_POST['place_of_birth'];

    $stmt = $pdo->prepare("UPDATE users SET contact_number = ?, address = ?, place_of_birth = ? WHERE id = ?");
    $result = $stmt->execute([$contact_number, $address, $place_of_birth, $user_id]);

    if ($result) {
        // Handle profile picture upload
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_pic']['name'];
            $filetype = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array(strtolower($filetype), $allowed)) {
                $new_filename = "profile_" . $user_id . "." . $filetype;
                move_uploaded_file($_FILES['profile_pic']['tmp_name'], "uploads/" . $new_filename);
                
                $stmt = $pdo->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
                $stmt->execute([$new_filename, $user_id]);
            }
        }
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => "Failed to update profile."]);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => "Invalid request."]);
?>