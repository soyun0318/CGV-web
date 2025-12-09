<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>쿠폰 발급</title>
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

        /* 폼 컨테이너 */
        .form-container {
            padding: 24px 20px;
        }

        .page-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 24px;
            border-left: 4px solid #dc2626;
            padding-left: 10px;
        }

        /* 입력 폼 */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9fafb;
            color: #111;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #dc2626;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* 발급 버튼 */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background-color: #dc2626;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: #b91c1c;
        }

        .submit-btn:active {
            transform: scale(0.98);
        }

    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
            <a href="coupon_list.php" class="header-link">취소</a>
        </div>

        <div class="form-container">
            <h2 class="page-title">쿠폰 발급</h2>

            <form method="post" action="coupon_save.php">
                <div class="form-group">
                    <label class="form-label">회원 ID</label>
                    <input type="text" name="userid" class="form-input" placeholder="발급받을 회원 ID 입력" required>
                </div>

                <div class="form-group">
                    <label class="form-label">쿠폰명</label>
                    <input type="text" name="title" class="form-input" placeholder="예: 신규 가입 축하 쿠폰" required>
                </div>

                <div class="form-group">
                    <label class="form-label">할인금액</label>
                    <input type="number" name="price" class="form-input" placeholder="숫자만 입력 (예: 3000)" min="0" step="100" required>
                </div>

                <button type="submit" class="submit-btn">발급하기</button>
            </form>
        </div>
    </div>

</body>
</html>