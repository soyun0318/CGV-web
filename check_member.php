<?php
session_start();
header('Content-Type: application/json'); 

error_reporting(0); 

$conn = new mysqli("localhost", "root", "", "sample");
$response = array();

if ($conn->connect_error) {
    $response["status"] = "error";
    $response["message"] = "DB 연결 실패";
    echo json_encode($response);
    exit;
}

if (!isset($_POST['userid']) || !isset($_POST['password'])) {
    $response["status"] = "fail";
    $response["message"] = "아이디와 비밀번호가 입력되지 않았습니다.";
    echo json_encode($response);
    exit;
}

$userid = $_POST['userid'];
$password = $_POST['password'];

$sql = "SELECT * FROM cgvtable WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hash = $row['password']; 

    if (password_verify($password, $hash)) {
        // 로그인 성공
        $_SESSION['userid'] = $row['userid'];
        $_SESSION['name'] = $row['name'];

        $response["status"] = "success";
        $response["message"] = "로그인 성공! 환영합니다.";
    } else {
        // 비밀번호 틀림
        $response["status"] = "fail";
        $response["message"] = "비밀번호가 일치하지 않습니다.";
    }
} else {
    // 아이디 없음
    $response["status"] = "fail";
    $response["message"] = "존재하지 않는 아이디입니다.";
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>