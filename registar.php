<?php
// registar.php
require 'config.php'; // データベース接続

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    
    if ($stmt->execute()) {
        echo "ユーザー登録成功";
    } else {
        echo "エラー: " . $stmt->error;
    }
}
?>
<link href="registar.css" rel="stylesheet">
<div class="container">
<form method="post">
     <input type="text" name="username" placeholder="Username" required><br>
     <input type="email" name="email" placeholder="Email" required><br>
     <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">登録</button>
</form>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <div>
        <input type="checkbox" id="scales" name="scales" checked />
        <label for="scales">Scales</label>
    </div>
    <a href="home.php" class="back">ホームに戻る</a>
</body>
</html>
