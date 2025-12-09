<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>영화 등록 - CGV</title>
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

        input[type="file"] {
            padding: 10px;
            background-color: white;
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

        .submit-btn:hover { background-color: #b91c1c; }
        .submit-btn:active { transform: scale(0.98); }

    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
            <a href="movie_admin_list.php" class="header-link">취소</a>
        </div>

        <div class="form-container">
            <h2 class="page-title">영화 등록</h2>

            <form action="movie_save.php" method="post" enctype="multipart/form-data">
                
                <div class="form-group">
                    <label class="form-label">영화 제목</label>
                    <input type="text" name="name" class="form-input" placeholder="영화 제목 입력" required>
                </div>

                <div class="form-group">
                    <label class="form-label">감독</label>
                    <input type="text" name="director" class="form-input" placeholder="감독 이름 입력" required>
                </div>

                <div class="form-group">
                    <label class="form-label">영화 설명 (줄거리)</label>
                    <textarea name="description" class="form-textarea" rows="5" placeholder="간단한 줄거리를 입력하세요."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">포스터 이미지</label>
                    <input type="file" name="img" class="form-input" accept="image/*" required>
                </div>

                <button type="submit" class="submit-btn">등록하기</button>
            </form>
        </div>
    </div>

</body>
</html>