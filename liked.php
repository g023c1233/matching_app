<?php
// エラーメッセージ表示を有効にする
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッション開始
session_start();

// データベース接続
$mysqli = new mysqli("localhost", "root", "", "matching_app");
if ($mysqli->connect_error) {
    die("接続失敗: " . $mysqli->connect_error);
}

// ログイン中のユーザーのIDを取得
$current_user_id = $_SESSION['user_id'] ?? null;

// ユーザーがログインしているか確認
if (!$current_user_id) {
    die("ログインしてください。");
}

// いいねを送ってきたユーザーの情報を取得するSQLクエリ
$query = "SELECT u.id, u.username, u.created_at, l.liked_at, p.nickname, p.photo_path, p.gender, p.bio
        FROM likes l
        JOIN users u ON l.user_id = u.id
        JOIN profiles p ON u.id = p.user_id
        WHERE l.liked_user_id = ?
        ORDER BY l.liked_at DESC";

// クエリの準備
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die("SQLエラー: " . $mysqli->error);
}

// パラメータをバインドしてクエリを実行
$stmt->bind_param("i", $current_user_id);
$stmt->execute();

// 結果の取得
$result = $stmt->get_result();
$liked_by_users = $result->fetch_all(MYSQLI_ASSOC);

// HTMLで結果を表示
?>

<!DOCTYPE html>
<html lang="ja">
<link rel="stylesheet" href="liked.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>いいねされた相手</title>

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
    <h1>いいねされた相手の一覧</h1>

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
    <?php if (!empty($liked_by_users)): ?>
            <?php foreach ($liked_by_users as $user): ?>
                <div class="user-item">
                <?php if (!empty($user['photo_path'])): ?>
                        <img src="<?php echo htmlspecialchars($user['photo_path']); ?>" alt="プロフィール写真">
                    <?php else: ?>
                        <p>写真がありません</p>
                    <?php endif; ?>

                    <h3><?php echo htmlspecialchars($user['nickname'] ?: $user['username']); ?></h3>
                    <p>性別: <?php echo htmlspecialchars($user['gender']); ?></p>

                    <p><?php echo htmlspecialchars($user['bio']); ?></p>
                    
                    </div>
            <?php endforeach; ?>
    <?php else: ?>
        <p>まだ誰もあなたにいいねをしていません。</p>
    <?php endif; ?>
    </section>
</body>
</html>

<?php
// データベース接続のクローズ
if (isset($stmt)) {
    $stmt->close();
}
$mysqli->close();
?>


