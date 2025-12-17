<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "sample"); 

if (!$conn) {
    die("DB 연결 실패");
}

$sql = "SELECT * FROM couponbox ORDER BY issued_date DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>쿠폰 관리</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Malgun Gothic', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            padding: 20px 0;
        }

        .app-container {
            max-width: 1000px; 
            margin: 0 auto;
            background-color: white;
            min-height: 100vh;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background-color: #dc2626;
            color: white;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 { font-size: 24px; font-weight: bold; }
        .btn-back { color: white; text-decoration: none; font-size: 14px; opacity: 0.9; }

        .content-container {
            padding: 24px;
            overflow-x: auto;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-left: 5px solid #dc2626;
            padding-left: 10px;
        }

        .page-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin: 0;
        }

        /* 등록 버튼 */
        .btn-add {
            background-color: #374151;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            transition: background 0.2s;
        }
        .btn-add:hover { background-color: #1f2937; }

        /* 테이블 */
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: 700;
            padding: 12px;
            font-size: 14px;
            border-bottom: 2px solid #dc2626;
            text-align: center;
            white-space: nowrap;
        }

        td {
            padding: 12px;
            font-size: 14px;
            color: #4b5563;
            border-bottom: 1px solid #e5e7eb;
            text-align: center;
            background-color: white;
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:nth-child(even) td { background-color: #fef2f2; }
        tr:hover td { background-color: #fff1f2; }

        /* 상태 */
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }
        .badge-active { background-color: #dc2626; } /* 사용 가능 */
        .badge-used { background-color: #9ca3af; }   /* 사용 완료 */

        /* 금액 */
        .price-text {
            font-weight: bold;
            color: #111;
        }

        /* 관리 */
        .btn-detail {
            display: inline-block;
            padding: 4px 8px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            color: #374151;
            text-decoration: none;
            font-size: 12px;
            background-color: white;
        }
        .btn-detail:hover { background-color: #f3f4f6; }

    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>CGV ADMIN</h1>
            <a href="admin_page.php" class="btn-back">닫기</a>
        </div>

        <div class="content-container">
            <div class="page-header">
                <h2 class="page-title">쿠폰 발급 내역</h2>
                <a href="coupon_write.php" class="btn-add">+ 쿠폰 등록</a>
            </div>

            <!-- 테이블 -->
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>쿠폰명</th>
                        <th>할인금액</th>
                        <th>회원ID</th>
                        <th>좋아요</th>
                        <th>발급일</th>
                        <th>상태</th>
                        <th>관리</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(mysqli_num_rows($result) == 0): ?>
                        <tr><td colspan="8" style="padding: 40px 0; color: #999;">등록된 쿠폰이 없습니다.</td></tr>
                    <?php else: ?>
                        <?php while($row = mysqli_fetch_assoc($result)) { 
                            $is_used = $row['is_used'];
                            $status_class = $is_used ? "badge-used" : "badge-active";
                            $status_text = $is_used ? "사용 완료" : "사용 가능";
                            $date = date("Y-m-d", strtotime($row['issued_date']));
                        ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td style="text-align:left; font-weight:bold;"><?= htmlspecialchars($row['title']) ?></td>
                                <td class="price-text"><?= number_format($row['price']) ?>원</td>
                                <td><?= $row['userid'] ?></td>
                                <td>♥ <?= $row['likes'] ?></td>
                                <td><?= $date ?></td>
                                <td>
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= $status_text ?>
                                    </span>
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