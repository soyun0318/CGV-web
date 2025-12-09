<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>메인</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Malgun Gothic', -apple-system, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-container {
            width: 100%;
            max-width: 480px;
            background-color: white;
            min-height: 100vh;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }

        /* 헤더 */
        .header {
            background-color: #dc2626;
            color: white;
            padding: 16px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: -0.5px;
        }

        /* 관리자 버튼 */
        .admin-btn {
            background: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .admin-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* 메인 컨텐츠 */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            text-align: center;
        }

        .logo-text h2 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 8px;
            font-weight: 800;
        }

        .logo-text p {
            color: #6b7280;
            margin-bottom: 50px;
            font-size: 15px;
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
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s;
        }

        /* 로그인 버튼 */
        .btn-primary {
            background-color: #dc2626;
            color: white;
            border: 1px solid #dc2626;
        }
        .btn-primary:hover { background-color: #b91c1c; }

        /* 회원가입 버튼 */
        .btn-secondary {
            background-color: white;
            color: #dc2626;
            border: 1px solid #dc2626;
        }
        .btn-secondary:hover { background-color: #fef2f2; }

        .footer {
            padding: 20px;
            text-align: center;
            font-size: 11px;
            color: #9ca3af;
            background-color: #f9fafb;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
            <button onclick="goAdmin()" class="admin-btn">관리자 페이지</button>
        </div>

        <div class="content">
            <div class="logo-text">
                <h2>CGV-SWU</h2>
                <p>영화 예매 사이트입니다</p>
            </div>

            <div class="btn-group">
                <a href="login.php" class="btn btn-primary">로그인</a>
                <a href="signup.php" class="btn btn-secondary">회원가입</a>
            </div>
        </div>

        <div class="footer">
            &copy; 소프트웨어융합학과 2022111696 오소윤
        </div>
    </div>

    <script>
        function goAdmin() {
            const password = prompt("관리자 비밀번호를 입력하세요.");

            if (password === "1234") {
                location.href = "admin_page.php";
            } else if (password !== null) {
                alert("비밀번호가 일치하지 않습니다.");
            }
        }
    </script>

</body>
</html>