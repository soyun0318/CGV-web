<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패");
}

// 1. 로그인 확인
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); history.back();</script>";
    exit;
}

// 2. 영화 ID 확인
if (!isset($_GET['id'])) {
    echo "<script>alert('잘못된 접근입니다.'); history.back();</script>";
    exit;
}

$movie_id = (int)$_GET['id'];
$user_id = $_SESSION['userid'];

// 3. 좋아요 상태 확인
$check_sql = "SELECT * FROM movie_likes WHERE movie_id = $movie_id AND user_id = '$user_id'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    // 이미 좋아요를 누른 상태 -> 좋아요 취소 (삭제)
    $conn->query("DELETE FROM movie_likes WHERE movie_id = $movie_id AND user_id = '$user_id'");
    // movie 테이블의 hits(좋아요 수) 감소
    $conn->query("UPDATE movie SET hits = hits - 1 WHERE id = $movie_id");
} else {
    // 좋아요 안 누른 상태 -> 좋아요 추가
    $conn->query("INSERT INTO movie_likes (movie_id, user_id) VALUES ($movie_id, '$user_id')");
    // movie 테이블의 hits(좋아요 수) 증가
    $conn->query("UPDATE movie SET hits = hits + 1 WHERE id = $movie_id");
}

// 4. 원래 페이지로 복귀 (스크롤 위치 유지는 어렵지만 페이지 새로고침)
header("Location: home.php"); // 혹은 history.back()을 쓰면 폼 재전송 경고가 뜰 수 있어 redirect가 낫습니다.
exit;
?>