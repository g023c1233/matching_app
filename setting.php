<?php
// setting.php
// セッション開始
session_start();

// データベース接続
require 'config.php';

// ユーザー情報の取得
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>
<link href="setting.css" rel="stylesheet">



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイページ</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* チャットリンクの無効化スタイル */
        a.disabled {
            pointer-events: none;  /* クリックを無効化 */
            cursor: default;        /*通常のマウスカーソルに変更 */
            text-decoration: none;  /* 下線も削除 */
        }
    </style>


</head>
<body>
    <header>
        
            <div class="menu">
                <button href="menu.php">さがす</button>
                <button href="matches.php">いいね！</button>
                <button href="chat.php">チャット</button>
                <button class="disabled" >マイページ</button>
            </div>
            <h1>マイページ</h1>
    </header>

    <main>
        
        <p>メールアドレス: <?php echo htmlspecialchars($user['email']); ?></p>
        <a href="edit_profile.php">プロフィールを編集する</a><br>
        <a href="logout.php">ログアウト</a>
    </main>
</body>
</html>
