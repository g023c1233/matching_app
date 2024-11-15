<?php
// matches.php
require 'config.php';
session_start();

// ログインしていない場合、ログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// ユーザーIDの取得
$user_id = $_SESSION['user_id'];

// マッチング情報の取得
$stmt = $conn->prepare("SELECT user1_id, user2_id, matched_at FROM matches WHERE user1_id = ? OR user2_id = ?");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マッチ一覧</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>いいね！</h1>
        <nav>
            <a href="menu.php">さがす</a>
            <a href="chat.php">チャット</a>
            <a href="setting.php">マイページ</a><br>
            <a href="matching.php">候補者一覧</a>
        </nav>
    </header>

    <main>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
            // マッチング相手のユーザーIDを取得
            $matched_user_id = ($row['user1_id'] == $user_id) ? $row['user2_id'] : $row['user1_id'];

            // マッチング相手のプロフィール情報を取得
            $profile_stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
            $profile_stmt->bind_param("i", $matched_user_id);
            $profile_stmt->execute();
            $profile_stmt->bind_result($username);
            $profile_stmt->fetch();
            $profile_stmt->close();
            ?>

            <div>
                <p>ユーザー名: <?php echo htmlspecialchars($username); ?></p>
                <p>マッチング日時: <?php echo htmlspecialchars($row['matched_at']); ?></p>
            </div>
            <hr>
        <?php endwhile; ?>
    </main>
</body>
</html>

<?php
// クリーンアップ
$stmt->close();
$conn->close();
?>

