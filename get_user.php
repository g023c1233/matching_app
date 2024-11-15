<?php
// get_user.php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['username' => '']);
    exit;
}

require 'config.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT username FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo json_encode(['username' => $user['username']]);
?>
