<?php
// random_user.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "testdb";

// データベース接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ランダムなユーザーを取得
$sql = "SELECT u.id, u.username, p.gender, p.bio, p.photo_path, p.age 
        FROM users u
        JOIN profiles p ON u.id = p.user_id 
        ORDER BY RAND() 
        LIMIT 1";
$result = $conn->query($sql);

$user = [];
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
}

// JSON形式で返す
header('Content-Type: application/json');
echo json_encode($user);

// データベース接続を閉じる
$conn->close();
?>
