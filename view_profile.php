<?php
// view_profile.php
session_start();

// ログインしていない場合、ログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require 'config.php';

// ユーザーIDの取得
$user_id = $_SESSION['user_id'];

// プロフィール情報の取得
$sql = "SELECT gender, bio, photo_path, nickname, age, languages, tall, category, blood, figure, beer, MBTI, college, Birthplace, birthdate FROM profiles WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();
$stmt->close();

$conn->close();

// デフォルト値の設定
$default_profile = [
    'gender' => '未設定',
    'bio' => '未設定',
    'photo_path' => '',
    'nickname' => '未設定',
    'age' => '未設定',
    'languages' => '未設定',
    'tall' => '未設定',
    'category' => '未設定',
    'blood' => '未設定',
    'figure' => '未設定',
    'beer' => '未設定',
    'MBTI' => '未設定',
    'college' => '未設定',
    'Birthplace' => '未設定',
    'birthdate' => ''
];

foreach ($default_profile as $key => $value) {
    if (!array_key_exists($key, $profile)) {
        $profile[$key] = $value;
    }
}
?>

<link rel="stylesheet" href="view_profile.css">

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール表示</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>プロフィール</h1>
        <nav>
            <a href="setting.php">マイページに戻る</a>
            <a href="edit_profile.php">プロフィールを編集する</a>
        </nav>
    </header>

    <main>
        <h2>プロフィール情報</h2>
        <?php if (!empty($profile['photo_path'])): ?>
            <img src="<?php echo htmlspecialchars($profile['photo_path']); ?>" alt="プロフィール写真"class="profile-photo" width="100">
        <?php endif; ?>
        <p class="profile-info"><?php echo htmlspecialchars($profile['nickname']); ?>:<!-- 名前 -->
        <?php echo htmlspecialchars($profile['gender']); ?>:<!-- 性別 -->
        <?php echo htmlspecialchars($profile['age']); ?>歳</p><!-- 年齢 -->
        <p class="bio"><?php echo htmlspecialchars($profile['bio']); ?></p>
        
        <div class="container">
        <div class="box-1">
        <p><?php echo htmlspecialchars($profile['languages']); ?></p>
        </div>
        <div class="box-2">
        <p><?php echo htmlspecialchars($profile['tall']); ?>cm</p>
        </div>
        <div class="box-3">
        <p><?php echo htmlspecialchars($profile['category']); ?></p>
        </div>
        </div>
        <div class="container">
        <div class="box-4">
        <p><?php echo htmlspecialchars($profile['blood']); ?></p>
        </div>
        <div class="box-5">
        <p>体型: <?php echo htmlspecialchars($profile['figure']); ?></p>
        </div>
        <div class="box-6">
        <p>生年月日: <?php echo htmlspecialchars($profile['birthdate']); ?></p>
        </div>
        </div>
        <div class="container">
        <div class="box-7">
        <p><?php echo htmlspecialchars($profile['Birthplace']); ?>出身</p>
        </div>
        <div class="box-8">
        <p><?php echo htmlspecialchars($profile['MBTI']); ?></p>
        </div>
        <div class="box-9">
        <p><?php echo htmlspecialchars($profile['college']); ?></p>
        </div>
        </div>
        <div class="container">
        <div class="box-10">
        <p>お酒: <?php echo htmlspecialchars($profile['beer']); ?></p>
        </div>
        </div>
    
    </main>
</body>
</html>

