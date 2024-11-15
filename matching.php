<?php
session_start();
require 'config.php';

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// お互いに「いいね」したユーザーを取得
$sql = "SELECT u.id, u.username, p.gender, p.bio, p.photo_path 
        FROM likes l1
        JOIN likes l2 ON l1.liked_user_id = l2.user_id AND l1.user_id = l2.liked_user_id
        JOIN users u ON l1.liked_user_id = u.id
        JOIN profiles p ON u.id = p.user_id
        WHERE l1.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$mutual_likes = [];

while ($row = $result->fetch_assoc()) {
    $mutual_likes[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="matching.css">
<head>
    <meta charset="UTF-8">
    <title>相互いいねしたユーザー一覧</title>
    
</head>
<body>
    <header>
        <h1>チャット</h1>
        <nav>
            <a href="menu.php">さがす</a>
            <a href="setting.php">マイページ</a><br>
        </nav>
    </header>

    <!-- ユーザー一覧 -->
    
    <!-- 相互にいいねしたユーザー一覧 -->
<section class="user-list">
    <?php if (empty($mutual_likes)): ?>
        <p>まだ相互に「いいね」したユーザーはいません。</p>
    <?php else: ?>
        <?php foreach ($mutual_likes as $user): ?>
            <li>
               
                    <a href="chat.php?user_id=<?php echo $user['id']; ?>"> 
                <div class="user-item">
                <figure class="phot"><img src="<?php echo htmlspecialchars($user['photo_path']); ?>" alt="プロフィール写真"></figure>
                <p class="text"><?php echo htmlspecialchars($user['username']); ?></p>
            </a> <!-- チャットリンク追加 -->
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
</body>
</html>
