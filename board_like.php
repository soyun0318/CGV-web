<?php
session_start();

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); history.back();</script>";
    exit;
}

if (!isset($_POST['board_id'])) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

$userid = $_SESSION['userid'];
$board_id = (int)$_POST['board_id']; 

$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) {
    die("DB 연결 실패");
}

$check = $conn->query("SELECT * FROM board_likes WHERE board_id = $board_id AND userid = '$userid'");

if ($check->num_rows == 0) {
    // 좋아요 추가
    $conn->query("INSERT INTO board_likes (board_id, userid) VALUES ($board_id, '$userid')");
} else {
    // 이미 누른 경우 
    echo "<script>alert('이미 공감하셨습니다.'); location.href='board_view.php?id=$board_id';</script>";
    exit;
}

header("Location: board_view.php?id=$board_id");
?>