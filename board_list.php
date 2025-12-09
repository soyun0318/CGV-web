<?php
session_start();
$conn = new mysqli("localhost", "root", "", "sample");

if ($conn->connect_error) {
    die("DB 연결 실패");
}

$result = $conn -> query("SELECT * FROM board ORDER BY id DESC");
$total = $result -> num_rows;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>한줄평 목록 - CGV</title>
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

        /* 헤더 스타일 */
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

        /* 컨텐츠 */
        .content {
            padding: 20px;
        }

        /* 페이지 타이틀 & 글쓰기 버튼 */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 12px;
        }
        
        .page-title {
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }

        .btn-write {
            background-color: #dc2626;
            color: white;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-write:hover { background-color: #b91c1c; }

        /* 게시판 테이블 */
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        
        th {
            background-color: #f9fafb;
            color: #555;
            padding: 10px 4px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            text-align: center;
        }

        td {
            padding: 12px 4px;
            border-bottom: 1px solid #f3f4f6;
            color: #333;
            text-align: center;
        }

        /* 제목 */
        .col-title {
            text-align: left;
            padding-left: 8px;
        }
        .col-title a {
            color: #111;
            text-decoration: none;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis; 
            max-width: 160px;
        }
        
        /* 부가 정보 */
        .col-small {
            font-size: 12px;
            color: #888;
        }

        /* 리스트가 없을 때 */
        .empty-list {
            text-align: center;
            padding: 40px 0;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="app-container">
        <div class="header">
            <h1>CGV</h1>
            <!-- 홈으로 돌아가는 링크 -->
            <a href="home.php" class="header-link">홈으로</a>
        </div>

        <div class="content">
            <div class="page-header">
                <h2 class="page-title">영화 한줄평</h2>
                <a href="board_write.php" class="btn-write">글쓰기</a>
            </div>

            <!-- 리스트 -->
            <table>
                <colgroup>
                    <col style="width: 12%"> <!-- 번호 -->
                    <col style="width: auto"> <!-- 제목 -->
                    <col style="width: 20%"> <!-- 작성자 -->
                    <col style="width: 15%"> <!-- 날짜 -->
                </colgroup>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>제목</th>
                        <th>작성자</th>
                        <th>날짜</th>
                        <th>조회수</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($total == 0): ?>
                        <tr><td colspan="4" class="empty-list">작성된 한줄평이 없습니다.</td></tr>
                    <?php else: ?>
                        <?php while($row = $result -> fetch_assoc()) { 
                            $date = date("m.d", strtotime($row['created_at']));
                        ?>
                            <tr>
                                <td class="col-small"><?=$total--?></td>
                                <td class="col-title">
                                    <a href="board_view.php?id=<?=$row['id']?>">
                                        <?= htmlspecialchars($row['title']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($row['userid']) ?></td>
                                <td class="col-small"><?= $date ?></td>
                                <td class="col-small"><?= $row['hits'] ?></td>
                            </tr>
                        <?php } ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

