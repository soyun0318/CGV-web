<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$userid = $_SESSION['userid'];
$movieid = $_POST['movie_id'];
$time_str = $_POST['time']; 
$amount = $_POST['people'];
$price = $_POST['price'];
$coupon_id = isset($_POST['coupon_id']) ? $_POST['coupon_id'] : '';

// 시간 처리 
$time_int = (int)$time_str; 

// 예매 데이터 저장 
$sql = "INSERT INTO booking (userid, movieid, amount, time, price) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiii", $userid, $movieid, $amount, $time_int, $price);

if ($stmt->execute()) {
    // 저장 성공 시, 방금 생성된 예매 번호 가져오기
    $booking_id = $stmt->insert_id;

    // 쿠폰을 선택했다면 실행
    if (!empty($coupon_id)) {
        $coupon_id_int = (int)$coupon_id; 
        
        // is_used 1로 바꾸고 used_date를 오늘로 설정
        $sql_coupon = "UPDATE couponbox SET is_used=1, used_date=CURDATE() WHERE id = $coupon_id_int";
        $conn->query($sql_coupon);
    }

    // 성공 페이지로 이동
    header("Location: booking_success.php?id=$booking_id");
    exit;
} else {
    echo "<script>alert('예매 처리 중 오류가 발생했습니다.'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>