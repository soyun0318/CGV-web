<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sample");
$id = $_GET['id'];
$conn->query("UPDATE board SET hits = hits + 1 WHERE id = $id");
$result = $conn->query("SELECT * FROM board WHERE id = $id");
$row = $result->fetch_assoc();

$likes_result = $conn->query("SELECT COUNT(*) AS cnt FROM board_likes WHERE board_id = $id");
$like_count = $likes_result->fetch_assoc()['cnt'];
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>글 보기 - CGV</title>
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
        .header h1 { font-size: 24px; font-weight: bold; letter-spacing: -0.5px; }
        .header-link { color: white; text-decoration: none; font-size: 14px; opacity: 0.9; }

        /* 컨텐츠 영역 */
        .content { padding: 20px; }

        /* 게시글 제목 영역 */
        .post-header {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .post-title {
            font-size: 20px;
            font-weight: bold;
            color: #111;
            margin-bottom: 10px;
            line-height: 1.3;
        }
        .post-meta {
            font-size: 13px;
            color: #888;
            display: flex;
            justify-content: space-between;
        }

        /* 본문 영역 */
        .post-body {
            min-height: 200px;
            font-size: 15px;
            line-height: 1.6;
            color: #333;
            margin-bottom: 30px;
        }

        /* 공감 버튼 스타일 */
        .like-form {
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-like {
            background-color: white;
            border: 2px solid #dc2626;
            color: #dc2626;
            padding: 10px 24px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            box-shadow: 0 2px 5px rgba(220, 38, 38, 0.1);
        }
        .btn-like:hover {
            background-color: #fef2f2;
            transform: scale(1.05);
        }
        .btn-like:active { transform: scale(0.95); }

        /* 하단 버튼 그룹 */
        .btn-group {
            display: flex;
            gap: 10px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        .btn-action {
            flex: 1;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
        }
        .btn-list {
            background-color: #374151;
            color: white;
        }
        .btn-delete {
            background-color: #e5e7eb;
            color: #374151;
        }
        
    </style>
</head>
<body>

    <div class="app-container">
        <!-- 헤더 -->
        <div class="header">
            <h1>CGV</h1>
            <a href="board_list.php" class="header-link">목록</a>
        </div>

        <div class="content">
            <!-- 제목 및 정보 -->
            <div class="post-header">
                <h2 class="post-title"><?= htmlspecialchars($row['title']) ?></h2>
                <div class="post-meta">
                    <span><?= htmlspecialchars($row['userid']) ?></span>
                    <span><?= date("Y.m.d H:i", strtotime($row['created_at'])) ?> · 조회 <?= $row['hits'] ?></span>
                </div>
            </div>

            <!-- 본문 -->
            <div class="post-body">
                <?= nl2br(htmlspecialchars($row['content'])) ?>
            </div>
            
            <!-- 공감 버튼 (board_like.php로 POST 전송) -->
            <form method="post" action="board_like.php" class="like-form">
                <input type="hidden" name="board_id" value="<?= $id ?>">
                <button type="submit" class="btn-like">
                    ❤️ 공감해요 <?= $like_count ?>
                </button>
            </form>

            <!-- 하단 버튼 -->
            <div class="btn-group">
                <a href='board_list.php' class="btn-action btn-list">목록으로</a>
                <!-- 본인 글일 때만 삭제 버튼이 보이게 하려면 조건문 필요 (현재는 그냥 둠) -->
                <a href='board_delete.php?id=<?= $id ?>' class="btn-action btn-delete" onclick="return confirm('정말 삭제하시겠습니까?');">삭제</a>
            </div>
        </div>
    </div>

</body>
</html>