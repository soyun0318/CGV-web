<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
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
            margin-bottom: 4px;
        }

        .header p {
            font-size: 13px;
            opacity: 0.9;
        }

        .form-container {
            padding: 24px 20px;
        }

        .form-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 24px;
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

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9fafb;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #dc2626;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .id-input-group {
            display: flex;
            gap: 8px;
        }

        .id-input-group .form-input {
            flex: 1;
        }

        .btn-check {
            padding: 12px 20px;
            background-color: #dc2626;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background-color 0.2s;
        }

        .btn-check:hover {
            background-color: #b91c1c;
        }

        .btn-check:active {
            transform: scale(0.98);
        }

        .checkbox-group {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
        }

        .checkbox-item:last-of-type {
            margin-bottom: 8px;
        }

        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #dc2626;
        }

        .checkbox-item label {
            font-size: 14px;
            color: #374151;
            cursor: pointer;
            user-select: none;
        }

        .note {
            font-size: 12px;
            color: #dc2626;
            margin-top: 8px;
            display: block;
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
            margin-top: 8px;
        }

        .submit-btn:hover {
            background-color: #b91c1c;
        }

        .submit-btn:active {
            transform: scale(0.98);
        }

        .password-note {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }

        .discount-badge {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 6px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
            <p>회원가입</p>
        </div>

        <div class="form-container">
            <h2 class="form-title">회원 정보 입력</h2>
            
            <form action="register_process.php" method="post" onsubmit="return validateForm();">
                <div class="form-group">
                    <label class="form-label" for="userid">아이디</label>
                    <div class="id-input-group">
                        <input type="text" id="userid" name="userid" class="form-input" placeholder="아이디를 입력하세요" required>
                        <button type="button" class="btn-check" onclick="checkDuplicate();">중복확인</button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="name">이름</label>
                    <input type="text" id="name" name="name" class="form-input" placeholder="이름을 입력하세요" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">비밀번호</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="비밀번호를 입력하세요" required>
                    <small class="password-note">※ 8자 이상, 문자/숫자/특수문자 포함</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password2">비밀번호 확인</label>
                    <input type="password" id="password2" name="password2" class="form-input" placeholder="비밀번호를 다시 입력하세요" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">이메일</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="example@email.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="address">주소</label>
                    <input type="text" id="address" name="address" class="form-input" placeholder="주소를 입력하세요" required>
                </div>

                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" id="is_univ" name="is_univ" value="yes">
                        <label for="is_univ">대학생 할인 적용<span class="discount-badge">10%</span></label>
                    </div>
                    <div class="checkbox-item">
                        <input type="checkbox" id="is_swu" name="is_swu" value="yes">
                        <label for="is_swu">서울여자대학교 학생<span class="discount-badge">추가 10%</span></label>
                    </div>
                    <small class="note">※ 총 할인율 최대 20% 적용 가능</small>
                </div>

                <button type="submit" class="submit-btn">회원가입 완료</button>
            </form>
        </div>
    </div>

    <script>
        function checkPasswordMatch() {
            const pw = document.getElementById("password").value;
            const pw2 = document.getElementById("password2").value;

            if (pw !== pw2) {
                alert("비밀번호가 일치하지 않습니다.");
                return false;
            }
            return true;
        }

        function checkPasswordFormat() {
            const pw = document.getElementById("password").value;
            const pwRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}$/;

            if (!pwRegex.test(pw)) {
                alert("비밀번호는 최소 8자 이상이어야 하며, 문자, 숫자, 특수문자를 포함해야 합니다.");
                return false;
            }
            return true;
        }

        function checkDuplicate() {
            const userid = document.getElementById("userid").value; 
            
            if (userid === "") {
                alert("ID를 입력하세요.");
                return;
            }
            fetch("check_duplicate.php?userid=" + userid)
                .then(res => res.text())
                .then(msg => alert(msg));
        }
        
        function validateForm() {
            if (!checkPasswordFormat()) return false;
            if (!checkPasswordMatch()) return false;
            return true;
        }
    </script>
</body>
</html>
