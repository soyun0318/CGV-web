<?php
session_start();
$userid = $_SESSION['userid'];
$title = $_POST['title'];
$content = $_POST['content'];
$conn = new mysqli("localhost", "root", "", "sample");
$stmt = $conn -> prepare("INSERT INTO board (userid, title, content) VALUES (?, ?, ?)");
$stmt -> bind_param("sss", $userid, $title, $content);
$stmt -> execute();
header("Location: board_list.php");
?>