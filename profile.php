<?php
// profile.php
require 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
    $Birthplace = $_POST['Birthplace'];
    $birthdate = $_POST['birthdate'];

    $stmt = $conn->prepare("REPLACE INTO profiles (user_id, gender, bio, nickname, age, languages, tall, category, blood, figure, beer, MBTI, college, Birthplace, birthdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssssssss", $user_id, $gender, $bio, $nickname, $age, $languages, $tall, $category, $blood, $figure, $beer, $MBTI, $college, $Birthplace, $birthdate);
    
    if ($stmt->execute()) {
        echo "プロフィール更新成功";
    } else {
        echo "エラー: " . $stmt->error;
    }
}

$stmt = $conn->prepare("SELECT gender, bio, nickname, age, languages, tall, category, blood, figure, beer, MBTI, college, Birthplace, birthdate FROM profiles WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($gender, $bio, $nickname, $age, $languages, $tall, $category, $blood, $figure, $beer, $MBTI, $college, $Birthplace, $birthdate);
$stmt->fetch();
?>

<form method="post">
    性別: 
    <select name="gender">
        <option value="男性" <?php if ($gender == '男性') echo 'selected'; ?>>男性</option>
        <option value="女性" <?php if ($gender == '女性') echo 'selected'; ?>>女性</option>
        <option value="その他" <?php if ($gender == 'その他') echo 'selected'; ?>>その他</option>
    </select><br>
    自己紹介: <textarea name="bio"><?php echo htmlspecialchars($bio); ?></textarea><br>
    ニックネーム： <input type="text" name="nickname"><?php echo htmlspecialchars($nickname); ?></input><br>
    年齢： <input type="text" name="age"><?php echo htmlspecialchars($age); ?></input><br>
    言語： <input type="text" name="languages"><?php echo htmlspecialchars($languages); ?></input><br>
    身長： <input type="text" name="tall"><?php echo htmlspecialchars($tall); ?></input><br>
    カテゴリー:

    <div class="choices">
    <select name="category">
        <option value="1年生" <?php if ($category == '1年生') echo 'selected'; ?>>1年生</option>
        <option value="2年生" <?php if ($category == '2年生') echo 'selected'; ?>>2年生</option>
        <option value="3年生" <?php if ($category == '3年生') echo 'selected'; ?>>3年生</option>
        <option value="4年生" <?php if ($category == '4年生') echo 'selected'; ?>>4年生</option>
    </select>
    </div><br>

    <div class="blood">
        <select name="blood">
            <option value="A型" <?php if ($blood == 'A型') echo 'selected'; ?>>A型</option>
            <option value="B型" <?php if ($blood == 'B型') echo 'selected'; ?>>B型</option>
            <option value="O型" <?php if ($blood == 'O型') echo 'selected'; ?>>O型</option>
            <option value="AB型" <?php if ($blood == 'AB型') echo 'selected'; ?>>AB型</option>
        </select>
    </div><br>

    <div class="figure">
        <select name="figure">
            <option value="超ほっそり" <?php if ($figure == '超ほっそり') echo 'selected'; ?>>超ほっそり</option>
            <option value="やや細め" <?php if ($figure == 'やや細め') echo 'selected'; ?>>やや細め</option>
            <option value="ふつう" <?php if ($figure == 'ふつう') echo 'selected'; ?>>ふつう</option>
            <option value="ややぽちゃ" <?php if ($figure == 'ややぽちゃ') echo 'selected'; ?>>ややぽちゃ</option>
            <option value="ぽっちゃり" <?php if ($figure == 'ぽっちゃり') echo 'selected'; ?>>ぽっちゃり</option>
        </select>
    </div><br>

    <div class="beer">
        <select name="beer">
            <option value="飲酒" <?php if ($beer == '飲酒') echo 'selected'; ?>>飲酒</option>
            <option value="喫煙" <?php if ($beer == '喫煙') echo 'selected'; ?>>喫煙</option>
            <option value="どちらもあり" <?php if ($beer == 'どちらもあり') echo 'selected'; ?>>どちらもあり</option>
            <option value="どちらもなし" <?php if ($beer == 'どちらもなし') echo 'selected'; ?>>どちらもなし</option>
        </select>
    </div><br>

    <div class="MBTI">
        <select name="MBTI">
            <option value="INTJ" <?php if ($MBTI == 'INTJ') echo 'selected'; ?>>INTJ</option>
            <option value="INTP" <?php if ($MBTI == 'INTP') echo 'selected'; ?>>INTP</option>
            <option value="ENTJ" <?php if ($MBTI == 'ENTJ') echo 'selected'; ?>>ENTJ</option>
            <option value="ENTP" <?php if ($MBTI == 'ENTP') echo 'selected'; ?>>ENTP</option>
            <option value="INFJ" <?php if ($MBTI == 'INFJ') echo 'selected'; ?>>INFJ</option>
            <option value="INFP" <?php if ($MBTI == 'INFP') echo 'selected'; ?>>INFP</option>
            <option value="ENFJ" <?php if ($MBTI == 'ENFJ') echo 'selected'; ?>>ENFJ</option>
            <option value="ENFP" <?php if ($MBTI == 'ENFP') echo 'selected'; ?>>ENFP</option>
            <option value="ISTJ" <?php if ($MBTI == 'ISTJ') echo 'selected'; ?>>ISTJ</option>
            <option value="ISFJ" <?php if ($MBTI == 'ISFJ') echo 'selected'; ?>>ISFJ</option>
            <option value="ESTJ" <?php if ($MBTI == 'ESTJ') echo 'selected'; ?>>ESTJ</option>
            <option value="ESFJ" <?php if ($MBTI == 'ESFJ') echo 'selected'; ?>>ESFJ</option>
            <option value="ISTP" <?php if ($MBTI == 'ISTP') echo 'selected'; ?>>ISTP</option>
            <option value="ISFP" <?php if ($MBTI == 'ISFP') echo 'selected'; ?>>ISFP</option>
            <option value="ESTP" <?php if ($MBTI == 'ESTP') echo 'selected'; ?>>ESTP</option>
            <option value="ESFP" <?php if ($MBTI == 'ESFP') echo 'selected'; ?>>ESFP</option>
        </select>
    </div><br>

    <div class="college">
        <select name="college">
            <option value="クリエイターズカレッジ" <?php if ($college == 'クリエイターズカレッジ') echo 'selected'; ?>>クリエイターズカレッジ</option>
            <option value="デザインカレッジ" <?php if ($college == 'デザインカレッジ') echo 'selected'; ?>>デザインカレッジ</option>
            <option value="ミュージックカレッジ" <?php if ($college == 'ミュージックカレッジ') echo 'selected'; ?>>ミュージックカレッジ</option>
            <option value="ITカレッジ" <?php if ($college == 'ITカレッジ') echo 'selected'; ?>>ITカレッジ</option>
            <option value="テクノロジーカレッジ" <?php if ($college == 'テクノロジーカレッジ') echo 'selected'; ?>>テクノロジーカレッジ</option>
            <option value="スポーツ・医療カレッジ" <?php if ($college == 'スポーツ・医療カレッジ') echo 'selected'; ?>>スポーツ・医療カレッジ</option>
        </select>
    </div><br>

    <div class="Birthplace">
        <input type="radio" id="q1a" name="Birthplace" value="北海道" <?php echo $Birthplace == '北海道' ? 'checked' : ''; ?>>
        <label for="q1a">北海道</label>
        <input type="radio" id="q1b" name="Birthplace" value="青森" <?php echo $Birthplace == '青森' ? 'checked' : ''; ?>>
        <label for="q1b">青森</label>
        <input type="radio" id="q1c" name="Birthplace" value="岩手" <?php echo $Birthplace == '岩手' ? 'checked' : ''; ?>>
        <label for="q1c">岩手</label>
        <input type="radio" id="q1d" name="Birthplace" value="宮城" <?php echo $Birthplace == '宮城' ? 'checked' : ''; ?>>
        <label for="q1d">宮城</label>
        <input type="radio" id="q1c" name="Birthplace" value="秋田" <?php echo $Birthplace == '秋田' ? 'checked' : ''; ?>>
        <label for="q1c">秋田</label>
        <input type="radio" id="q1d" name="Birthplace" value="山形" <?php echo $Birthplace == '山形' ? 'checked' : ''; ?>>
        <label for="q1d">山形</label>
        <input type="radio" id="q1a" name="Birthplace" value="福島" <?php echo $Birthplace == '福島' ? 'checked' : ''; ?>>
        <label for="q1a">福島</label>
        <input type="radio" id="q1b" name="Birthplace" value="茨城" <?php echo $Birthplace == '茨城' ? 'checked' : ''; ?>>
        <label for="q1b">茨城</label>
        <input type="radio" id="q1b" name="Birthplace" value="栃木" <?php echo $Birthplace == '栃木' ? 'checked' : ''; ?>>
        <label for="q1b">栃木</label>
        <input type="radio" id="q1c" name="Birthplace" value="群馬" <?php echo $Birthplace == '群馬' ? 'checked' : ''; ?>>
        <label for="q1c">群馬</label>
        <input type="radio" id="q1d" name="Birthplace" value="埼玉" <?php echo $Birthplace == '埼玉' ? 'checked' : ''; ?>>
        <label for="q1d">埼玉</label>
        <input type="radio" id="q1c" name="Birthplace" value="千葉" <?php echo $Birthplace == '千葉' ? 'checked' : ''; ?>>
        <label for="q1c">千葉</label>
        <input type="radio" id="q1d" name="Birthplace" value="東京" <?php echo $Birthplace == '東京' ? 'checked' : ''; ?>>
        <label for="q1d">東京</label>
        <input type="radio" id="q1a" name="Birthplace" value="神奈川" <?php echo $Birthplace == '神奈川' ? 'checked' : ''; ?>>
        <label for="q1a">神奈川</label>
        <input type="radio" id="q1b" name="Birthplace" value="新潟" <?php echo $Birthplace == '新潟' ? 'checked' : ''; ?>>
        <label for="q1b">新潟</label>
        <input type="radio" id="q1c" name="Birthplace" value="富山" <?php echo $Birthplace == '富山' ? 'checked' : ''; ?>>
        <label for="q1c">富山</label>
        <input type="radio" id="q1d" name="Birthplace" value="石川" <?php echo $Birthplace == '石川' ? 'checked' : ''; ?>>
        <label for="q1d">石川</label>
        <input type="radio" id="q1c" name="Birthplace" value="福井" <?php echo $Birthplace == '福井' ? 'checked' : ''; ?>>
        <label for="q1c">福井</label>
        <input type="radio" id="q1d" name="Birthplace" value="山梨" <?php echo $Birthplace == '山梨' ? 'checked' : ''; ?>>
        <label for="q1d">山梨</label>
        <input type="radio" id="q1a" name="Birthplace" value="長野" <?php echo $Birthplace == '長野' ? 'checked' : ''; ?>>
        <label for="q1a">長野</label>
        <input type="radio" id="q1b" name="Birthplace" value="岐阜" <?php echo $Birthplace == '岐阜' ? 'checked' : ''; ?>>
        <label for="q1b">岐阜</label>
        <input type="radio" id="q1c" name="Birthplace" value="静岡" <?php echo $Birthplace == '静岡' ? 'checked' : ''; ?>>
        <label for="q1c">静岡</label>
        <input type="radio" id="q1d" name="Birthplace" value="愛知" <?php echo $Birthplace == '愛知' ? 'checked' : ''; ?>>
        <label for="q1d">愛知</label>
        <input type="radio" id="q1c" name="Birthplace" value="三重" <?php echo $Birthplace == '三重' ? 'checked' : ''; ?>>
        <label for="q1c">三重</label>
        <input type="radio" id="q1d" name="Birthplace" value="滋賀" <?php echo $Birthplace == '滋賀' ? 'checked' : ''; ?>>
        <label for="q1d">滋賀</label>
        <input type="radio" id="q1a" name="Birthplace" value="京都" <?php echo $Birthplace == '京都' ? 'checked' : ''; ?>>
        <label for="q1a">京都</label>
        <input type="radio" id="q1b" name="Birthplace" value="大阪" <?php echo $Birthplace == '大阪' ? 'checked' : ''; ?>>
        <label for="q1b">大阪</label>
        <input type="radio" id="q1c" name="Birthplace" value="兵庫" <?php echo $Birthplace == '兵庫' ? 'checked' : ''; ?>>
        <label for="q1c">兵庫</label>
        <input type="radio" id="q1d" name="Birthplace" value="奈良" <?php echo $Birthplace == '奈良' ? 'checked' : ''; ?>>
        <label for="q1d">奈良</label>
        <input type="radio" id="q1c" name="Birthplace" value="和歌山" <?php echo $Birthplace == '和歌山' ? 'checked' : ''; ?>>
        <label for="q1c">和歌山</label>
        <input type="radio" id="q1d" name="Birthplace" value="鳥取" <?php echo $Birthplace == '鳥取' ? 'checked' : ''; ?>>
        <label for="q1d">鳥取</label>
        <input type="radio" id="q1a" name="Birthplace" value="島根" <?php echo $Birthplace == '島根' ? 'checked' : ''; ?>>
        <label for="q1a">島根</label>
        <input type="radio" id="q1b" name="Birthplace" value="岡山" <?php echo $Birthplace == '岡山' ? 'checked' : ''; ?>>
        <label for="q1b">岡山</label>
        <input type="radio" id="q1c" name="Birthplace" value="広島" <?php echo $Birthplace == '広島' ? 'checked' : ''; ?>>
        <label for="q1c">広島</label>
        <input type="radio" id="q1d" name="Birthplace" value="山口" <?php echo $Birthplace == '山口' ? 'checked' : ''; ?>>
        <label for="q1d">山口</label>
        <input type="radio" id="q1c" name="Birthplace" value="徳島" <?php echo $Birthplace == '徳島' ? 'checked' : ''; ?>>
        <label for="q1c">徳島</label>
        <input type="radio" id="q1d" name="Birthplace" value="香川" <?php echo $Birthplace == '香川' ? 'checked' : ''; ?>>
        <label for="q1d">香川</label>
        <input type="radio" id="q1a" name="Birthplace" value="愛媛" <?php echo $Birthplace == '愛媛' ? 'checked' : ''; ?>>
        <label for="q1a">愛媛</label>
        <input type="radio" id="q1b" name="Birthplace" value="高知" <?php echo $Birthplace == '高知' ? 'checked' : ''; ?>>
        <label for="q1b">高知</label>
        <input type="radio" id="q1c" name="Birthplace" value="福岡" <?php echo $Birthplace == '福岡' ? 'checked' : ''; ?>>
        <label for="q1c">福岡</label>
        <input type="radio" id="q1d" name="Birthplace" value="佐賀" <?php echo $Birthplace == '佐賀' ? 'checked' : ''; ?>>
        <label for="q1d">佐賀</label>
        <input type="radio" id="q1c" name="Birthplace" value="長崎" <?php echo $Birthplace == '長崎' ? 'checked' : ''; ?>>
        <label for="q1c">長崎</label>
        <input type="radio" id="q1a" name="Birthplace" value="熊本" <?php echo $Birthplace == '熊本' ? 'checked' : ''; ?>>
        <label for="q1a">熊本</label>
        <input type="radio" id="q1b" name="Birthplace" value="大分" <?php echo $Birthplace == '大分' ? 'checked' : ''; ?>>
        <label for="q1b">大分</label>
        <input type="radio" id="q1c" name="Birthplace" value="宮崎" <?php echo $Birthplace == '宮崎' ? 'checked' : ''; ?>>
        <label for="q1c">宮崎</label>
        <input type="radio" id="q1d" name="Birthplace" value="鹿児島" <?php echo $Birthplace == '鹿児島' ? 'checked' : ''; ?>>
        <label for="q1d">鹿児島</label>
        <input type="radio" id="q1c" name="Birthplace" value="沖縄" <?php echo $Birthplace == '沖縄' ? 'checked' : ''; ?>>
        <label for="q1c">沖縄</label>
    </div><br>

    <div class="birthdate">
        <select name="birthdate">
        <label for="birthdate">生年月日:</label>
        <input type="date" name="birthdate" id="birthdate" value="<?php echo htmlspecialchars($profile['birthdate']); ?>" required><br>
        </select>
    </div><br>

    <button type="submit">更新</button>
</form>
