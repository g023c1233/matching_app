<?php
// send_message.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$message = $data['message'];
$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO messages (user_id, message) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('is', $user_id, $message);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

echo json_encode($response);
?>
