<?php
// search_profile.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "matching_app";

// データベース接続
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 検索キーワードを取得
$query = isset($_GET['query']) ? $_GET['query'] : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';

// プロフィールを検索する関数
function searchProfiles($conn, $query, $gender) {
    $sql = "SELECT u.id, u.username, p.gender, p.bio, p.photo_path 
            FROM users u
            JOIN profiles p ON u.id = p.user_id 
            WHERE (u.username LIKE ? OR p.bio LIKE ?)";

    $params = ["%$query%", "%$query%"];
    $types = "ss";

    if (!empty($gender)) {
        $sql .= " AND p.gender = ?";
        $params[] = $gender;
        $types .= "s";
    }

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);

    $stmt->execute();
    $result = $stmt->get_result();

    $profiles = [];
    while ($row = $result->fetch_assoc()) {
        $profiles[] = $row;
    }
    return $profiles;
}

// 検索結果を取得
$profiles = searchProfiles($conn, $query, $gender);

// データベース接続を閉じる
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索結果</title>
</head>
<body>
    <h1>検索結果</h1>
    <?php if (empty($profiles)): ?>
        <p>検索結果が見つかりませんでした。</p>
    <?php else: ?>
        <ul>
            <?php foreach ($profiles as $profile): ?>
                <li>
                    <h2><?php echo htmlspecialchars($profile['username']); ?></h2>
                    <p>性別: <?php echo htmlspecialchars($profile['gender']); ?></p>
                    <p>自己紹介: <?php echo htmlspecialchars($profile['bio']); ?></p>
                    <?php if (!empty($profile['photo_path'])): ?>
                        <img src="<?php echo htmlspecialchars($profile['photo_path']); ?>" alt="プロフィール写真">
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <a href="search_form.php">戻る</a>
</body>
</html>
