<?php
// girl.php
session_start();
require 'config.php';

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// 女性のおすすめユーザーを取得
$sql = "SELECT u.id, u.username, p.gender, p.photo_path, p.age 
        FROM users u
        JOIN profiles p ON u.id = p.user_id 
        WHERE u.id != ? AND p.gender = '女性'
        LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$recommended_users = [];

while ($row = $result->fetch_assoc()) {
    $recommended_users[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>女性のおすすめユーザー</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .content {
            margin: 0;
            padding: 0 20px;
        }

        .user-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 1行に2人表示 */
            gap: 20px;
            padding: 20px;
            justify-items: center; /* 各要素を中央揃え */
        }

        .user-item {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 550px; /* 幅を固定 */
            text-align: center;
        }

        .user-item img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
            object-fit: cover; /* 画像のサイズ調整 */
            object-position: center; /* 画像の中央を表示 */
        }
    </style>
</head>
<body>
    <header>
        <h1>さがす</h1>
        <nav>
            <a href="like.php">いいね！</a>
            <a href="chat.php">チャット</a>
            <a href="setting.php">マイページ</a><br>
            <a href="search_form.php">検索</a><br>
            <a href="boy.php">男性のおすすめユーザー</a>
            <a href="girl.php">女性のおすすめユーザー</a>
        </nav>
    </header>
    <div class="user-list">
        <?php foreach ($recommended_users as $user): ?>
            <div class="user-item">
                <a href="user_detail.php?id=<?php echo $user['id']; ?>">
                    <img src="<?php echo htmlspecialchars($user['photo_path']); ?>" alt="プロフィール写真" width="100">
                    <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                    <p>性別: <?php echo htmlspecialchars($user['gender']); ?></p>
                    <p>年齢: <?php echo htmlspecialchars($user['age']); ?></p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
