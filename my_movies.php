<?php
session_start();

// 로그인 체크
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>마이 무비 - CGV</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Malgun Gothic', -apple-system, sans-serif;
            background-color: #f5f5f5;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 20px; font-weight: bold; }
        .btn-back { color: white; text-decoration: none; font-size: 14px; opacity: 0.9; }

        .content { padding: 30px 20px; }

        .welcome-msg {
            font-size: 20px;
            font-weight: 800;
            color: #111;
            margin-bottom: 30px;
            border-left: 5px solid #dc2626;
            padding-left: 15px;
        }
        .welcome-msg span {
            display: block;
            font-size: 14px;
            color: #666;
            font-weight: normal;
            margin-top: 5px;
        }

        /* 메뉴 그리드 */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        /* 메뉴 카드 */
        .menu-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 30px 10px;
            text-align: center;
            text-decoration: none;
            color: #374151;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.2s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 150px;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
            border-color: #dc2626;
            color: #dc2626;
        }

        .menu-icon { font-size: 40px; margin-bottom: 15px; }
        .menu-title { font-size: 16px; font-weight: bold; }

    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>마이 무비</h1>
            <a href="home.php" class="btn-back">홈으로</a>
        </div>

        <div class="content">
            <div class="welcome-msg">
                나의 영화 기록
                <span>지금까지 본 영화들을 모아보세요.</span>
            </div>

            <div class="menu-grid">
                <!-- 1. 지금까지 본 영화 (예매 내역) -->
                <a href="my_booking.php" class="menu-card">
                    <div class="menu-icon">🎫</div>
                    <div class="menu-title">지금까지 본 영화</div>
                </a>

                <!-- 2. 좋아요 한 영화 -->
                <a href="my_liked_movies.php" class="menu-card">
                    <div class="menu-icon">❤️</div>
                    <div class="menu-title">좋아요 한 영화</div>
                </a>
            </div>
        </div>
    </div>

</body>
</html>