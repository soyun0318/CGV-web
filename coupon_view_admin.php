<?php
$conn = mysqli_connect("localhost", "root", "", "sample");
$id = $_GET['id'];

$sql = "SELECT * FROM couponbox WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$is_used = $row['is_used'];
$status_class = $is_used ? "status-used" : "status-active";
$status_text = $is_used ? "사용 완료" : "사용 가능";
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>쿠폰 상세 - CGV</title>
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
            padding-bottom: 40px;
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
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* 쿠폰 */
        .coupon-ticket {
            width: 100%;
            background-color: white;
            border: 2px solid #dc2626;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.15);
            margin-bottom: 30px;
            position: relative;
        }

        /* 사용 완료된 쿠폰 */
        .coupon-ticket.used {
            border-color: #9ca3af;
            background-color: #f3f4f6;
        }
        .coupon-ticket.used .ticket-header {
            background-color: #9ca3af;
        }
        .coupon-ticket.used .ticket-price {
            color: #6b7280;
            text-decoration: line-through;
        }

        /* 티켓 상단 */
        .ticket-header {
            background-color: #dc2626;
            color: white;
            padding: 12px;
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            letter-spacing: 1px;
        }

        /* 티켓 본문 */
        .ticket-body {
            padding: 24px 20px;
            text-align: center;
        }

        .ticket-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .ticket-price {
            font-size: 32px;
            font-weight: 900;
            color: #dc2626;
            margin-bottom: 20px;
        }

        .ticket-info {
            font-size: 13px;
            color: #666;
            margin-bottom: 6px;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #eee;
            padding-bottom: 6px;
        }
        .ticket-info:last-child {
            border-bottom: none;
        }

        .dashed-line {
            height: 1px;
            border-top: 2px dashed #e5e7eb;
            margin: 0 10px;
            position: relative;
        }

        /* 버튼 그룹 */
        .btn-group {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 16px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.2s;
        }

        /* 메인 버튼 */
        .btn-primary {
            background-color: #dc2626;
            color: white;
            box-shadow: 0 4px 6px rgba(220, 38, 38, 0.2);
        }
        .btn-primary:hover { background-color: #b91c1c; }
        
        /* 사용 불가 버튼 */
        .btn-disabled {
            background-color: #d1d5db;
            color: white;
            cursor: not-allowed;
            pointer-events: none; 
        }

        /* 서브 버튼 */
        .sub-actions {
            display: flex;
            gap: 10px;
        }
        .btn-secondary {
            flex: 1;
            background-color: white;
            color: #374151;
            border: 1px solid #d1d5db;
            font-size: 14px;
            padding: 12px;
        }
        .btn-secondary:hover { background-color: #f9fafb; }

        .btn-delete {
            color: #ef4444; 
            border-color: #fca5a5;
        }
        .btn-delete:hover { background-color: #fef2f2; }

    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
            <a href="coupon_list.php" class="header-link">목록으로</a>
        </div>

        <div class="content">
            
            <div class="coupon-ticket <?= $is_used ? 'used' : '' ?>">
                <div class="ticket-header">COUPON</div>
                
                <div class="ticket-body">
                    <div class="ticket-title"><?= htmlspecialchars($row['title']) ?></div>
                    <div class="ticket-price">
                        <?= number_format($row['price']) ?>원 할인
                    </div>
                    
                    <div class="dashed-line"></div>
                    <br>

                    <div class="ticket-info">
                        <span>회원 ID</span>
                        <span><?= $row['userid'] ?></span>
                    </div>
                    <div class="ticket-info">
                        <span>발급일</span>
                        <span><?= date("Y.m.d", strtotime($row['issued_date'])) ?></span>
                    </div>
                    <div class="ticket-info">
                        <span>상태</span>
                        <span style="font-weight:bold; color: <?= $is_used ? '#999' : '#dc2626' ?>">
                            <?= $status_text ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="btn-group">

                <div class="sub-actions">
                    <a href="coupon_like.php?id=<?= $id ?>" class="btn btn-secondary">
                        ♥ 좋아요 (<?= $row['likes'] ?>)
                    </a>
                    <a href="coupon_delete.php?id=<?= $id ?>" class="btn btn-secondary btn-delete" 
                       onclick="return confirm('정말 삭제하시겠습니까?');">
                        삭제
                    </a>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
        
        
        
