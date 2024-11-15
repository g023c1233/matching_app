<?php
// get_message.php
require 'config.php';

$sql = "SELECT users.username AS user, messages.message AS message FROM messages JOIN users ON messages.user_id = users.id ORDER BY messages.created_at DESC";
$result = $conn->query($sql);

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode($messages);
?>
