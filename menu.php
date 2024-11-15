<?php
// menu.php
session_start();
require 'config.php';

// ログイン確認
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// おすすめユーザーを取得
$sql = "SELECT u.id, u.username, p.gender, p.bio, p.photo_path 
        FROM users u
        JOIN profiles p ON u.id = p.user_id 
        WHERE u.id != ? 
        AND (p.gender = (SELECT gender FROM profiles WHERE user_id = ?)
             OR p.languages = (SELECT languages FROM profiles WHERE user_id = ?)
             OR p.MBTI = (SELECT MBTI FROM profiles WHERE user_id = ?))
        LIMIT 10";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$recommended_users = [];

while ($row = $result->fetch_assoc()) {
    // いいねが既に押されているかをチェック
    $liked_user_id = $row['id'];
    $check_like_sql = "SELECT COUNT(*) FROM likes WHERE user_id = ? AND liked_user_id = ?";
    $check_like_stmt = $conn->prepare($check_like_sql);
    $check_like_stmt->bind_param("ii", $user_id, $liked_user_id);
    $check_like_stmt->execute();
    $check_like_stmt->bind_result($like_count);
    $check_like_stmt->fetch();
    $row['liked'] = $like_count > 0 ? true : false;

    $recommended_users[] = $row;
    $check_like_stmt->close();
}

// その他の全てのユーザーを取得
$sql_all_users = "SELECT u.id, u.username, p.gender, p.bio, p.photo_path 
                  FROM users u
                  JOIN profiles p ON u.id = p.user_id 
                  WHERE u.id != ? LIMIT 30";
$stmt_all_users = $conn->prepare($sql_all_users);
$stmt_all_users->bind_param("i", $user_id);
$stmt_all_users->execute();
$result_all_users = $stmt_all_users->get_result();
$all_users = [];

while ($row = $result_all_users->fetch_assoc()) {
    $all_users[] = $row;
}

$stmt->close();
$stmt_all_users->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>おすすめユーザー</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" harf="menu.css"/>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .swiper-container {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .swiper-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .swiper-slide {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 400px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            border-radius: 100px;
            padding: 20px;
            max-width: 500px;
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
            object-fit: cover; /* 画像のサイズ調整 */
            object-position: center; /* 画像の中央を表示 */
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #000;
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

        .like-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }

        .like-button:hover:not(:disabled) {
            background-color: #218838;
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
            <a href="boy.php" class="boy-link">男性のおすすめユーザー</a>
            <a href="girl.php" class="girl-link">女性のおすすめユーザー</a>
        </nav>
    </header>

    <div class="content">
        <!-- Swiper -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php foreach ($recommended_users as $user): ?>
                    <div class="swiper-slide">
                        <img src="<?php echo htmlspecialchars($user['photo_path']); ?>" alt="プロフィール写真" class="profile-photo">
                        <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                        <p>性別: <?php echo htmlspecialchars($user['gender']); ?></p>
                        <p><?php echo htmlspecialchars($user['bio']); ?></p>
                        <form action="like_user.php" method="post">
                            <input type="hidden" name="liked_user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="like-button" <?php echo $user['liked'] ? 'disabled' : ''; ?>>
                                <?php echo $user['liked'] ? 'いいね済み' : 'いいね'; ?>
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Swiperボタン -->
        <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        
    </div>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            slidesPerView: 1, // 1人ずつ表示
            spaceBetween: 10, // スライド間のスペース
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            loop: true // スライドをループさせる
        });
    </script>
</body>
</html>
