<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인 - CGV</title>
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
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
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
            margin-bottom: 4px;
        }

        .header p {
            font-size: 13px;
            opacity: 0.9;
        }

        .form-container {
            padding: 40px 20px;
        }

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
            padding: 14px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            background-color: #f9fafb;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #dc2626;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

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

        .auth-links {
            margin-top: 20px;
            text-align: center;
            font-size: 13px;
        }

        .auth-links a {
            color: #6b7280;
            text-decoration: none;
            margin: 0 8px;
        }
        
        .auth-links a:hover {
            color: #dc2626;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
        </div>

        <div class="form-container">
            <form onsubmit="return checkMember(event);">
                <div class="form-group">
                    <label class="form-label" for="userid">아이디</label>
                    <input type="text" id="userid" name="userid" class="form-input" placeholder="아이디를 입력하세요" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="password">비밀번호</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="비밀번호를 입력하세요" required>
                </div>
                
                <button type="submit" class="submit-btn">로그인</button>
            </form>

            <div class="auth-links">
                <a href="signup.php">회원가입</a>
            </div>
        </div>
    </div>

    <script>
        function checkMember(event) {
            event.preventDefault();

            const userid = document.getElementById('userid').value;
            const password = document.getElementById('password').value;

            if (!userid || !password) {
                alert('아이디와 비밀번호를 모두 입력해주세요.');
                return false;
            }

            fetch('check_member.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded', 
                },
                body: `userid=${userid}&password=${password}` 
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'home.php'; 
                } else {
                    alert(data.message); 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('서버 통신 중 오류가 발생했습니다.');
            });
        }
    </script>
</body>
</html>     