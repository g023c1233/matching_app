<?php
// login.php
require 'config.php'; // データベース接続

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['user_id'] = $id;
            header("Location: menu.php");
        } else {
            echo "パスワードが間違っています";
        }
    } else {
        echo "ユーザーが見つかりません";
    }
}
?>
<link href="login.css" rel="stylesheet">

<div class="container">
<form method="post">
    <img src="home.png" alt="ロゴ" class="logo">
    <input type="email" name="email" placeholder="Email" required ><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
 
    <a href="home.php" class="back">↩</a>
</body>
</html>