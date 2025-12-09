<?php
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

$userid = $_GET['userid'];
$sql = "SELECT * FROM cgvtable WHERE userid = '$userid'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "이미 사용 중인 ID입니다.";
} else {
    echo "사용 가능한 ID입니다.";
}

$conn->close();
?>