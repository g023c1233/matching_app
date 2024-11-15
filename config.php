<?php
// config.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "matching_app";

// データベース接続を確立
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

mysqli_set_charset($conn, "utf8");
?>