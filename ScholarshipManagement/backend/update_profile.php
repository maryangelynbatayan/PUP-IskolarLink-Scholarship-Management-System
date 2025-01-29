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

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit;
    }

    $mobileNumber = filter_var($_POST['mobileNumber'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $placeOfBirth = filter_var($_POST['placeOfBirth'], FILTER_SANITIZE_STRING);

    $stmt = $pdo->prepare("
        UPDATE users 
        SET contact_number = :mobile_number, address = :address, place_of_birth = :place_of_birth 
        WHERE id = :user_id
    ");
    $result = $stmt->execute([
        'mobile_number' => $mobileNumber,
        'address' => $address,
        'place_of_birth' => $placeOfBirth,
        'user_id' => $_SESSION['user_id']
    ]);

    if ($result) {
        if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profilePic']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            $maxFileSize = 2 * 1024 * 1024; 
            if (in_array(strtolower($ext), $allowed) && $_FILES['profilePic']['size'] <= $maxFileSize) {
                $newname = "profile_" . $_SESSION['user_id'] . "." . $ext;
                $target = "uploads/" . $newname;

                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }

                if (move_uploaded_file($_FILES['profilePic']['tmp_name'], $target)) {
                    $stmt = $pdo->prepare("
                        UPDATE users 
                        SET profile_img = :profile_img 
                        WHERE id = :user_id
                    ");
                    $stmt->execute([
                        'profile_img' => $target,
                        'user_id' => $_SESSION['user_id']
                    ]);
                    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to upload profile picture']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid file type or file too large']);
                exit;
            }
        } else {
            echo json_encode(['success' => true, 'message' => 'Profile updated successfully without image']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
