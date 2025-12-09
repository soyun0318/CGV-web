<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지</title>
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
            background-color: #1f2937;
            color: white;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 { font-size: 20px; font-weight: bold; letter-spacing: -0.5px; }
        .header-link { color: #d1d5db; text-decoration: none; font-size: 13px; }
        .header-link:hover { color: white; }

        /* 컨텐츠 */
        .content {
            padding: 30px 20px;
        }

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

        /* 메뉴 그리드- 2열*/
        .admin-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        /* 메뉴 카드 버튼 */
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
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
            border-color: #dc2626; /* 호버 시 빨간 테두리 */
            color: #dc2626;
        }

        .menu-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .menu-title {
            font-size: 16px;
            font-weight: bold;
        }

        /* 로그아웃 버튼 */
        .btn-logout {
            display: block;
            width: 100%;
            margin-top: 40px;
            padding: 15px;
            background-color: #9ca3af;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
        }
        .btn-logout:hover { background-color: #6b7280; }

    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>CGV ADMIN</h1>
        </div>

        <div class="content">

            <div class="admin-grid">
                <!-- 회원 관리 -->
                <a href="member_list.php" class="menu-card">
                    <div class="menu-icon">👥</div>
                    <div class="menu-title">회원 관리</div>
                </a>

                <!-- 영화 관리 -->
                <a href="movie_admin_list.php" class="menu-card">
                    <div class="menu-icon">🎬</div>
                    <div class="menu-title">영화 등록/관리</div>
                </a>

                <!-- 쿠폰 관리 -->
                <a href="coupon_list.php" class="menu-card">
                    <div class="menu-icon">🎟️</div>
                    <div class="menu-title">쿠폰 발급/관리</div>
                </a>
            </div>

            <a href="main_page.php" class="btn-logout">관리자 모드 종료</a>
        </div>
    </div>

</body>
</html>