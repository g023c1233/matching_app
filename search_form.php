<?php
// search_form.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索</title>
</head>
<body>
    <h1>検索</h1>
    <form action="search_profile.php" method="get">
        <label for="query">キーワード:</label>
        <input type="text" id="query" name="query"><br>
        <label for="gender">性別:</label>
        <select id="gender" name="gender">
            <option value="">すべて</option>
            <option value="男性">男性</option>
            <option value="女性">女性</option>
            <option value="その他">その他</option>
        </select><br>
        <button type="submit">検索</button>
    </form>
    <a href="menu.php">戻る</a>
</body>
</html>
