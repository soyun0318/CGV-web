<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글쓰기</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Malgun Gothic', -apple-system, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
        }

        /* 컨테이너 */
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

        .form-input, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background-color: #f9fafb;
            color: #111;
            transition: all 0.2s;
            font-family: inherit; /* 폰트 상속 */
        }

        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: #dc2626;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
        
        .form-textarea {
            resize: none; 
            line-height: 1.5;
        }

        /* 저장 버튼 */
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
            <a href="board_list.php" class="header-link">취소</a>
        </div>

        <div class="form-container">
            <h2 class="page-title">한줄평 작성</h2>

            <form action="board_save.php" method="post">
                <div class="form-group">
                    <label class="form-label" for="title">제목</label>
                    <input type="text" id="title" name="title" class="form-input" placeholder="제목을 입력하세요" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="content">내용</label>
                    <textarea id="content" name="content" class="form-textarea" rows="10" placeholder="영화에 대한 감상평을 남겨주세요." required></textarea>
                </div>

                <input type="submit" class="submit-btn" value="등록하기">
            </form>
        </div>
    </div>

</body>
</html>