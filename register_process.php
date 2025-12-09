
<?php
$conn = new mysqli("localhost", "root", "", "sample");
if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

$userid = $_POST['userid'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$is_univ = isset($_POST['is_univ']) ? 1 : 0;
$is_swu = isset($_POST['is_swu']) ? 1 : 0;

$sql = "INSERT INTO cgvtable (userid, password, name, email, address, is_univ, is_swu) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssii", $userid, $password, $name, $email, $address, $is_univ, $is_swu);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입 완료 - CGV</title>
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
        }

        .app-container {
            max-width: 480px;
            margin: 0 auto;
            background-color: white;
            min-height: 100vh;
            position: relative;
        }

        .header {
            background-color: #dc2626;
            color: white;
            padding: 16px 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
        }

        .content {
            padding: 40px 20px;
            text-align: center;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-success {
            background-color: #dcfce7;
        }

        .icon-error {
            background-color: #fee2e2;
        }

        .icon-success svg {
            width: 48px;
            height: 48px;
            color: #16a34a;
        }

        .icon-error svg {
            width: 48px;
            height: 48px;
            color: #dc2626;
        }

        .result-title {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .result-title.success {
            color: #16a34a;
        }

        .result-title.error {
            color: #dc2626;
        }

        .message {
            font-size: 16px;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .message strong {
            color: #dc2626;
            font-weight: 600;
        }

        .error-message {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 16px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
        }

        .info-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
            text-align: left;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #374151;
        }

        .info-label {
            font-weight: 600;
        }

        .info-value {
            color: #6b7280;
        }

        .button-group {
            margin-top: 32px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            text-align: center;
        }

        .btn-primary {
            background-color: #dc2626;
            color: white;
        }

        .btn-primary:hover {
            background-color: #b91c1c;
        }

        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
        }

        .btn-secondary:hover {
            background-color: #e5e7eb;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .discount-info {
            background-color: #dcfce7;
            border: 1px solid #86efac;
            border-radius: 8px;
            padding: 12px;
            margin: 20px 0;
            font-size: 14px;
            color: #166534;
        }

        .discount-info strong {
            color: #15803d;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
        </div>

        <div class="content">
            <?php if ($stmt->execute()) : ?>
                <div class="icon-wrapper icon-success">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h2 class="result-title success">회원가입 완료!</h2>

                <p class="message">
                    <strong><?= htmlspecialchars($name) ?></strong>님, 환영합니다!
                </p>
                <p class="message">
                    이제 CGV 영화 예매 서비스를 이용하실 수 있습니다.
                </p>

                <div class="info-box">
                    <div class="info-item">
                        <span class="info-label">아이디</span>
                        <span class="info-value"><?= htmlspecialchars($userid) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">이메일</span>
                        <span class="info-value"><?= htmlspecialchars($email) ?></span>
                    </div>
                </div>

                <?php if ($is_univ || $is_swu) : ?>
                    <div class="discount-info">
                        🎉 할인 혜택이 적용되었습니다!
                        <?php if ($is_univ && $is_swu) : ?>
                            <br><strong>총 20% 할인</strong> (대학생 10% + 서울여대 10%)
                        <?php elseif ($is_univ) : ?>
                            <br><strong>10% 할인</strong> (대학생)
                        <?php else : ?>
                            <br><strong>10% 할인</strong> (서울여대)
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="button-group">
                    <a href="login.php" class="btn btn-primary">로그인하러 가기</a>
                    <a href="home.php" class="btn btn-secondary">홈으로</a>
                </div>

            <?php else : ?>
                <div class="icon-wrapper icon-error">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <h2 class="result-title error">회원가입 실패</h2>

                <p class="message">
                    회원가입 처리 중 오류가 발생했습니다.
                </p>

                <div class="error-message">
                    <?= htmlspecialchars($stmt->error) ?>
                </div>

                <div class="button-group">
                    <a href="signup.php" class="btn btn-primary">다시 시도하기</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>