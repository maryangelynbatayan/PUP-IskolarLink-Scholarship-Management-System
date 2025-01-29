<?php
session_start();
header('Content-Type: application/json');

session_destroy();

echo json_encode(['success' => true, 'message' => 'Signed out successfully']);
?>

