<?php
session_start();
require 'config.php';

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_GET['id'];

// 選択されたユーザーの詳細を取得
$sql = "SELECT u.username, p.gender, p.bio, p.photo_path, p.category, p.blood, p.figure, p.beer, p.MBTI, p.college, p.nickname, p.age, p.languages, p.tall, p.Birthplace, p.birthdate FROM users u JOIN profiles p ON u.id = p.user_id WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($user['username']); ?> の詳細</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .profile {
            margin-top: 50px;
            width: 1000px;
            height: 850px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: inline-block;
            border-radius: 100px;
        }

        .profile img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .like-button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .like-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="profile">
        <img src="<?php echo htmlspecialchars($user['photo_path']); ?>" alt="プロフィール写真">
        <h2><?php echo htmlspecialchars($user['username']); ?></h2>
        <p>ニックネーム: <?php echo htmlspecialchars($user['nickname']); ?></p>
        <p>年齢: <?php echo htmlspecialchars($user['age']); ?></p>
        <p>性別: <?php echo htmlspecialchars($user['gender']); ?></p>
        <p>学年: <?php echo htmlspecialchars($user['category']); ?></p>
        <p>血液型: <?php echo htmlspecialchars($user['blood']); ?></p>
        <p>体型: <?php echo htmlspecialchars($user['figure']); ?></p>
        <p>お酒: <?php echo htmlspecialchars($user['beer']); ?></p>
        <p>MBTI: <?php echo htmlspecialchars($user['MBTI']); ?></p>
        <p>カレッジ: <?php echo htmlspecialchars($user['college']); ?></p>
        <p>言語: <?php echo htmlspecialchars($user['languages']); ?></p>
        <p>身長: <?php echo htmlspecialchars($user['tall']); ?></p>
        <p>出身地: <?php echo htmlspecialchars($user['Birthplace']); ?></p>
        <p>生年月日: <?php echo htmlspecialchars($user['birthdate']); ?></p>
        <p>自己紹介: <?php echo htmlspecialchars($user['bio']); ?></p>

        <!-- いいねボタン -->
        <form action="like_user.php" method="post">
            <input type="hidden" name="liked_user_id" value="<?php echo $user_id; ?>">
            <button type="submit" class="like-button">いいね</button>
        </form>
    </div>
</body>
</html>
