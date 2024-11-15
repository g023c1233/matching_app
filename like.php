<?php
// matches.php
session_start();
require 'config.php';

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// いいねしたユーザーを取得
$sql = "SELECT u.id, u.username, p.gender, p.bio, p.photo_path 
        FROM likes l
        JOIN users u ON l.liked_user_id = u.id
        JOIN profiles p ON u.id = p.user_id
        WHERE l.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$liked_users = [];

while ($row = $result->fetch_assoc()) {
    $liked_users[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="like.css">
<head>
    <meta charset="UTF-8">
    <title>いいねしたユーザー一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .user-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
        }

        .user-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 100px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .user-item img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>いいねしたユーザー</h1>
        <nav>
            <a href="menu.php">さがす</a>
            <a href="chat.php">チャット</a>
            <a href="setting.php" >マイページ</a><br>
        <div class="aaa">
            <a href="like.php" class="like-link">自分から</a>
           <a href="liked.php" class="liked-link">相手から</a>
        </div>
        </nav>
    </header>

    <!-- ユーザー一覧 -->
    <section class="user-list">
        <?php if (empty($liked_users)): ?>
            <p>まだ「いいね」したユーザーはいません。</p>
        <?php else: ?>
            <?php foreach ($liked_users as $user): ?>
                <div class="user-item">
                    <img src="<?php echo htmlspecialchars($user['photo_path']); ?>" alt="プロフィール写真">
                    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                    <p>性別: <?php echo htmlspecialchars($user['gender']); ?></p>
                    <p><?php echo htmlspecialchars($user['bio']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</body>
</html>
