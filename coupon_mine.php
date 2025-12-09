<?php
session_start();

if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "sample");
if (!$conn) {
    die("DB 연결 실패");
}

$userid = $_SESSION['userid'];
$sql ="SELECT * FROM couponbox WHERE userid = '$userid' ORDER BY issued_date DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>내 쿠폰함</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Malgun Gothic', -apple-system, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        /* 앱 컨테이너 */
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 24px; font-weight: bold; letter-spacing: -0.5px; }
        .header-link { color: white; text-decoration: none; font-size: 14px; opacity: 0.9; }

        /* 컨텐츠 */
        .content {
            padding: 20px;
        }

        /* 페이지 타이틀 */
        .page-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 20px;
            border-left: 4px solid #dc2626;
            padding-left: 10px;
        }

        /* 테이블 */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        th {
            background-color: #f9fafb;
            color: #555;
            padding: 10px 4px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            text-align: center;
            white-space: nowrap;
        }

        td {
            padding: 14px 6px;
            border-bottom: 1px solid #f3f4f6;
            color: #333;
            text-align: center;
            vertical-align: middle;
        }

        /* 쿠폰명 */
        .col-title {
            text-align: left;
            padding-left: 8px;
        }
        .col-title a {
            color: #111;
            text-decoration: none;
            font-weight: bold;
            display: block;
        }
        .col-title span {
            display: block;
            font-size: 11px;
            color: #888;
            font-weight: normal;
            margin-top: 2px;
        }

        /* 금액 */
        .price-text {
            color: #111;
            font-weight: bold;
        }

        /* 상세보기 */
        .btn-detail {
            display: inline-block;
            padding: 4px 8px;
            background-color: #dc2626;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 11px;
        }

        .btn-detail:hover { background-color: #b91c1c; }

        /* 빈 리스트 */
        .empty-list {
            padding: 50px 0;
            text-align: center;
            color: #9ca3af;
            font-size: 14px;
        }

    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
            <a href="home.php" class="header-link">홈으로</a>
        </div>

        <div class="content">
            <h2 class="page-title">내 쿠폰함</h2>

            <table>
                <colgroup>
                    <col style="width: auto"> <!-- 쿠폰명 -->
                    <col style="width: 20%">  <!-- 금액 -->
                    <col style="width: 20%">  <!-- 상태 -->
                </colgroup>
                <thead>
                    <tr>
                        <th style="text-align:left; padding-left:8px;">쿠폰명</th>
                        <th>할인금액</th>
                        <th>상태</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) == 0): ?>
                        <tr><td colspan="3" class="empty-list">보유하신 쿠폰이 없습니다.</td></tr>
                    <?php else: ?>
                        <?php while($row = mysqli_fetch_assoc($result)) { 
                            $is_used = $row['is_used'];
                            $status_class = $is_used ? "status-used" : "status-active";
                            $status_text = $is_used ? "사용 완료" : "사용 가능";
                            $date = date("Y.m.d", strtotime($row['issued_date']));
                        ?>
                            <tr>
                                <td class="col-title">
                                    <a href="coupon_view.php?id=<?= $row['id'] ?>">
                                        <?= htmlspecialchars($row['title']) ?>
                                    </a>
                                    <span>발급일: <?= $date ?></span>
                                </td>
                                <td class="price-text">
                                    <?= number_format($row['price']) ?>
                                </td>
                                <td>
                                    <a href="coupon_view.php?id=<?= $row['id'] ?>" class="btn-detail">상세</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>