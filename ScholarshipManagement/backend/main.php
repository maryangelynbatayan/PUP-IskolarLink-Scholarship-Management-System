<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$host = "localhost";
$dbname = "iskolarlink_db";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => "Database connection failed. Please try again later."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email']) && isset($_POST['student-number']) && isset($_POST['password'])) {
        if (isset($_POST['first-name']) && isset($_POST['last-name'])) {
            // Registration
            $firstName = trim($_POST['first-name']);
            $lastName = trim($_POST['last-name']);
            $email = trim($_POST['email']);
            $studentNumber = trim($_POST['student-number']);
            $password = $_POST['password'];

            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR student_number = :student_number");
                $stmt->execute(['email' => $email, 'student_number' => $studentNumber]);

                if ($stmt->rowCount() > 0) {
                    echo json_encode(['success' => false, 'message' => 'User with this email or student number already exists.']);
                    exit;
                }

                $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, student_number, password) VALUES (:first_name, :last_name, :email, :student_number, :password)");
                $result = $stmt->execute([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'student_number' => $studentNumber,
                    'password' => $password 
                ]);

                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Registration successful']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
                }
            } catch (PDOException $e) {
                error_log("Registration error: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again later. Error: ' . $e->getMessage()]);
            }
        } else {
            // Login
            $email = trim($_POST['email']);
            $studentNumber = trim($_POST['student-number']);
            $password = $_POST['password'];

            try {
                $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND student_number = :student_number");
                $stmt->execute(['email' => $email, 'student_number' => $studentNumber]);

                if ($stmt->rowCount() == 1) {
                    $user = $stmt->fetch();
                    if ($password === $user['password']) {
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['email'] = $user['email'];
                        echo json_encode(['success' => true, 'message' => 'Login successful']);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Invalid email, student number, or password.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Invalid email, student number, or password.']);
                }
            } catch (PDOException $e) {
                error_log("Login error: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Login failed. Please try again later. Error: ' . $e->getMessage()]);
            }
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid form data.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>