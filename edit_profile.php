<?php
// edit_profile.php
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
$sql_select = "SELECT gender, bio, photo_path, nickname, age, languages, tall, category, blood, figure, beer, MBTI, college, Birthplace, birthdate FROM profiles WHERE user_id = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param('i', $user_id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$profile = $result->fetch_assoc();

if (!$profile) {
    // プロフィール情報が存在しない場合、新規作成
    $sql_insert = "INSERT INTO profiles (
    user_id, 
    gender, 
    bio, 
    photo_path, 
    nickname, 
    age, 
    languages, 
    tall, 
    category, 
    blood, 
    figure, 
    beer, 
    MBTI, 
    college, 
    Birthplace, 
    birthdate
    ) VALUES (
    ?, 
    '未設定', 
    '未設定', 
    '', 
    '未設定', 
    '未設定', 
    '未設定', 
    '未設定', 
    '未設定', 
    '未設定', 
    '未設定', 
    '未設定', 
    '未設定', 
    '未設定', 
    '未設定',
    '未設定'
    )";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param('i', $user_id);
    $stmt_insert->execute();
    $stmt_insert->close();

    // プロフィール情報の再取得
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $profile = $result->fetch_assoc();
}

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

// フォームが送信された場合
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_POST['gender'];
    $bio = $_POST['bio'];
    $nickname = $_POST['nickname'];
    $age = $_POST['age'];
    $languages = $_POST['languages'];
    $tall = $_POST['tall'];
    $category = $_POST['category'];
    $blood = $_POST['blood'];
    $figure = $_POST['figure'];
    $beer = $_POST['beer'];
    $MBTI = $_POST['MBTI'];
    $college = $_POST['college'];
    $Birthpalace = $_POST['Birthplace'];
    $birthdate = $_POST['birthdate'];
    $photo_path = $profile['photo_path']; // デフォルトでは既存のパス

    // 写真がアップロードされた場合
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";

        // ディレクトリが存在しない場合は作成
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_path = $target_file;
        } else {
            $message = "ファイルのアップロードに失敗しました。";
        }
    }

    // プロフィール情報の更新
    $sql_update = "UPDATE profiles SET gender = ?, bio = ?, photo_path = ?, nickname = ?, age = ?, languages = ?, tall = ?, category = ?, blood = ?, figure = ?, beer = ?, MBTI = ?, college = ?, Birthplace = ? WHERE user_id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('ssssssssssssssi', $gender, $bio, $photo_path, $nickname, $age, $languages, $tall, $category, $blood, $figure, $beer, $MBTI, $college, $Birthpalace, $user_id);

    if ($stmt_update->execute()) {
        $message = "プロフィールが更新されました。";
    } else {
        $message = "プロフィールの更新に失敗しました。";
    }

    $stmt_update->close();

    // 更新後のプロフィール情報を再取得
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $profile = $result->fetch_assoc();

    foreach ($default_profile as $key => $value) {
        if (!array_key_exists($key, $profile)) {
            $profile[$key] = $value;
        }
    }
}

$stmt_select->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール編集</title>
    <link rel="stylesheet" href="edit_profile.css">
