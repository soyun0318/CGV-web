<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패");
}

// 예매 번호 확인
if (!isset($_GET['id'])) {
    echo "<script>alert('잘못된 접근입니다.'); location.href='home.php';</script>";
    exit;
}

$booking_id = $_GET['id'];
$userid = $_SESSION['userid'];

// booking 테이블의 movieid와 movie 테이블의 id를 매칭
$sql = "SELECT b.*, m.name as movie_name, m.img as movie_img 
        FROM booking b 
        JOIN movie m ON b.movieid = m.id 
        WHERE b.id = ? AND b.userid = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $booking_id, $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('예매 내역을 찾을 수 없습니다.'); location.href='home.php';</script>";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>예매 완료 - CGV</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Malgun Gothic', -apple-system, sans-serif;
            background-color: #e5e7eb;
            min-height: 100vh;
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: white;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        /* 헤더 */
        .header {
            background-color: #dc2626;
            color: white;
            padding: 16px 20px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        /* 완료 메시지 */
        .success-message {
            padding: 40px 20px;
            text-align: center;
            background-color: white;
            border-bottom: 1px dashed #e5e7eb;
        }
        .icon-check {
            font-size: 50px;
            color: #dc2626;
            margin-bottom: 16px;
            display: block;
        }
        .msg-title {
            font-size: 24px;
            font-weight: 800;
            color: #111;
            margin-bottom: 8px;
        }
        .msg-sub {
            font-size: 14px;
            color: #6b7280;
        }

        /* 예매 상세 내역 */
        .ticket-info {
            padding: 30px 20px;
        }
        
        .poster-area {
            text-align: center;
            margin-bottom: 24px;
        }
        .poster-img {
            width: 120px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table th {
            text-align: left;
            color: #6b7280;
            font-weight: normal;
            padding: 8px 0;
            width: 80px;
            font-size: 14px;
        }
        .info-table td {
            text-align: right;
            color: #111;
            font-weight: bold;
            padding: 8px 0;
            font-size: 15px;
        }
        .info-table tr.total-row td {
            color: #dc2626;
            font-size: 20px;
            padding-top: 16px;
            border-top: 2px solid #f3f4f6;
        }

        /* 하단 버튼 */
        .btn-home {
            display: block;
            width: 90%;
            margin: 20px auto;
            background-color: #dc2626;
            color: white;
            text-align: center;
            padding: 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
        }
        .btn-home:hover { background-color: #b91c1c; }

    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">예매 완료</div>

        <div class="success-message">
            <span class="icon-check">✔</span>
            <div class="msg-title">예매가 완료되었습니다!</div>
            <div class="msg-sub">즐거운 영화 관람 되세요.</div>
        </div>

        <div class="ticket-info">
            <!-- 이미지 -->
            <div class="poster-area">
                <img src="images/<?= $row['movie_img'] ?>" class="poster-img" alt="포스터"
                     onerror="this.src='https://via.placeholder.com/120x170?text=No+Image'">
            </div>

            <!-- 상세 정보 -->
            <table class="info-table">
                <tr>
                    <th>예매번호</th>
                    <td><?= $row['id'] ?></td>
                </tr>
                <tr>
                    <th>영화</th>
                    <td><?= htmlspecialchars($row['movie_name']) ?></td>
                </tr>
                <tr>
                    <th>시간</th>
                    <td><?= $row['time'] ?>:00</td>
                </tr>
                <tr>
                    <th>인원</th>
                    <td><?= $row['amount'] ?>명</td>
                </tr>
                <tr class="total-row">
                    <th>결제금액</th>
                    <td><?= number_format($row['price']) ?>원</td>
                </tr>
            </table>
        </div>

        <a href="home.php" class="btn-home">홈으로 돌아가기</a>
    </div>

</body>
</html>