</head>
<body>
    <header>
        <h1>プロフィール編集</h1>
        <nav>
            <a href="setting.php">マイページに戻る</a><br>
            <a href="view_profile.php">プロフィールを見る</a>
        </nav>
    </header>
    <main>
        <?php if (isset($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="edit_profile.php" method="post" enctype="multipart/form-data">
        
        <div style="display:inline-block;">
            <label for="gender">性別:</label><br>
            <select name="gender" id="gender" required>
                <option value="男性" <?php echo $profile['gender'] == '男性' ? 'selected' : ''; ?>>男性</option>
                <option value="女性" <?php echo $profile['gender'] == '女性' ? 'selected' : ''; ?>>女性</option>
                <option value="その他" <?php echo $profile['gender'] == 'その他' ? 'selected' : ''; ?>>その他</option>
            </select>
        
        </div>
            <br>
            <label for="bio">自己紹介:</label><br>
            <textarea name="bio" id="bio" required><?php echo htmlspecialchars($profile['bio']); ?></textarea>
            <br>

            <label for="photo">プロフィール写真:</label><br>
            <input type="file" name="photo" id="photo">
            <?php if (!empty($profile['photo_path'])): ?>
                <img src="<?php echo htmlspecialchars($profile['photo_path']); ?>" alt="プロフィール写真" width="100">
            <?php endif; ?>
            <br>
        <div style="display:inline-block;">
            
            <label for="nickname" >ニックネーム:</label><br>
            <input type="text" name="nickname" value="<?php echo htmlspecialchars($profile['nickname']); ?>"><br>

            <label for="languages">言語:</label><br>
            <input type="text" name="languages" value="<?php echo htmlspecialchars($profile['languages']); ?>"><br>

            
            <label for="category">学年:</label><br>
            <select name="category" id="category" required>
            <option value="1年生" <?php echo $profile['category'] == '1年生' ? 'selected' : ''; ?>>1年生</option>
            <option value="2年生" <?php echo $profile['category'] == '2年生' ? 'selected' : ''; ?>>2年生</option>
            <option value="3年生" <?php echo $profile['category'] == '3年生' ? 'selected' : ''; ?>>3年生</option>
            <option value="4年生" <?php echo $profile['category'] == '4年生' ? 'selected' : ''; ?>>4年生</option>
            </select>
            <br>
            
            <label for="figure">体型:</label><br>
            <select name="figure" id="figure" required>
                <option value="スリム" <?php echo $profile['figure'] == 'スリム' ? 'selected' : ''; ?>>スリム</option>
                <option value="普通" <?php echo $profile['figure'] == '普通' ? 'selected' : ''; ?>>普通</option>
                <option value="がっちり" <?php echo $profile['figure'] == 'がっちり' ? 'selected' : ''; ?>>がっちり</option>
                <option value="ぽっちゃり" <?php echo $profile['figure'] == 'ぽっちゃり' ? 'selected' : ''; ?>>ぽっちゃり</option>
            </select>
            <br>

            
            <label for="MBTI">MBTI:</label><br>
            <select name="MBTI" id="MBTI" required>
                <option value="INTJ" <?php echo $profile['MBTI'] == 'INTJ' ? 'selected' : ''; ?>>INTJ</option>
                <option value="INTP" <?php echo $profile['MBTI'] == 'INTP' ? 'selected' : ''; ?>>INTP</option>
                <option value="ENTJ" <?php echo $profile['MBTI'] == 'ENTJ' ? 'selected' : ''; ?>>ENTJ</option>
                <option value="ENTP" <?php echo $profile['MBTI'] == 'ENTP' ? 'selected' : ''; ?>>ENTP</option>
                <option value="INFJ" <?php echo $profile['MBTI'] == 'INFJ' ? 'selected' : ''; ?>>INFJ</option>
                <option value="INFP" <?php echo $profile['MBTI'] == 'INFP' ? 'selected' : ''; ?>>INFP</option>
                <option value="ENFJ" <?php echo $profile['MBTI'] == 'ENFJ' ? 'selected' : ''; ?>>ENFJ</option>
                <option value="ENFP" <?php echo $profile['MBTI'] == 'ENFP' ? 'selected' : ''; ?>>ENFP</option>
                <option value="ISTJ" <?php echo $profile['MBTI'] == 'ISTJ' ? 'selected' : ''; ?>>ISTJ</option>
                <option value="ISFJ" <?php echo $profile['MBTI'] == 'ISFJ' ? 'selected' : ''; ?>>ISFJ</option>
                <option value="ESTJ" <?php echo $profile['MBTI'] == 'ESTJ' ? 'selected' : ''; ?>>ESTJ</option>
                <option value="ESFJ" <?php echo $profile['MBTI'] == 'ESFJ' ? 'selected' : ''; ?>>ESFJ</option>
                <option value="ISTP" <?php echo $profile['MBTI'] == 'ISTP' ? 'selected' : ''; ?>>ISTP</option>
                <option value="ISFP" <?php echo $profile['MBTI'] == 'ISFP' ? 'selected' : ''; ?>>ISFP</option>
                <option value="ESTP" <?php echo $profile['MBTI'] == 'ESTP' ? 'selected' : ''; ?>>ESTP</option>
                <option value="ESFP" <?php echo $profile['MBTI'] == 'ESFP' ? 'selected' : ''; ?>>ESFP</option>
            </select>
            <br>

            <label for="Birthplace">出身地:</label><br>
                <select name="Birthplace" id="Birthplace" required>
                <option value="北海道" <?php echo $profile['Birthplace'] == '北海道' ? 'selected' : ''; ?>>北海道</option>
                    <option value="青森" <?php echo $profile['Birthplace'] == '青森' ? 'selected' : ''; ?>>青森</option>
                    <option value="岩手" <?php echo $profile['Birthplace'] == '岩手' ? 'selected' : ''; ?>>岩手</option>
                    <option value="宮城" <?php echo $profile['Birthplace'] == '宮城' ? 'selected' : ''; ?>>宮城</option>
                    <option value="秋田" <?php echo $profile['Birthplace'] == '秋田' ? 'selected' : ''; ?>>秋田</option>
                    <option value="山形" <?php echo $profile['Birthplace'] == '山形' ? 'selected' : ''; ?>>山形</option>
                    <option value="福島" <?php echo $profile['Birthplace'] == '福島' ? 'selected' : ''; ?>>福島</option>
                    <option value="茨城" <?php echo $profile['Birthplace'] == '茨城' ? 'selected' : ''; ?>>茨城</option>
                    <option value="栃木" <?php echo $profile['Birthplace'] == '栃木' ? 'selected' : ''; ?>>栃木</option>
                    <option value="群馬" <?php echo $profile['Birthplace'] == '群馬' ? 'selected' : ''; ?>>群馬</option>
                    <option value="埼玉" <?php echo $profile['Birthplace'] == '埼玉' ? 'selected' : ''; ?>>埼玉</option>
                    <option value="千葉" <?php echo $profile['Birthplace'] == '千葉' ? 'selected' : ''; ?>>千葉</option>
                    <option value="東京" <?php echo $profile['Birthplace'] == '東京' ? 'selected' : ''; ?>>東京</option>
                    <option value="神奈川" <?php echo $profile['Birthplace'] == '神奈川' ? 'selected' : ''; ?>>神奈川</option>
                    <option value="新潟" <?php echo $profile['Birthplace'] == '新潟' ? 'selected' : ''; ?>>新潟</option>
                    <option value="富山" <?php echo $profile['Birthplace'] == '富山' ? 'selected' : ''; ?>>富山</option>
                    <option value="石川" <?php echo $profile['Birthplace'] == '石川' ? 'selected' : ''; ?>>石川</option>
                    <option value="福井" <?php echo $profile['Birthplace'] == '福井' ? 'selected' : ''; ?>>福井</option>
                    <option value="山梨" <?php echo $profile['Birthplace'] == '山梨' ? 'selected' : ''; ?>>山梨</option>
                    <option value="長野" <?php echo $profile['Birthplace'] == '長野' ? 'selected' : ''; ?>>長野</option>
                    <option value="岐阜" <?php echo $profile['Birthplace'] == '岐阜' ? 'selected' : ''; ?>>岐阜</option>
                    <option value="静岡" <?php echo $profile['Birthplace'] == '静岡' ? 'selected' : ''; ?>>静岡</option>
                    <option value="愛知" <?php echo $profile['Birthplace'] == '愛知' ? 'selected' : ''; ?>>愛知</option>
                    <option value="三重" <?php echo $profile['Birthplace'] == '三重' ? 'selected' : ''; ?>>三重</option>
                    <option value="滋賀" <?php echo $profile['Birthplace'] == '滋賀' ? 'selected' : ''; ?>>滋賀</option>
                    <option value="京都" <?php echo $profile['Birthplace'] == '京都' ? 'selected' : ''; ?>>京都</option>
                    <option value="大阪" <?php echo $profile['Birthplace'] == '大阪' ? 'selected' : ''; ?>>大阪</option>
                    <option value="兵庫" <?php echo $profile['Birthplace'] == '兵庫' ? 'selected' : ''; ?>>兵庫</option>
                    <option value="奈良" <?php echo $profile['Birthplace'] == '奈良' ? 'selected' : ''; ?>>奈良</option>
                    <option value="和歌山" <?php echo $profile['Birthplace'] == '和歌山' ? 'selected' : ''; ?>>和歌山</option>
                    <option value="鳥取" <?php echo $profile['Birthplace'] == '鳥取' ? 'selected' : ''; ?>>鳥取</option>
                    <option value="島根" <?php echo $profile['Birthplace'] == '島根' ? 'selected' : ''; ?>>島根</option>
                    <option value="岡山" <?php echo $profile['Birthplace'] == '岡山' ? 'selected' : ''; ?>>岡山</option>
                    <option value="広島" <?php echo $profile['Birthplace'] == '広島' ? 'selected' : ''; ?>>広島</option>
                    <option value="山口" <?php echo $profile['Birthplace'] == '山口' ? 'selected' : ''; ?>>山口</option>
                    <option value="徳島" <?php echo $profile['Birthplace'] == '徳島' ? 'selected' : ''; ?>>徳島</option>
                    <option value="香川" <?php echo $profile['Birthplace'] == '香川' ? 'selected' : ''; ?>>香川</option>
                    <option value="愛媛" <?php echo $profile['Birthplace'] == '愛媛' ? 'selected' : ''; ?>>愛媛</option>
                    <option value="高知" <?php echo $profile['Birthplace'] == '高知' ? 'selected' : ''; ?>>高知</option>
                    <option value="福岡" <?php echo $profile['Birthplace'] == '福岡' ? 'selected' : ''; ?>>福岡</option>
                    <option value="佐賀" <?php echo $profile['Birthplace'] == '佐賀' ? 'selected' : ''; ?>>佐賀</option>
                    <option value="長崎" <?php echo $profile['Birthplace'] == '長崎' ? 'selected' : ''; ?>>長崎</option>
                    <option value="熊本" <?php echo $profile['Birthplace'] == '熊本' ? 'selected' : ''; ?>>熊本</option>
                    <option value="大分" <?php echo $profile['Birthplace'] == '大分' ? 'selected' : ''; ?>>大分</option>
                    <option value="宮崎" <?php echo $profile['Birthplace'] == '宮崎' ? 'selected' : ''; ?>>宮崎</option>
                    <option value="鹿児島" <?php echo $profile['Birthplace'] == '鹿児島' ? 'selected' : ''; ?>>鹿児島</option>
                    <option value="沖縄" <?php echo $profile['Birthplace'] == '沖縄' ? 'selected' : ''; ?>>沖縄</option>
                </select>
                <br>


        </div>
        <div style="display:inline-block; float:right;">
            <label for="age">年齢:</label><br>
            <input type="text" name="age" value="<?php echo htmlspecialchars($profile['age']); ?>"><br>

            
            <label for="tall">身長:</label><br>
            <input type="text" name="tall" value="<?php echo htmlspecialchars($profile['tall']); ?>"><br>

            
            <label for="blood">血液型:</label><br>
            <select name="blood" id="blood" required>
                <option value="A型" <?php echo $profile['blood'] == 'A型' ? 'selected' : ''; ?>>A型</option>
                <option value="B型" <?php echo $profile['blood'] == 'B型' ? 'selected' : ''; ?>>B型</option>
                <option value="O型" <?php echo $profile['blood'] == 'O型' ? 'selected' : ''; ?>>O型</option>
                <option value="AB型" <?php echo $profile['blood'] == 'AB型' ? 'selected' : ''; ?>>AB型</option>
            </select>
            <br>

            
            <label for="beer">お酒:</label><br>
            <select name="beer" id="beer" required>
                <option value="毎日飲む" <?php echo $profile['beer'] == '毎日飲む' ? 'selected' : ''; ?>>毎日飲む</option>
                <option value="たまに飲む" <?php echo $profile['beer'] == 'たまに飲む' ? 'selected' : ''; ?>>たまに飲む</option>
                <option value="あまり飲まない" <?php echo $profile['beer'] == 'あまり飲まない' ? 'selected' : ''; ?>>あまり飲まない</option>
                <option value="飲まない" <?php echo $profile['beer'] == '飲まない' ? 'selected' : ''; ?>>飲まない</option>
            </select>
            <br>

            <label for="college">カレッジ:</label><br>
            <select name="college" id="college" required>
                <option value="クリエイターズカレッジ" <?php echo $profile['college'] == 'クリエイターズカレッジ' ? 'selected' : ''; ?>>クリエイターズカレッジ</option>
                <option value="デザインカレッジ" <?php echo $profile['college'] == 'デザインカレッジ' ? 'selected' : ''; ?>>デザインカレッジ</option>
                <option value="ミュージックカレッジ" <?php echo $profile['college'] == 'ミュージックカレッジ' ? 'selected' : ''; ?>>ミュージックカレッジ</option>
                <option value="ITカレッジ" <?php echo $profile['college'] == 'ITカレッジ' ? 'selected' : ''; ?>>ITカレッジ</option>
                <option value="テクノロジーカレッジ" <?php echo $profile['college'] == 'テクノロジーカレッジ' ? 'selected' : ''; ?>>テクノロジーカレッジ</option>
                <option value="スポーツ・医療カレッジ" <?php echo $profile['college'] == 'スポーツ・医療カレッジ' ? 'selected' : ''; ?>>スポーツ・医療カレッジ</option>
            </select>
            <br>

        </div>

        <label for="birthdate">生年月日:</label>
        <input type="date" name="birthdate" id="birthdate" value="<?php echo htmlspecialchars($profile['birthdate']); ?>" required><br>

        <input type="submit" value="保存" class="button">
        </form>
    </main>
</body>
</html>






    